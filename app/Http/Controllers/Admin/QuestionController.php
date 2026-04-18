<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuestionAttachment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('quiz')->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $quizzes = Quiz::all();
        return view('admin.questions.form', compact('quizzes'));
    }

    protected function validationRules() {
        return [
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'explanation' => 'nullable|string',
            'option_text' => 'required|array|size:4',
            'option_text.*' => 'required|string',
            'correct_option' => 'required|in:0,1,2,3',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:10240',
        ];
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        $question = Question::create([
            'quiz_id' => $request->quiz_id,
            'question_text' => $request->question_text,
            'explanation' => $request->explanation,
        ]);

        // Save options
        foreach ($request->option_text as $index => $text) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $text,
                'is_correct' => ($index == $request->correct_option),
            ]);
        }

        // Handle attachment
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $type = strpos($request->file('attachment')->getMimeType(), 'video') !== false ? 'video' : 'image';

            QuestionAttachment::create([
                'question_id' => $question->id,
                'type' => $type,
                'path' => $path,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully');
    }

    public function edit(Question $question)
    {
        $quizzes = Quiz::all();
        $options = $question->options;
        return view('admin.questions.form', compact('question', 'quizzes', 'options'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate($this->validationRules());

        $question->update([
            'quiz_id' => $request->quiz_id,
            'question_text' => $request->question_text,
            'explanation' => $request->explanation,
        ]);

        // Update options
        $question->options()->delete();
        foreach ($request->option_text as $index => $text) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $text,
                'is_correct' => ($index == $request->correct_option),
            ]);
        }

        // Handle attachment
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($question->attachment) {
                Storage::disk('public')->delete($question->attachment->path);
                $question->attachment()->delete();
            }

            $path = $request->file('attachment')->store('attachments', 'public');
            $type = strpos($request->file('attachment')->getMimeType(), 'video') !== false ? 'video' : 'image';

            QuestionAttachment::create([
                'question_id' => $question->id,
                'type' => $type,
                'path' => $path,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully');
    }

    public function destroy(Question $question)
    {
        // Delete attachment if exists
        if ($question->attachment) {
            Storage::disk('public')->delete($question->attachment->path);
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully');
    }
}
