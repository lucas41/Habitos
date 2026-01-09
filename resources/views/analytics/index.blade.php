@extends('layouts.app')
@section('content')
<div class="space-y-8 animate-in zoom-in-95 duration-500 pb-12">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
             <div class="flex items-center justify-between mb-2">
                <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Ofensiva</span>
                <span class="text-2xl">🔥</span>
             </div>
             <div class="text-3xl font-black text-orange-500 mb-1">{{ $streak }}</div>
             <div class="text-slate-400 text-xs font-medium">Dias seguidos</div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
             <div class="text-3xl font-black text-slate-800 mb-1">{{ $totalDone }}</div>
             <div class="text-slate-400 text-xs font-medium">Conclusões totais</div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
             <div class="flex items-center justify-between mb-2">
                <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Taxa Média</span>
                <span class="text-2xl">🎯</span>
             </div>
             <div class="text-3xl font-black text-slate-800 mb-1">{{ $globalCompletion }}%</div>
             <div class="text-slate-400 text-xs font-medium">De conclusão global</div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
             <div class="flex items-center justify-between mb-2">
                <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Melhor Hábito</span>
                <span class="text-2xl">🏆</span>
             </div>
             <div class="text-lg font-black text-slate-800 mb-1 truncate">{{ $bestHabit ? $bestHabit->name : 'Nenhum' }}</div>
             <div class="text-slate-400 text-xs font-medium">Mais frequente</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Trend Chart -->
        <section class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Tendência (7 dias)</h3>
            <div class="w-full h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </section>

        <!-- Ranking Chart -->
        <section class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Ranking</h3>
            <div class="w-full h-64">
                <canvas id="rankingChart"></canvas>
            </div>
        </section>
    </div>

    <!-- Insights -->
    <section class="bg-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <h3 class="text-xl font-bold flex items-center gap-2 mb-6">
                <span class="p-2 bg-indigo-500 rounded-lg">✨</span>
                Análise Inteligente (IA)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($insights as $insight)
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 p-5 rounded-2xl">
                        <h4 class="font-bold text-indigo-100 text-sm uppercase tracking-wider mb-2">{{ $insight['title'] }}</h4>
                        <p class="text-sm text-white/90 leading-relaxed">{{ $insight['content'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script>
    // Trends
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($last7Days, 'name')) !!},
            datasets: [{
                label: 'Conclusões',
                data: {!! json_encode(array_column($last7Days, 'completions')) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                pointRadius: 6,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Ranking
    const ctxRank = document.getElementById('rankingChart').getContext('2d');
    new Chart(ctxRank, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribution->pluck('name')) !!},
            datasets: [{
                label: 'Conclusões',
                data: {!! json_encode($distribution->pluck('logs_count')) !!},
                backgroundColor: '#818cf8',
                borderRadius: 10
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { display: false },
                y: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
