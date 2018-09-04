<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
                'First Name' => $user->first_name,
                'Last Name' => $user->last_name,
                'Cell #' => $user->cell,
                'Email' => $user->email,
                'Initial Weight' => $user->initial_weight,
            ];

            $latestEntry = $user->weightEntries->where('created_at', '>=', Carbon::today()->startOfWeek())->last();

            if ($latestEntry) {
                $userInfo['Updated Weight'] = $latestEntry->weight;
                $diff = $user->initial_weight - $latestEntry->weight;

                $userInfo['Total Difference'] = (float)number_format($diff, 2);

            }

            $formattedUsers[] = $userInfo;
        }

        return $formattedUsers;
    }
}
