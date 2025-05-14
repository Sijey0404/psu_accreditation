<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:departments,name']);

        Department::create([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-', $request->name))
        ]);

        return redirect()->route('departments.create')->with('success', 'Department added successfully!');
    }

    public function show($slug)
    {
        // Fetch the department by slug
        $department = Department::where('slug', $slug)->firstOrFail();

        // Return a view with the department data
        return view('departments.show', compact('department'));
    }
}
