<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Category;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::with('category')->latest()->get();
        $categories = Category::all();
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
        
        Habit::create($data);
        return back()->with('success', 'Hábito criado!');
    }

    public function destroy(Habit $habit)
    {
        $habit->delete();
        return back()->with('success', 'Hábito excluído!');
    }
}
