<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
class UserManageController extends Controller
{
     public function index(){
      $menu = 'User';
    $user = User::where('name', Auth::user()->name)->first();
    $cvPath = asset('storage/' . $user->cv);
    $encryptedUrl = Crypt::encrypt($cvPath);
     return view('admin.user.index', compact('user', 'encryptedUrl','menu'));
   }

   public function indexMain(){
     
      $users = User::all();
     return view('admin.user.main', compact('users'));
   }
  public function create()
{
    // kirim instance kosong supaya $user selalu ada di Blade
    return view('admin.user.create', [
        'user' => new User(),   // <- penting
    ]);
}

public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.user.edit', compact('user'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'username' => 'nullable|string|unique:users,username',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'ttd' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->username = $request->username;
    $user->NIDNorNUPTK = $request->NIDNorNUPTK;
    $user->role = $request->role ?? 'admin';
    $user->password = bcrypt('USHBISDIG9599');

    if ($request->hasFile('photo')) {
        $user->photo = $request->file('photo')->store('users/photo', 'public');
    }
    if ($request->hasFile('ttd')) {
        $user->ttd = $request->file('ttd')->store('users/ttd', 'public');
    }

    $user->save();

    return redirect()->route('user.admin.main')->with('success', 'User berhasil ditambahkan');
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'username' => 'nullable|string|unique:users,username,' . $user->id,
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'ttd' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user->fill($request->only('name','email','username','NIDNorNUPTK','role'));

    if ($request->hasFile('photo')) {
        if ($user->photo) Storage::disk('public')->delete($user->photo);
        $user->photo = $request->file('photo')->store('users/photo', 'public');
    }
    if ($request->hasFile('ttd')) {
        if ($user->ttd) Storage::disk('public')->delete($user->ttd);
        $user->ttd = $request->file('ttd')->store('users/ttd', 'public');
    }

    $user->save();

    return redirect()->route('user.admin.index')->with('success', 'User berhasil diperbarui');
}

}