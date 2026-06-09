<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            're' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('re', $credentials['re'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password) || ! $user->active) {
            throw ValidationException::withMessages([
                're' => ['As credenciais estão incorretas ou a conta ainda não foi ativada.'],
            ]);
        }

        Auth::login($user);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    public function firstAccess(Request $request)
    {
        $data = $request->validate([
            're' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('re', $data['re'])->where('active', false)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                're' => ['O RE não foi encontrado ou a conta já está ativada.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'active' => true,
        ])->save();

        return response()->json([
            'message' => 'Conta ativada com sucesso. Faça login usando o RE e a senha cadastrada.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        Auth::logout();

        return response()->json(['message' => 'Sessão encerrada com sucesso.']);
    }
}
