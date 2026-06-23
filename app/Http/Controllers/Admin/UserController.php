<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $admins = User::where('role', 'admin')->with(['reviewsReceived', 'leaveRequests'])->latest()->get();
        $employees = User::where('role', 'pegawai')->with(['reviewsReceived', 'leaveRequests'])->latest()->get();
        $customers = User::where('role', 'user')->latest()->paginate(15);

        $stats = [
            'total' => User::count(),
            'pegawai' => $employees->count(),
            'pelanggan' => User::where('role', 'user')->count(),
            'admin' => $admins->count(),
        ];

        return view('admin.users.index', compact('admins', 'employees', 'customers', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,pegawai,user',
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $path = public_path('images/profile_akun/' . strtolower($validated['role']));
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $avatar->move($path, $filename);
            $validated['avatar'] = $filename;
        }

        $user = User::create($validated);
        
        // Akun yang dibuat oleh admin otomatis dianggap sudah terverifikasi
        $user->email_verified_at = now();
        $user->save();

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,pegawai,user',
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            $validated['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $path = public_path('images/profile_akun/' . strtolower($validated['role']));
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $avatar->move($path, $filename);
            $validated['avatar'] = $filename;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Hindari admin menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
