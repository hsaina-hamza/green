<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Get all users except the current admin
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = User::ROLES;
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:' . implode(',', User::ROLES)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء الحساب بنجاح');
    }

    public function edit(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك تعديل حسابك من هنا');
        }

        $roles = User::ROLES;
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك تعديل حسابك من هنا');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:' . implode(',', User::ROLES)],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->syncRoles([$validated['role']]);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف الحساب بنجاح');
    }
}
