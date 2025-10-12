<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
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
        // TODO: No api route yet as this route is not needed
        $users = User::paginate(10);
        return ResponseHelper::success($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $validated = $request->validated();
            if (User::where('email', $validated['email'])->first()) {
                return ResponseHelper::error(['Email already exists!'], 'Error storing user!', 400);
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
        try {
            $user = User::find($id);

            if (!$user) {
                return ResponseHelper::error(['User not found!'], 'Error fetching user!', 404);
            }

            return ResponseHelper::success($user, 'User found!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return ResponseHelper::error(['User not found!'], 'Error updating user!', 404);
            }

            $user->update($request->validated());

            return ResponseHelper::success($user, 'User data updated!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }
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
    }
}
