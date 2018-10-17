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
                'Name' => $user->name
            ];

            $latestEntry = $user->weightEntries->where('created_at', '>=', Carbon::today()->startOfWeek())->last();

            foreach ($user->weightEntries as $index => $weight) {
                $userInfo['Weight Week ' . ($index + 1)] = $weight->weight;
            }


            $diff = $user->initial_weight - ($latestEntry->weight ?? $user->initial_weight);

            $userInfo['Total Difference'] = (float)number_format($diff, 2);


            $formattedUsers[] = $userInfo;
        }

        return $formattedUsers;
    }
}
