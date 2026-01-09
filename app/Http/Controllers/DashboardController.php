<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;

use App\Services\StreakService;
use App\Services\GamificationService;

class DashboardController extends Controller
{
    protected $streakService;
    protected $gamificationService;

    public function __construct(StreakService $streakService, GamificationService $gamificationService)
    {
        $this->streakService = $streakService;
        $this->gamificationService = $gamificationService;
    }

    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        
        $habits = Habit::with(['category', 'logs' => function($q) use ($date) {
            $q->where('date', $date);
        }])->get();

        $streak = $this->streakService->getStreak();
        $gamification = $this->gamificationService->getStats();

        return view('dashboard', compact('habits', 'date', 'streak', 'gamification'));
    }

    public function toggle(Request $request, Habit $habit)
    {
        $date = $request->input('date');
        
        $log = $habit->logs()->where('date', $date)->first();
        $message = null;
        
        if ($log) {
            // Toggle
            $log->completed = !$log->completed;
            $log->save();
            
            if ($log->completed) {
                // Re-completed
                $result = $this->gamificationService->addXp(10);
                if ($result['leveledUp']) $message = "Level Up! Agora você é nível " . $result['stats']->level;
            } else {
                // Unchecked
                $this->gamificationService->removeXp(10);
            }

        } else {
            // New completion
            $habit->logs()->create([
                'date' => $date,
                'completed' => true
            ]);
            
            $result = $this->gamificationService->addXp(10);
            if ($result['leveledUp']) $message = "SUBIU DE NÍVEL! Nível " . $result['stats']->level . " alcançado!";
        }
        
        if ($message) {
            return back()->with('level_up', $message);
        }
        
        return back();
    }
}
