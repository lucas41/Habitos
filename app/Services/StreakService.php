<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StreakService
{
    public function getStreak(): int
    {
        $streak = 0;
        
        // Start checking from yesterday to count past streak
        $checkDate = Carbon::yesterday();
        
        while (true) {
            if ($this->isDayPerfect($checkDate)) {
                $streak++;
                $checkDate->subDay();
            } else {
                // If yesterday wasn't perfect, we stop looking back.
                // However, user might have just started today.
                break;
            }
        }

        // Check if today is perfect to increment streak immediately
        if ($this->isDayPerfect(Carbon::today())) {
            $streak++;
        }

        return $streak;
    }

    private function isDayPerfect(Carbon $date): bool
    {
        $formattedDate = $date->format('Y-m-d');
        
        // Count habits that existed on that date
        // Note: This relies on created_at. If habits are deleted, they are gone from count (forgiving logic).
        $totalHabits = Habit::where('user_id', Auth::id())->whereDate('created_at', '<=', $formattedDate)->count();
        
        if ($totalHabits === 0) {
            return false;
        }

        $completedLogs = HabitLog::where('date', $formattedDate)
            ->where('completed', true)
            ->whereHas('habit', function($q) {
                $q->where('user_id', Auth::id());
            }) // Ensure habit still exists and belongs to user
            ->count();

        return $completedLogs >= $totalHabits;
    }
}
