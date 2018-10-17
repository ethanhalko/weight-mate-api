<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Auth\Authorizable;

class Group extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function formatForExport()
    {
        $users = $this->users;
        $formattedUsers = [];

        foreach ($users as $user) {
            $userInfo = [
                'Name' => $user->name,
                'Weight Entry 1' => null,
                'Weight Entry 2' => null,
                'Weight Entry 3' => null,
                'Weight Entry 4' => null,
                'Weight Entry 5' => null,
                'Weight Entry 6' => null,
                'Total Difference' => null,
            ];

            $latestEntry = $user->weightEntries->last();

            if ($user->weightEntries->isEmpty()) {
                $userInfo['Weight Entry 1'] = $user->initial_weight;
            } else {
                foreach ($user->weightEntries as $index => $weight) {
                    $userInfo['Weight Entry ' . ($index + 1)] = $weight->weight;
                }
            }

            if ($latestEntry) {
                $diff = $user->initial_weight - $latestEntry->weight;

                $userInfo['Total Difference'] = (float)number_format($diff, 2);
            }

            $formattedUsers[] = $userInfo;
        }

        return $formattedUsers;
    }
}
