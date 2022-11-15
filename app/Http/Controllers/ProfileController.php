<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePassword;
use Illuminate\Support\Facades\{Auth, Hash};

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(UpdatePassword $request)
    {
        $user = Auth::user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password has been changed');
        } else {
            return back()->with('error', 'Please enter correct current password');
        }
    }
}
