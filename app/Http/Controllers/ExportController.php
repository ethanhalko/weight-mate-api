<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    /**
     * @var Excel $excel
     */
    protected $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function export()
    {
        $groups = Group::where('active', true)->get();

        if($groups->isEmpty()){
            return back()->with('status_export', 'No groups to export');
        }

        $date = strtolower(Carbon::today()->format('F_d_Y'));
        $this->excel->create('challengers_' . $date, function ($excel) use ($groups) {
            foreach ($groups as $group) {
                /**
                 * @var Group $group
                 */
                $excel->sheet($group->name, function ($sheet) use ($group) {
                    $sheet->fromModel($group->formatForExport());
                });
            }
        })->download('xlsx');

    }
}
