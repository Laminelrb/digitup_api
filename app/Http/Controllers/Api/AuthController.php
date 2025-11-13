<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Services\AuthService;
use App\DTOs\LoginUserDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * AuthController
     * 
     * Gère l'authentification API (login / logout).
     */
    public function __construct(private AuthService $service)
    {
        // Protéger la route de déconnexion (token requis)
        $this->middleware('auth:sanctum')->only('logout');
    }

    /**
     * Authentifie un utilisateur et retourne un token.
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $dto = new LoginUserDTO(
            email: $request->email,
            password: $request->password
        );

        $loginResult = $this->service->login($dto);

        return response()->json($loginResult);
    }

    /**
     * Déconnecte l'utilisateur en supprimant le token actif.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnecté avec succès',
        ]);
    }
}
