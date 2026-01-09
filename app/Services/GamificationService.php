<?php

namespace App\Services;

use App\Models\GamificationStats;

class GamificationService
{
    public function getStats(): GamificationStats
    {
        return GamificationStats::firstOrFail();
    }

    public function addXp(int $amount)
    {
        $stats = $this->getStats();
        $stats->current_xp += $amount;
        
        $leveledUp = false;

        // Check level up
        while ($stats->current_xp >= $stats->next_level_xp) {
            $stats->current_xp -= $stats->next_level_xp;
            $stats->level++;
            $stats->next_level_xp = ceil($stats->next_level_xp * 1.5); // Increase difficulty
            $leveledUp = true;
        }

        $stats->save();
        
        return [
            'leveledUp' => $leveledUp,
            'stats' => $stats
        ];
    }

    public function removeXp(int $amount)
    {
        $stats = $this->getStats();
        $stats->current_xp = max(0, $stats->current_xp - $amount);
        $stats->save();
        return $stats;
    }
}
