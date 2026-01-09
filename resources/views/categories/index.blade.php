@extends('layouts.app')
@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
    <section class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">Categorias</h2>
        <button onclick="document.getElementById('createCategory').classList.toggle('hidden')" class="flex items-center gap-2 px-4 py-2 rounded-xl font-bold transition-all bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700">
            + Nova Categoria
        </button>
    </section>

    <!-- Create Form -->
    <div id="createCategory" class="hidden bg-white p-8 rounded-3xl shadow-xl border border-indigo-100 space-y-6">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Nome</label>
                    <input type="text" name="name" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-400 focus:bg-white outline-none transition-all text-lg font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Ícone</label>
                    <input type="text" name="icon" required placeholder="Ex: 🍎" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-400 focus:bg-white outline-none transition-all text-lg font-medium">
                </div>
                <input type="hidden" name="color" value="indigo">
                <button type="submit" class="w-full py-4 rounded-2xl font-bold text-lg shadow-lg transition-all bg-indigo-600 text-white shadow-indigo-200 hover:bg-indigo-700">Criar</button>
            </div>
        </form>
    </div>

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($categories as $category)
            <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        {{ $category->icon }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">{{ $category->name }}</h4>
                        <p class="text-xs font-bold text-slate-400">{{ $category->habits_count }} hábitos</p>
                    </div>
                </div>
                 <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Excluir?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="opacity-0 group-hover:opacity-100 p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
