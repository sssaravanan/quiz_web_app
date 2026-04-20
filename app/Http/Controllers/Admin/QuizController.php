<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('category')->get();
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    protected function validationRules() {
        return [
            'title' => 'required|string|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit' => 'required|integer|min:1|max:180',
            'is_published' => 'nullable|boolean',
        ];
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        Quiz::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'time_limit' => $request->time_limit,
            'is_published' => $request->has('is_published'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz created successfully');
    }

    public function edit(Quiz $quiz)
    {
        $categories = Category::all();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate($this->validationRules());

        $quiz->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'time_limit' => $request->time_limit,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully');
    }
}
