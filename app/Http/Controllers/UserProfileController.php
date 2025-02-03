<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

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
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update data pengguna
        $user->username = $request->input('username');
        $user->nama_lengkap = $request->input('nama_lengkap');
        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }
        $user->save();
    
        alert()->success('Profil berhasil diperbarui!');
    return redirect()->route('kelola-user.edit');
    }
}
