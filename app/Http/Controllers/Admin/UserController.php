<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isBeingDeactivated = $user->is_active; // true means we're about to deactivate
        
        $user->is_active = !$user->is_active;
        $user->save();

        // If user is being deactivated and is currently logged in, logout
        if ($isBeingDeactivated && Auth::check() && Auth::id() == $id) {
            Auth::logout();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $user->is_active ? 'User activated successfully!' : 'User deactivated successfully!',
                'is_active' => $user->is_active
            ]);
        }

        $message = $user->is_active ? 'User activated successfully!' : 'User deactivated successfully!';
        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
