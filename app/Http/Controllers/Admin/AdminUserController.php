<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar pengguna dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $users = User::select('id', 'name', 'email', 'is_admin', 'created_at')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'ilike', '%'.$search.'%')
                    ->orWhere('email', 'ilike', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manajemen-pengguna', compact('users', 'search'));
    }

    /**
     * Hapus pengguna berdasarkan ID.
     */
    public function destroy(string $id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
