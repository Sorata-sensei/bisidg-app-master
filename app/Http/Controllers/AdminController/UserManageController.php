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
     * Management Index - List semua dosen untuk superadmin/masteradmin
     */
    public function managementIndex(Request $request)
    {
        $search = $request->input('search');
        
        $lecturers = User::whereIn('role', ['admin', 'superadmin', 'masteradmin'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('program_studi', 'like', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('admin.management.lecturers.index', compact('lecturers', 'search'));
    }

    /**
     * Destroy user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Jangan hapus user yang sedang login
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Hapus foto dan ttd jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        if ($user->ttd) {
            Storage::disk('public')->delete($user->ttd);
        }

        $user->delete();

        return redirect()->route('admin.management.lecturers.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        $user = new User();
        
        // Tentukan view berdasarkan route
        if (request()->routeIs('admin.management.lecturers.*')) {
            return view('admin.management.lecturers.create', compact('user'));
        }
        
        return view('admin.user.create', compact('user'));
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Tentukan view berdasarkan route
        if (request()->routeIs('admin.management.lecturers.*')) {
            return view('admin.management.lecturers.edit', compact('user'));
        }
        
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $user = new User($request->only(['name', 'email', 'username', 'NIDNorNUPTK', 'role', 'program_studi']));
        $user->role = $request->role ?? 'admin';
        $user->program_studi = $request->program_studi ?? 'Bisnis Digital';
        $user->password = bcrypt('USHBISDIG9599'); // password default hardcoded

        $user->photo = $this->handleUpload($request, 'photo', null, 'users/photo');
        $user->ttd   = $this->handleUpload($request, 'ttd', null, 'users/ttd');

        $user->save();

        // Redirect berdasarkan route yang dipanggil
        if (request()->routeIs('admin.management.lecturers.*')) {
            return redirect()->route('admin.management.lecturers.index')->with('success', 'Dosen berhasil ditambahkan');
        }
        
        return redirect()->route('user.admin.main')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate($this->rules($user->id));

        $user->fill($request->only('name', 'email', 'username', 'NIDNorNUPTK', 'role', 'program_studi'));

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->photo = $this->handleUpload($request, 'photo', $user->photo, 'users/photo');
        $user->ttd   = $this->handleUpload($request, 'ttd', $user->ttd, 'users/ttd');

        $user->save();

        // Redirect berdasarkan route yang dipanggil
        if (request()->routeIs('admin.management.lecturers.*')) {
            return redirect()->route('admin.management.lecturers.index')->with('success', 'Dosen berhasil diperbarui');
        }
        
        return redirect()->route('user.admin.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Validasi rules (bisa dipakai store & update)
     */
    private function rules($id = null): array
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . ($id ?? 'NULL') . ',id',
            'username'      => 'nullable|string|unique:users,username,' . ($id ?? 'NULL') . ',id',
            'program_studi' => 'required|string|max:50',
            'role'          => 'required|string|in:admin,superadmin,masteradmin',
            'password'      => 'nullable|string|min:8',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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