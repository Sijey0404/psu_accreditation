<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtopic;
use App\Models\Department;




class SubtopicController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'subtopic' => 'required|string|max:255',
        ]);

        Subtopic::create([
            'department_id' => $request->department_id,
            'name' => $request->subtopic,
        ]);

        return back()->with('success', 'Subtopic added successfully!');
    }
    public function someFunction()
{
    $departments = Department::with('subtopics')->get();
    return view('your.view', compact('departments'));
}
}
