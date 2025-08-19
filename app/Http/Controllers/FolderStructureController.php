<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FolderStructure;
use Illuminate\Support\Facades\Auth;

class FolderStructureController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role, ['QA', 'Accreditor'])) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $structures = FolderStructure::all();
        return view('maintenance-folder-structures', compact('structures'));
    }

    public function edit($id)
    {
        $structure = FolderStructure::findOrFail($id);
        return view('maintenance-folder-structure-edit', compact('structure'));
    }

    public function update(Request $request, $id)
    {
        $structure = FolderStructure::findOrFail($id);
        $request->validate([
            'area_name' => 'required|string|max:255',
            'folders' => 'required|array',
            'folders.*' => 'required|string|max:255',
        ]);
        $structure->update([
            'area_name' => $request->area_name,
            'folders' => $request->folders,
        ]);
        return redirect()->route('folder-structures.index')->with('success', 'Folder structure updated successfully!');
    }
}
