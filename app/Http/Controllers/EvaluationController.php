<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $evaluations = Evaluation::with('department')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.evaluation', compact('departments', 'evaluations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area' => 'required|string',
            'program' => 'required|exists:departments,id',
            'strengths' => 'required|string',
            'improvements' => 'required|string',
            'recommendations' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $evaluation = Evaluation::create([
            'area' => $request->area,
            'department_id' => $request->program,
            'strengths' => $request->strengths,
            'improvements' => $request->improvements,
            'recommendations' => $request->recommendations,
            'rating' => $request->rating,
            'evaluator_id' => Auth::id(),
        ]);

        return redirect()->route('reports.evaluation')
            ->with('success', 'Evaluation report created successfully!');
    }

    public function edit(Evaluation $evaluation)
    {
        $departments = Department::all();
        return view('reports.evaluation.edit', compact('evaluation', 'departments'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'area' => 'required|string',
            'program' => 'required|exists:departments,id',
            'strengths' => 'required|string',
            'improvements' => 'required|string',
            'recommendations' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $evaluation->update([
            'area' => $request->area,
            'department_id' => $request->program,
            'strengths' => $request->strengths,
            'improvements' => $request->improvements,
            'recommendations' => $request->recommendations,
            'rating' => $request->rating,
        ]);

        return redirect()->route('reports.evaluation')
            ->with('success', 'Evaluation report updated successfully!');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return redirect()->route('reports.evaluation')
            ->with('success', 'Evaluation report deleted successfully!');
    }
} 