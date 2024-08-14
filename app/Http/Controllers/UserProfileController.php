<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('pages.kelola-user', compact('user'));
    }

    public function update(Request $request)
    {

        $user = auth()->user();

        // Validasi form
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update data pengguna
        $user->username = $request->input('username');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return redirect()->route('kelola-user.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
