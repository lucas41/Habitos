@extends('layouts.app')

@section('content')
<div class="space-y-6 animate-in slide-in-from-bottom-4 duration-500">
    <!-- Header & Gamification -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Streak Card -->
        <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-3xl p-6 text-white shadow-lg shadow-orange-200 dark:shadow-none flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="z-10">
                <p class="text-orange-100 font-bold text-sm uppercase mb-1 flex items-center gap-2">
                    <span class="text-xl">🔥</span> Sequência
                </p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold">{{ $streak }}</span>
                    <span class="text-lg font-medium opacity-80">dias</span>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 text-9xl opacity-20 rotate-12 group-hover:scale-110 transition-transform duration-500">🔥</div>
        </div>

        <!-- Daily Goal Card -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-400 rounded-3xl p-6 text-white shadow-lg shadow-blue-200 dark:shadow-none flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="z-10">
                <p class="text-blue-100 font-bold text-sm uppercase mb-1 flex items-center gap-2">
                    <span class="text-xl">🎯</span> Hoje
                </p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold">{{ $dailyCompletion }}%</span>
                </div>
            </div>
            <div class="w-full h-3 bg-blue-900/30 rounded-full mt-4 overflow-hidden backdrop-blur-sm z-10">
                <div class="h-full bg-white transition-all duration-[1500ms] ease-out w-0" id="daily-bar" data-width="{{ $dailyCompletion }}%"></div>
            </div>
            <div class="absolute -right-4 -bottom-4 text-9xl opacity-20 rotate-12 group-hover:scale-110 transition-transform duration-500">🎯</div>
        </div>

        <!-- Level Card -->
        <div class="bg-indigo-600 dark:bg-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-indigo-200 dark:shadow-none relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-indigo-200 font-bold text-sm uppercase mb-1">Nível {{ $gamification->level }}</p>
                    <h3 class="text-xl font-bold">Mestre</h3>
                </div>
                <div class="text-indigo-200 font-mono text-xs">
                    {{ $gamification->current_xp }}/{{ $gamification->next_level_xp }} XP
                </div>
            </div>
            
            <!-- XP Bar -->
            <div class="w-full h-4 bg-indigo-900/30 rounded-full overflow-hidden relative z-10 backdrop-blur-sm">
                <div id="xp-bar-fill" 
                     class="h-full bg-gradient-to-r from-teal-300 to-emerald-400 transition-all duration-[1500ms] ease-out w-0" 
                     data-width="{{ min(($gamification->current_xp / max($gamification->next_level_xp, 1)) * 100, 100) }}%">
                </div>
            </div>
            
            <div class="absolute -right-8 -top-8 text-9xl opacity-10 rotate-12 group-hover:scale-110 transition-transform duration-500">🏆</div>
        </div>
    </div>

    <!-- Date Navigation -->
    <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
        <a href="{{ route('dashboard', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}" class="p-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors text-slate-400 hover:text-indigo-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        
        <div class="flex items-center gap-3">
            <h2 class="font-bold text-slate-700 dark:text-slate-200 text-lg capitalize">
                {{ \Carbon\Carbon::parse($date)->locale('pt_BR')->translatedFormat('l, d \d\e F') }}
            </h2>
            @if($date !== date('Y-m-d'))
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg hover:bg-indigo-100 transition-colors">Voltar para Hoje</a>
            @endif
        </div>

        <a href="{{ route('dashboard', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}" class="p-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors text-slate-400 hover:text-indigo-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>

    <!-- Level Up Alert -->
    @if(session('level_up'))
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-6 rounded-3xl shadow-lg relative overflow-hidden animate-bounce-short">
            <div class="flex items-center gap-4 relative z-10">
                <div class="text-4xl">🎉</div>
                <div>
                    <h3 class="font-bold text-xl">PARABÉNS!</h3>
                    <p class="font-medium opacity-90">{{ session('level_up') }}</p>
                </div>
            </div>
            <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10"></div>
        </div>
    @endif

    <!-- Habits List -->
    <div class="grid grid-cols-1 gap-3">
        @forelse($habits as $habit)
            @php
                $isCompleted = $habit->logs->where('completed', true)->first();
            @endphp
            <form action="{{ route('habit.toggle', $habit) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                
                <button type="submit" class="w-full text-left group">
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border-2 {{ $isCompleted ? 'border-green-500 dark:border-green-600 bg-green-50/50 dark:bg-green-900/10' : 'border-transparent hover:border-slate-200 dark:hover:border-slate-600' }} shadow-sm transition-all duration-200 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl transition-all duration-300 {{ $isCompleted ? 'bg-green-100 text-green-600 scale-110 rotate-3' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:scale-105' }}">
                                {{ $habit->icon }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg {{ $isCompleted ? 'text-green-700 dark:text-green-400 line-through opacity-70' : 'text-slate-700 dark:text-slate-200' }}">
                                    {{ $habit->name }}
                                </h3>
                                <p class="text-xs font-bold uppercase tracking-wider {{ $isCompleted ? 'text-green-600/70' : 'text-slate-400' }}">
                                    {{ $habit->category->name ?? 'Geral' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all
                            {{ $isCompleted ? 'bg-green-500 border-green-500 text-white scale-110' : 'border-slate-200 dark:border-slate-600 text-transparent group-hover:border-indigo-400' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                    </div>
                </button>
            </form>
        @empty
            <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                <div class="text-6xl mb-4 opacity-50">🌱</div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-2">Sem hábitos para hoje</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-xs mx-auto">Comece a transformar sua vida adicionando seu primeiro hábito.</p>
                <a href="{{ route('habits.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                    Criar Hábito
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Animate XP Bar
        setTimeout(() => {
            const bar = document.getElementById('xp-bar-fill');
            if(bar && bar.dataset.width) {
                bar.style.width = bar.dataset.width;
            }
            
            // Animate Daily Bar
            const dailyBar = document.getElementById('daily-bar');
            if(dailyBar && dailyBar.dataset.width) {
                dailyBar.style.width = dailyBar.dataset.width;
            }
        }, 100);

        // Confetti Logic
        @if(session('level_up'))
            // Level Up Celebration
            const duration = 3000;
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#6366f1', '#10b981', '#f59e0b']
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#6366f1', '#10b981', '#f59e0b']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        @endif
    });
</script>
@endsection
