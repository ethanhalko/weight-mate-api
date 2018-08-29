<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class WeightEntry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'weight',
    ];

    /**
     * @param $user
     * @return array
     */
    public function getMessage($user)
    {
        $entries = WeightEntry::where('user_id', $user->id)->orderBy('created_at', 'asc')->take(2)->get();

        $thisWeek = $entries->last()->weight;
        $lastWeek = $entries->first()->weight;
        $initial = $user->initial_weight;

        $lastWeek = $entries->count() < 2 ? $initial : $lastWeek;

        $weeklyDiff = floatval(number_format($lastWeek - $thisWeek, 2));
        $totalDiff = floatval(number_format($initial - $thisWeek, 2)) ?? 0;

        $weeklyVerb = $this->getVerb($weeklyDiff);
        $totalVerb = $this->getVerb($totalDiff) ?? '';

        $weeklyDiff = $weeklyDiff < 0 ? abs($weeklyDiff) : $weeklyDiff;
        $totalDiff = $totalDiff < 0 ? abs($totalDiff) : $totalDiff;

        $baseMessage = [
            'weekly' => $weeklyDiff,
            'total' => $totalDiff,
            'total_verb' => $weeklyVerb,
            'weekly_verb' => $totalVerb,
        ];

        $positive = $this->getMessagePayload($baseMessage, 'Congratulations! You are doing great!');
        $encouragement = $this->getMessagePayload($baseMessage, 'Keep up the effort and you will see results!');

        Log::info($thisWeek . ' - This Week');
        Log::info($lastWeek . ' - Last Week');


        if (!$user->gain) {
            return $thisWeek < $lastWeek ? $positive : $encouragement;
        } else {
            return $thisWeek > $lastWeek ? $positive : $encouragement;
        }
    }

    /**
     * @param $diff
     * @return string
     */
    public function getVerb($diff)
    {
        if ($diff < 0) {
            $verb = 'gained';
        } else {
            $verb = 'lost';
        }

        return $verb;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param array $base
     * @param $message
     * @return array
     */
    public function getMessagePayload(Array $base, $message)
    {
        $base['body'] = $message;
        return $base;
    }
}
