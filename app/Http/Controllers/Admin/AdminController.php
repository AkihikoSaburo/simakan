<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bangsal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display the hospital admin dashboard.
     */
    public function dashboard()
    {
        $dapurCount = User::where('role', 'dapur')->count();
        $bangsalCount = User::where('role', 'bangsal')->count();
        $wardsCount = Bangsal::count();
        $todayOrdersCount = Order::whereDate('created_at', today())->count();

        // Get recent activity (e.g. recent orders and users)
        $recentOrders = Order::with([
            'creator',
            'bangsal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('dapurCount', 'bangsalCount', 'wardsCount', 'todayOrdersCount', 'recentOrders'));
    }

    /**
     * Display a listing of dapur and bangsal accounts.
     */
    public function index()
    {
        $users = User::whereIn('role', ['dapur', 'bangsal'])
            ->with([
                'bangsal' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->orderBy('role', 'asc')
            ->orderBy('username', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user (dapur/bangsal).
     */
    public function create()
    {
        $bangsals = Bangsal::orderBy('nama_bangsal', 'asc')->get();
        return view('admin.users.create', compact('bangsals'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['dapur', 'bangsal'])],
            'bangsal_id' => [
                'nullable',
                Rule::requiredIf($request->role === 'bangsal'),
                'exists:bangsals,id'
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
            'role.required' => 'Tipe akun (role) wajib dipilih.',
            'bangsal_id.required_if' => 'Nama Bangsal wajib dipilih untuk akun bertipe Bangsal.',
            'bangsal_id.exists' => 'Bangsal yang dipilih tidak valid.',
        ]);

        User::create([
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'bangsal_id' => $request->role === 'bangsal' ? $request->bangsal_id : null,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if (!in_array($user->role, ['dapur', 'bangsal'])) {
            abort(404);
        }

        $bangsals = Bangsal::orderBy('nama_bangsal', 'asc')->get();
        return view('admin.users.edit', compact('user', 'bangsals'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!in_array($user->role, ['dapur', 'bangsal'])) {
            abort(404);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['dapur', 'bangsal'])],
            'bangsal_id' => [
                'nullable',
                Rule::requiredIf($request->role === 'bangsal'),
                'exists:bangsals,id'
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
            'role.required' => 'Tipe akun (role) wajib dipilih.',
            'bangsal_id.required_if' => 'Nama Bangsal wajib dipilih untuk akun bertipe Bangsal.',
            'bangsal_id.exists' => 'Bangsal yang dipilih tidak valid.',
        ]);

        $data = [
            'username' => $request->username,
            'role' => $request->role,
            'bangsal_id' => $request->role === 'bangsal' ? $request->bangsal_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (!in_array($user->role, ['dapur', 'bangsal'])) {
            abort(404);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
