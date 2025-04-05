<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'QA':
                return view('dashboard.qa');
            case 'DCC':
                return view('dashboard.dcc');
            case 'Accreditor':
                return view('dashboard.accreditor');
            case 'Area Chair':
                return view('dashboard.area-chair');
            case 'Area Member':
                return view('dashboard.area-member');
            default:
                return redirect('/home');
        }
    }
}
