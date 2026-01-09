@extends('layouts.app')
@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
    <section class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">Meus Hábitos</h2>
        <button onclick="document.getElementById('createData').classList.toggle('hidden')" class="flex items-center gap-2 px-4 py-2 rounded-xl font-bold transition-all bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700">
            + Novo Hábito
        </button>
    </section>

    <!-- Create Form -->
    <div id="createData" class="hidden bg-white p-8 rounded-3xl shadow-xl border border-indigo-100 space-y-6">
        <form action="{{ route('habits.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Nome do Hábito</label>
                    <input type="text" name="name" required placeholder="Ex: Beber 2L de água" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-400 focus:bg-white outline-none transition-all text-lg font-medium">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Categoria</label>
                        <select name="category_id" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-400 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Ícone</label>
                        <select name="icon" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-400 focus:bg-white outline-none transition-all text-2xl cursor-pointer">
                            @foreach(['💧', '🏃', '📚', '🧘', '🍎', '💰', '🛌', '🎸', '💻', '🪴', '🧹', '🚶'] as $icon)
                                <option value="{{ $icon }}">{{ $icon }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-4 rounded-2xl font-bold text-lg shadow-lg transition-all bg-indigo-600 text-white shadow-indigo-200 hover:bg-indigo-700">Criar Hábito</button>
            </div>
        </form>
    </div>

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($habits as $habit)
            <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        {{ $habit->icon }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">{{ $habit->name }}</h4>
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">{{ $habit->category ? $habit->category->name : '' }}</p>
                    </div>
                </div>
                <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('Excluir?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="opacity-0 group-hover:opacity-100 p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="text-4xl mb-4">✍️</div>
                <p class="text-slate-500 font-medium">Você ainda não tem hábitos criados.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
