<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class ChangeUserSettings extends Controller
{
    /**
     * Handles an incoming user settings update request.
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

        return back()->with('success', 'Settings successfully changed');
    }

    /**
     * Handles an incoming password update request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_password(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $auth = Auth::user();

        if(!Hash::check($request->get('current_password'), $auth->password)) {
            return back()->with('current_password_error', "Current password is invalid");
        }

        if (strcmp($request->get('current_password'), $request->new_password) == 0) {
            return back()->with("new_password_error", "New Password cannot be same as your current password.");
        }

        $user = User::find($auth->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('password_change_success', 'Password successfully changed');
    }
}
