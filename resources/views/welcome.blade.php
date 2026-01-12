<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitly - Construa sua melhor versão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-indigo-600 tracking-tight">Habitly</h1>
            <div class="flex gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                        Acessar App
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl font-bold text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                        Começar Agora
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col justify-center items-center text-center px-6 py-20 bg-gradient-to-b from-gray-50 to-indigo-50/50">
        <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-full px-4 py-1.5 text-indigo-700 text-sm font-bold mb-8 animate-bounce w-fit">
            <span class="text-lg">🔥</span> Comece sua sequência hoje!
        </div>
        <h2 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-6 tracking-tight leading-tight max-w-4xl mx-auto">
            Transforme pequenos hábitos em <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">grandes conquistas</span>.
        </h2>
        <p class="text-lg md:text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
            Organize sua rotina, acompanhe seu progresso e suba de nível na vida real com o Habitly. A melhor plataforma para construir consistência.
        </p>
        
        <div class="flex gap-4 flex-col sm:flex-row w-full sm:w-auto">
            @auth
                 <a href="{{ route('dashboard') }}" class="px-8 py-4 rounded-2xl font-bold text-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 hover:-translate-y-1">
                    Ir para meu Checklist 🚀
                </a>
            @else
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl font-bold text-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 hover:-translate-y-1">
                    Criar conta grátis
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl font-bold text-lg text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-indigo-200 transition-all shadow-sm hover:shadow-md">
                    Já tenho conta
                </a>
            @endauth
        </div>

        <!-- Features Preview -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto w-full text-left">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl mb-4">✅</div>
                <h3 class="font-bold text-xl mb-2 text-gray-800">Checklist Diário</h3>
                <p class="text-gray-500">Acompanhe suas tarefas diárias de forma simples e visual.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl mb-4">🔥</div>
                <h3 class="font-bold text-xl mb-2 text-gray-800">Sequências</h3>
                <p class="text-gray-500">Mantenha o foco e não quebre a corrente de dias produtivos.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl mb-4">🏆</div>
                <h3 class="font-bold text-xl mb-2 text-gray-800">Gamificação</h3>
                <p class="text-gray-500">Ganhe XP, suba de nível e torne sua rotina divertida.</p>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-100 py-8 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} Habitly. Todos os direitos reservados.
    </footer>

</body>
</html>
