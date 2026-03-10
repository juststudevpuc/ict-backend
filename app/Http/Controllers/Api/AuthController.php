<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==========================================
    // 1. REGISTRATION (For Regular Users Only)
    // ==========================================
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6' // Add 'confirmed' here later if you add a confirm password field in React
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // ✅ Force new accounts to be normal users
        ]);

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    // ==========================================
    // 2. USER LOGIN (Blocks Admins)
    // ==========================================
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid email or password.'], 401);
        }

        // ✅ SECURITY: Stop admins from logging into the student/user portal
        if ($user->role === 'admin') {
            return response()->json(['status' => false, 'message' => 'Admins must use the Admin Portal.'], 403);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // ==========================================
    // 3. ADMIN LOGIN (Blocks Regular Users)
    // ==========================================
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid email or password.'], 401);
        }

        // ✅ SECURITY: Stop regular users from logging into the Admin portal
        if ($user->role !== 'admin') {
            return response()->json(['status' => false, 'message' => 'Access Denied. Admins only.'], 403);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Admin Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Keep your logout method exactly the same!
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }
}
