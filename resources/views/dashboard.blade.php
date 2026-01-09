@extends('layouts.app')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <!-- Level Up Notification -->
    @if(session('level_up'))
    <div class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
        <div class="bg-indigo-600 text-white px-8 py-4 rounded-3xl shadow-2xl animate-in zoom-in slide-in-from-bottom-10 duration-1000 border-4 border-white pointer-events-auto">
            <div class="text-4xl mb-2 text-center">🎉</div>
            <div class="text-xl font-black uppercase tracking-widest">{{ session('level_up') }}</div>
        </div>
    </div>
    @endif

    <!-- Header Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Date Header -->
        <header class="md:col-span-2 flex flex-col md:flex-row items-center justify-between bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 gap-4 transition-colors">
            <div class="flex items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white capitalize transition-colors">
                        {{ \Carbon\Carbon::parse($date)->locale('pt_BR')->translatedFormat('l') }}
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d \d\e F') }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-900 p-1 rounded-xl transition-colors">
                <a href="{{ route('dashboard', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}" class="p-2 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-all text-slate-600 dark:text-slate-300">◀</a>
                <input type="date" value="{{ $date }}" onchange="window.location.href='?date='+this.value" class="bg-transparent border-none outline-none text-sm font-bold text-slate-600 dark:text-slate-300 w-32 text-center dark:color-white" />
                <a href="{{ route('dashboard', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}" class="p-2 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-all text-slate-600 dark:text-slate-300">▶</a>
            </div>
        </header>

        <!-- Stats Column -->
        <div class="space-y-4">
             <!-- Streak Counter -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-between transition-colors">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ofensiva</span>
                <div class="flex items-center gap-2 text-orange-500 font-black text-xl">
                    <span class="animate-pulse">🔥</span> {{ $streak }}
                </div>
            </div>

             <!-- XP / Level -->
             <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nível {{ $gamification->level }}</span>
                    <span class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400">{{ $gamification->current_xp }} / {{ $gamification->next_level_xp }} XP</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-900 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ ($gamification->current_xp / $gamification->next_level_xp) * 100 }}%"></div>
                </div>
             </div>
        </div>
    </div>


    <!-- Progress Bar -->
    @php
        $total = $habits->count();
        $done = $habits->filter(fn($h) => $h->logs->where('completed', true)->isNotEmpty())->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;
    @endphp
    
    <div class="bg-indigo-600 rounded-3xl p-8 text-white flex items-center justify-between shadow-lg shadow-indigo-200 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none group-hover:bg-white/20 transition-all duration-700"></div>
        <div class="relative z-10">
            <div class="text-indigo-200 font-bold mb-1 uppercase tracking-wider text-xs">Progresso Diário</div>
            <div class="text-4xl font-black flex items-baseline">
                <span id="progress-text">0</span>%
            </div>
            <div class="text-indigo-200 text-sm mt-1">{{ $done }} de {{ $total }} hábitos completados</div>
        </div>
        <div class="w-24 h-24 relative">
             <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90 drop-shadow-lg">
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="4" stroke-linecap="round" />
                <path id="progress-ring" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" class="transition-all duration-1000 ease-out" stroke="white" stroke-width="4" stroke-dasharray="0, 100" stroke-linecap="round" />
            </svg>
        </div>
    </div>

    <!-- Habits List -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($habits as $habit)
            @php
                $isCompleted = $habit->logs->where('completed', true)->isNotEmpty();
            @endphp
            <form action="{{ route('habit.toggle', $habit) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <button type="submit" class="w-full bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 flex items-center justify-between group hover:border-indigo-300 dark:hover:border-indigo-700 transition-all text-left relative overflow-hidden">
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm transition-all duration-300 {{ $isCompleted ? 'bg-indigo-600 text-white scale-110' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 group-hover:scale-110' }}">
                            {{ $habit->icon }}
                        </div>
                        <div>
                            <h4 class="font-bold text-lg transition-all {{ $isCompleted ? 'text-slate-800 dark:text-slate-200 line-through decoration-slate-300 dark:decoration-slate-600 decoration-2 opacity-75' : 'text-slate-800 dark:text-slate-200' }}">
                                {{ $habit->name }}
                            </h4>
                            <p class="text-xs font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-wider bg-indigo-50 dark:bg-indigo-900/30 inline-block px-2 py-1 rounded-md mt-1">
                                {{ $habit->category ? $habit->category->name : 'Geral' }}
                            </p>
                        </div>
                    </div>
                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all relative z-10 {{ $isCompleted ? 'bg-indigo-600 border-indigo-600 scale-110' : 'border-slate-300 dark:border-slate-600 group-hover:border-indigo-400 dark:group-hover:border-indigo-500' }}">
                        @if($isCompleted)
                            <svg class="w-5 h-5 text-white animate-in zoom-in spin-in-90 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"></path></svg>
                        @endif
                    </div>
                    
                    @if($isCompleted)
                        <div class="absolute inset-0 bg-indigo-50/50 dark:bg-indigo-900/10 backdrop-blur-[1px] transition-all duration-500"></div>
                    @endif
                </button>
            </form>
        @empty
            <div class="text-center py-12">
                <p class="text-slate-500 dark:text-slate-400">Nenhum hábito encontrado.</p>
                <a href="{{ route('habits.index') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Criar hábitos</a>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const targetProgress = {{ $progress }};
        const progressRing = document.getElementById('progress-ring');
        const progressText = document.getElementById('progress-text');
        
        // Animate Ring
        setTimeout(() => {
            progressRing.setAttribute('stroke-dasharray', `${targetProgress}, 100`);
        }, 100);

        // Animate Number
        let currentProgress = 0;
        const duration = 1000;
        const interval = 20;
        const steps = duration / interval;
        const increment = targetProgress / steps;

        if (targetProgress > 0) {
            const timer = setInterval(() => {
                currentProgress += increment;
                if (currentProgress >= targetProgress) {
                    currentProgress = targetProgress;
                    clearInterval(timer);
                    
                    // Trigger Celebration if 100%
                    if (targetProgress === 100) {
                        triggerConfetti();
                    }
                }
                progressText.textContent = Math.round(currentProgress);
            }, interval);
        } else {
            progressText.textContent = 0;
        }

        function triggerConfetti() {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            var random = function(min, max) { return Math.random() * (max - min) + min; }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                var particleCount = 50 * (timeLeft / duration);
                // since particles fall down, start a bit higher than random
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: random(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: random(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
            
            // Big burst
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
    });
</script>
@endsection
