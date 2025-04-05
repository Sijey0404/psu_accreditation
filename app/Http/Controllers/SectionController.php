<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Storage;


class SectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,docx,jpg,png|max:2048',
            'subtopic_id' => 'required|exists:subtopics,id',
        ]);

        $fileName = null;
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file->storeAs('public/files', $fileName);
        }

        Section::create([
            'subtopic_id' => $request->subtopic_id,
            'name' => $request->name,
            'file_name' => $fileName,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Section added successfully!');
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,docx,jpg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($section->file_name) {
                Storage::delete('public/files/' . $section->file_name);
            }

            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file->storeAs('public/files', $fileName);
            $section->file_name = $fileName;
        }

        $section->name = $request->name;
        $section->status = 'pending';
        $section->save();

        return back()->with('success', 'Section updated successfully!');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);

        if ($section->file_name) {
            Storage::delete('public/files/' . $section->file_name);
        }

        $section->delete();

        return response()->json(['success' => true]);
    }
}
