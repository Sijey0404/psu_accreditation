<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:QA,DCC,Accreditor,Area Chair,Area Member',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        return redirect($this->redirectTo($user));
    }

    protected function redirectTo($user)
    {
        switch ($user->role) {
            case 'QA':
                return '/dashboard/qa';
            case 'DCC':
                return '/dashboard/dcc';
            case 'Accreditor':
                return '/dashboard/accreditor';
            case 'Area Chair':
                return '/dashboard/area-chair';
            case 'Area Member':
                return '/dashboard/area-member';
            default:
                return '/home';
        }
    }
}
