<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    public function authenticate(Request $request)
    {
        $request->validate([
            'uname' => ['required'],
            'pwd' => ['required'],
        ]);

        // Retrieve the user based on the provided username
        $user = User::where('uname', $request->input('uname'))->first();

        // Check if the user exists and verify the password
        if ($user && Hash::check($request->input('pwd'), Hash::make($user->pwd))) {
            $request->session()->regenerate();
            Auth::guard()->login($user);

            // Redirect based on user level
            if (auth()->user()->lvl === 1) {

                return redirect()->route('admin.dashboard')->with([
                    'success' => [
                        'title' => 'Sukses',
                        'message' => 'Login sebagai berhasil!'
                    ]
                ]);
            }

            return redirect()->route('pages.dashboard')->with([
                'success' => [
                    'title' => 'Sukses',
                    'message' => 'Login sebagai berhasil!'
                ]
            ]);
        }

        // Return to login with errors if authentication fails
        return redirect()->route('login')->withInput()->withErrors([
            'uname' => 'Username tidak sesuai',
            'pwd' => 'Password salah',
        ]);
    }



    protected function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with([
            'success' => [
                'title' => 'Sukses',
                'message' => 'Logout Success!'
            ]
        ]);
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function username()
    {
        return 'uname';
    }

    public function landingPage()
    {
        return view('landing-page');
    }
}
