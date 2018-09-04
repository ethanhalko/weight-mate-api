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
    protected $excel;

    protected $sheets = [
        'Deposits',
        'Weigh-In Sheet',
    ];

    public function __construct(Excel $excel)
    {
        $this->middleware('auth.http');
        $this->excel = $excel;
    }

    public function index()
    {
        $groups = Group::where('active', true)->get();

        return view('import-export', ['groups' => $groups]);
    }

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
                $sheets = $reader->select(['first_name', 'last_name', 'cell', 'email', 'barcode', 'week_0_w', 'week_1_w'])->get();


                $users = new Collection();
                $sheets->first()->each(function ($item) use ($users, $group) {
                    $user = $item->all();

                    if (!array_key_exists('first_name', $user)) {
                        return;
                    }

                    $user['pin'] = Hash::make(str_replace('-', '', $user['cell']));
                    $user['group_id'] = $group->id;
                    $users->push(new User($user));
                });

                $weightInfo = $sheets->last();
                $users->each(function ($user) use ($weightInfo) {
                    foreach ($weightInfo->all() as $item) {
                        $item = $item->all();

                        if (!array_key_exists('week_0_w', $item) || !$item['week_0_w']) {
                            return;
                        }
                        if ($item['first_name'] == $user->first_name && $item['last_name'] == $user->last_name) {
                            $user->initial_weight = $item['week_0_w'];
                            $user->save();
                            WeightEntry::create(['user_id' => $user->id, 'weight' => $item['week_0_w']]);
                            if (array_key_exists('week_1_w', $item) && $item['week_1_w']) {
                                WeightEntry::create(['user_id' => $user->id, 'weight' => $item['week_1_w']]);
                            }
                        }
                    }
                });
            });
        return back()->with('successMsg','Successfully imported!');
    }
}
