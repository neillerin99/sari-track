<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'name' => ['required', 'string', 'min:8'],
            ]);

            if (User::where('email', $validated['email'])->first()) {
                return ResponseHelper::error(['Email already exists!'], 'Register failed!', 400);
            }

            $user = User::create($validated);
            return ResponseHelper::success($user, 'User registered!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return ResponseHelper::error(['User not found!'], 'User deletion failed', 404);
            }
            $user->delete();
            return ResponseHelper::success($user, 'Store deleted!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }
    }

    public function signin(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (!Auth::attempt($credentials)) {
                return ResponseHelper::error(['Credentials does not exist on our system!'], 'Sign in failed!', 400);
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $token = $user->createToken('singin')->accessToken;

            return ResponseHelper::success(['token' => $token, 'user' => $user], 'Login in success');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }
    }
}
