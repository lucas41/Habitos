<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#151e2e',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script>
        // Check local storage for theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen pb-24 md:pb-8 flex flex-col items-center text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <header class="w-full max-w-4xl px-6 pt-8 pb-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
           <h1 class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 tracking-tight">Habitly</h1>
           <p class="text-slate-500 dark:text-slate-400 text-sm">Construindo sua melhor versão dia após dia.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleTheme()" class="hidden md:block p-2 rounded-xl bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 shadow-sm border border-slate-200 dark:border-slate-700 transition-all">
                <span class="dark:hidden">🌙</span>
                <span class="hidden dark:inline">☀️</span>
            </button>
            <nav class="hidden md:flex bg-white dark:bg-slate-800 p-1 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Checklist</a>
                <a href="{{ route('habits.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('habits.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Hábitos</a>
                <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Categorias</a>
                <a href="{{ route('analytics.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('analytics.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Análises</a>
            </nav>
        </div>
    </header>

    <main class="w-full max-w-4xl px-4 flex-1">
        @if(session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-100 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
    
    <!-- Mobile Nav -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 px-4 py-3 flex justify-around items-center md:hidden z-50 transition-colors duration-300">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center space-y-1">
            <span class="text-xl {{ request()->routeIs('dashboard') ? 'opacity-100' : 'opacity-40 grayscale' }}">📅</span>
            <span class="text-[9px] font-bold uppercase tracking-wider {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">Checklist</span>
        </a>
        <a href="{{ route('habits.index') }}" class="flex flex-col items-center space-y-1">
            <span class="text-xl {{ request()->routeIs('habits.*') ? 'opacity-100' : 'opacity-40 grayscale' }}">✅</span>
            <span class="text-[9px] font-bold uppercase tracking-wider {{ request()->routeIs('habits.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">Hábitos</span>
        </a>
        <a href="{{ route('categories.index') }}" class="flex flex-col items-center space-y-1">
            <span class="text-xl {{ request()->routeIs('categories.*') ? 'opacity-100' : 'opacity-40 grayscale' }}">🏷️</span>
            <span class="text-[9px] font-bold uppercase tracking-wider {{ request()->routeIs('categories.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">Categorias</span>
        </a>
        <a href="{{ route('analytics.index') }}" class="flex flex-col items-center space-y-1">
            <span class="text-xl {{ request()->routeIs('analytics.*') ? 'opacity-100' : 'opacity-40 grayscale' }}">📈</span>
            <span class="text-[9px] font-bold uppercase tracking-wider {{ request()->routeIs('analytics.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">Dados</span>
        </a>
    </nav>
    
    <!-- Mobile Theme Toggle -->
    <button onclick="toggleTheme()" class="fixed bottom-20 right-4 md:hidden p-4 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-500/50 z-40">
        <span class="dark:hidden">🌙</span>
        <span class="hidden dark:inline">☀️</span>
    </button>
</body>
</html>
