<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Services\StreakService;

class AnalyticsController extends Controller
{
    protected $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    public function index()
    {
        $habits = Habit::where('user_id', Auth::id())->withCount(['logs' => function($q) {
            $q->where('completed', true);
        }])->get();
        
        $streak = $this->streakService->getStreak();
        
        $logs = HabitLog::where('completed', true)
            ->whereHas('habit', function($q) {
                $q->where('user_id', Auth::id());
            })->get();
        $totalDone = $logs->count();
        
        $totalHabits = $habits->count();
        // Calculation similar to React app: totalDone / (habits * 30) (approx)
        // Original: habits.length > 0 ? Math.round((totalDone / (habits.length * Math.max(logs.length / habits.length, 1))) * 100) : 0;
        // The React math seems weird max(logs/habits, 1). 
        // I'll stick to simple ratio: Logs / (Habits * 30 days) * 100? No, let's just use the simpler metric.
        $globalCompletion = $totalHabits > 0 ? round(($totalDone / max($totalHabits * 20, 1)) * 100) : 0;

        $distribution = $habits->sortByDesc('logs_count')->values();
        $bestHabit = $distribution->first();

        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $date = $d->format('Y-m-d');
            $count = $logs->where('date', $date)->count();
            $last7Days[] = [
                'name' => $d->locale('pt_BR')->shortDayName,
                'completions' => $count
            ];
        }
        
        $insights = [
            ['title' => 'Mantenha o foco!', 'content' => 'A consistência é a chave para o sucesso.', 'type' => 'positive'],
            ['title' => 'Dica', 'content' => 'Tente realizar seus hábitos pela manhã.', 'type' => 'tip']
        ];

        return view('analytics.index', compact('habits', 'totalDone', 'globalCompletion', 'bestHabit', 'last7Days', 'insights', 'distribution', 'streak'));
    }
}
