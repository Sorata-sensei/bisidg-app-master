<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class UserManageController extends Controller
{
    /**
     * Tampilkan profil user yang sedang login
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.user.index', compact('user'));
    }

    /**
     * Tampilkan semua user
     */
    public function indexMain()
    {
        $users = User::all();
        return view('admin.user.main', compact('users'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        return view('admin.user.create', [
            'user' => new User(), // instance kosong supaya Blade tidak error
        ]);
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $user = new User($request->only(['name', 'email', 'username', 'NIDNorNUPTK', 'role']));
        $user->role = $request->role ?? 'admin';
        $user->password = bcrypt('USHBISDIG9599'); // password default hardcoded

        $user->photo = $this->handleUpload($request, 'photo', null, 'users/photo');
        $user->ttd   = $this->handleUpload($request, 'ttd', null, 'users/ttd');

        $user->save();

        return redirect()->route('user.admin.main')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate($this->rules($user->id));

        $user->fill($request->only('name', 'email', 'username', 'NIDNorNUPTK', 'role'));

        $user->photo = $this->handleUpload($request, 'photo', $user->photo, 'users/photo');
        $user->ttd   = $this->handleUpload($request, 'ttd', $user->ttd, 'users/ttd');

        $user->save();

        return redirect()->route('user.admin.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Validasi rules (bisa dipakai store & update)
     */
    private function rules($id = null): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . ($id ?? 'NULL') . ',id',
            'username' => 'nullable|string|unique:users,username,' . ($id ?? 'NULL') . ',id',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Handle upload file dengan hapus file lama (kalau ada)
     */
    private function handleUpload(Request $request, string $field, ?string $oldFile, string $path): ?string
    {
        if ($request->hasFile($field)) {
            if ($oldFile) {
                Storage::disk('public')->delete($oldFile);
            }
            return $request->file($field)->store($path, 'public');
        }
        return $oldFile;
    }
}