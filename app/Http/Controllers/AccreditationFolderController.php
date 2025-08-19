<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccreditationFolder;

class AccreditationFolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
        ]);

        AccreditationFolder::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Accreditation Folder added successfully!');
    }

    public function edit($id)
    {
        $folder = AccreditationFolder::findOrFail($id);
        return view('accreditation-folders.edit', compact('folder'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $folder = AccreditationFolder::findOrFail($id);
        $folder->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Accreditation Folder updated successfully!');
    }

    public function destroy($id)
    {
        $folder = AccreditationFolder::findOrFail($id);
        $folder->delete();
        return redirect()->back()->with('success', 'Accreditation Folder deleted successfully!');
    }
} 