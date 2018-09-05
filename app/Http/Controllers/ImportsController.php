<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use App\WeightEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel;

class ImportsController extends Controller
{
    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var array
     */
    protected $sheets = [
        'Deposits',
        'Weigh-In Sheet',
    ];

    /**
     * ImportsController constructor.
     * @param Excel $excel
     */
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $groups = Group::where('active', true)->get();

        return view('import-export', ['groups' => $groups]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $group = $request->input('group');

        $overwriteGroup = $request->input('overwrite');

        /**
         * @var $group Group
         */
        $group = Group::find($group) ?? Group::firstOrCreate(['name' => $group]);

        if ($overwriteGroup) {
            $group->active = false;
            $group->users()->update(['active' => false]);
            $group->save();

            $group = Group::create(['name' => $group->name]);
        }


        $this->excel
            ->selectSheets($this->sheets)
            ->load($request->file('file'), function ($reader) use ($group) {
                $sheets = $reader->select(
                    [
                        'first_name',
                        'last_name',
                        'cell',
                        'email',
                        'barcode',
                        'week_0_w',
                        'week_1_w',
                        'week_2_w',
                        'week_3_w',
                        'week_4_w',
                        'week_5_w',
                        'week_6_w',
                    ]
                )->get();

                $users = new Collection();
                $sheets->first()
                    ->where('first_name', '!=', '')
                    ->each(function ($item) use ($users, $group) {
                        $user = $item->all();

                        if (!array_key_exists('first_name', $user)) {
                            return false;
                        }

                        $user['pin'] = Hash::make(str_replace('-', '', $user['cell']));
                        $user['group_id'] = $group->id;
                        $users->push(new User($user));
                    });
                $weightInfo = $sheets->last()->where('first_name', '!=', '');

                $users->each(function ($user) use ($weightInfo) {

                    $item = $weightInfo->where('first_name', '=', $user->first_name)
                        ->where('last_name', '=', $user->last_name)
                        ->where('week_0_w', '!=', '')
                        ->first();

                    if ($item) {
                        $item = $item->all();
                    }

                    if (!$item || !array_key_exists('week_0_w', $item)) {
                        return;
                    }

                    $user->initial_weight = $item['week_0_w'];
                    $user->save();

                    WeightEntry::create(['user_id' => $user->id, 'weight' => $item['week_0_w']]);

                    for ($i = 1; $i <= 6; ++$i) {
                        $this->createWeeklyWeightEntry($item, $user, $i);
                    }
                });
            });

        return redirect('/import')->with('status', 'Import Complete!');
    }

    /**
     * @param $item
     * @param User $user
     * @param int $week
     */
    public function createWeeklyWeightEntry($item, User $user, int $week)
    {
        if (array_key_exists('week_' . $week . '_w', $item) && $item['week_' . $week . '_w']) {
            WeightEntry::create(['user_id' => $user->id, 'weight' => $item['week_' . $week . '_w']]);
        }
    }
}
