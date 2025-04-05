<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtopic;

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

    public function edit($id)
    {
        $subtopic = Subtopic::findOrFail($id);
        return view('subtopics.edit', compact('subtopic'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $subtopic = Subtopic::findOrFail($id);
        $subtopic->update(['name' => $request->name]);

        return redirect()->route('subtopics.show', $id)->with('success', 'Subtopic updated successfully.');
    }

    public function destroy($id)
    {
        $subtopic = Subtopic::findOrFail($id);
        $subtopic->delete();

        return redirect()->route('dashboard')->with('success', 'Subtopic deleted successfully.');
    }

    public function show($id)
    {
        // Fetch the subtopic by ID and eager load the associated department
        $subtopic = Subtopic::with('department')->findOrFail($id);

        // Pass subtopic and department data to the view
        return view('subtopics.show', compact('subtopic'));
    }

}
