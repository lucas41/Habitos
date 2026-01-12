<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::where('user_id', Auth::id())->with('category')->latest()->get();
        $categories = Category::where('user_id', Auth::id())->get();
        return view('habits.index', compact('habits', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'icon' => 'required|string',
        ]);
        
        $data['color'] = 'indigo'; // Default
        $data['user_id'] = Auth::id();
        
        Habit::create($data);
        return back()->with('success', 'Hábito criado!');
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) abort(403);
        $habit->delete();
        return back()->with('success', 'Hábito excluído!');
    }
}
