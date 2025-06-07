<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // Display users
    public function index()
    {
        $users = User::paginate(5);
        return view('management.user_management', compact('users'));
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
            'password_option' => 'nullable|string|in:default,custom',
            'custom_password' => 'nullable|required_if:password_option,custom|string|min:6',
        ]);

        $password = $request->password_option === 'custom' && $request->custom_password
            ? $request->custom_password
            : 'Psuedu123';

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show edit form for a user
    public function edit(User $user)
    {
        return response()->json($user);
    }

    // Update user data
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('user.management')->with('success', 'User updated successfully!');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.management')->with('success', 'User deleted successfully!');
    }

    // Display Area Members for Area Chair
    public function manageFaculty()
    {
        if (Auth::user()->role !== 'Area Chair') {
            abort(403, 'Unauthorized.');
        }

        $users = User::where('role', 'Area Member')->paginate(5);
        return view('management.faculty_management', compact('users'));
    }

    // Store new Area Member
    public function storeFaculty(Request $request)
    {
        if (Auth::user()->role !== 'Area Chair') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'Area Member',
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('faculty.management')->with('success', 'Faculty member added.');
    }

    // Update faculty member data
    public function updateFaculty(Request $request, User $user)
    {
        if (Auth::user()->role !== 'Area Chair') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('faculty.management')->with('success', 'Faculty member updated successfully.');
    }
}
