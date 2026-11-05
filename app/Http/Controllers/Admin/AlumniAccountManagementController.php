<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlumniAccountManagementController extends Controller
{
    private function pastikanAdmin()
    {
        abort_unless(auth()->user()->role === 'admin', 403);
    }

    private function cariAlumni($id)
    {
        return User::where('role', 'alumni')->findOrFail($id);
    }

    public function edit($id)
    {
        $this->pastikanAdmin();

        $alumni = $this->cariAlumni($id);

        return view('admin.alumni.edit', compact('alumni'));
    }

    public function update(Request $request, $id)
    {
        $this->pastikanAdmin();

        $alumni = $this->cariAlumni($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // Admin hanya dapat mengubah nama alumni.
        $alumni->name = $validated['name'];

        // Password hanya diganti jika diisi.
        if (!empty($validated['password'])) {
            $alumni->password = Hash::make($validated['password']);
        }

        // NIM dan email tidak diubah.
        $alumni->save();

        return redirect()
            ->route('admin.alumni.index')
            ->with('status', 'Data akun alumni berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->pastikanAdmin();

        $alumni = $this->cariAlumni($id);

        $alumni->delete();

        return redirect()
            ->route('admin.alumni.index')
            ->with('status', 'Akun alumni berhasil dihapus.');
    }
}
