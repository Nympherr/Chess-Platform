<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangeUserSettings extends Controller
{
    /**
     * Handle an incoming user settings update request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function change_user_settings(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
        ]);

        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(array('email' => $request->email, 'name' => $request->name));

        return redirect()->route('user-profile')->with('success', 'Settings successfully changed');
    }
}
