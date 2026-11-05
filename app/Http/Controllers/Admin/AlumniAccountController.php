<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AlumniAccountController extends Controller
{
    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.alumni.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:50', 'unique:users,nim'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['nim']),
            'role' => 'alumni',
            'email_verified_at' => null,
            'otp_verified_at' => null,
        ]);

        return redirect()
            ->route('admin.alumni.index')
            ->with('status', 'Akun alumni berhasil dibuat. Password awal adalah NIM alumni.');
    }
}
