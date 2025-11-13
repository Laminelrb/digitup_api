<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Repositories\UserRepositoryInterface;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct(
        private UserService $service,
        private UserRepositoryInterface $repo
    ) {
        // Toutes les routes nécessitent une authentification via Sanctum
        $this->middleware('auth:sanctum');
    }

    /** 
     * Afficher la liste des agents.
     * Accessible uniquement aux administrateurs (via Policy).
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\User::class);

        $users = $this->repo->allAgents();

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users)
        ]);
    }

    /** 
     * Afficher le détail d’un agent spécifique.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->repo->findOrFail($id);
        $this->authorize('view', $user);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    /** 
     * Créer un nouvel agent.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', \App\Models\User::class);

        $dto = $request->toDTO();
        $user = $this->service->createFromDTO($dto);

        return response()->json([
            'success' => true,
            'message' => 'Agent créé avec succès',
            'data' => new UserResource($user)
        ], 201);
    }

    /** 
     * Mettre à jour les informations d’un agent existant.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->repo->findOrFail($id);
        $this->authorize('update', $user);

        $dto = $request->toDTO();
        $user = $this->service->updateFromDTO($user, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Agent mis à jour avec succès',
            'data' => new UserResource($user)
        ]);
    }

    /** 
     * Supprimer un agent du système.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->repo->findOrFail($id);
        $this->authorize('delete', $user);

        $this->service->delete($user);

        return response()->json([
            'success' => true,
            'message' => 'Agent supprimé avec succès'
        ]);
    }
}
