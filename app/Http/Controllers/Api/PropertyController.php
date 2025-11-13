<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Services\PropertyService;
use App\Repositories\PropertyRepositoryInterface;
use App\Http\Resources\PropertyResource;
use App\DTOs\CreatePropertyDTO;
use App\DTOs\FilterPropertiesDTO;
use App\DTOs\UpdatePropertyDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class PropertyController extends Controller
{
    /**
     * Injection du service métier et du repository.
     * 
     * Seules les routes "index" et "show" sont publiques (pas besoin de token).
     */
    public function __construct(
        private PropertyService $service,
        private PropertyRepositoryInterface $repo
    ) {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Liste paginée des propriétés avec filtres optionnels.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\Property::class);

        // Construction du DTO pour filtrer la recherche
        $dto = new FilterPropertiesDTO(
            city: $request->input('city'),
            type: $request->input('type'),
            minPrice: $request->input('minPrice'),
            maxPrice: $request->input('maxPrice'),
            status: $request->input('status'),
            q: $request->input('q'),
            sortBy: $request->input('sortBy'),
            sortDir: $request->input('sortDir')
        );

        $perPage = (int) $request->input('per_page', 15);

        // Récupération via le repository (avec pagination + filtres)
        $paginator = $this->repo->paginateFiltered($dto, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Liste des propriétés récupérée avec succès.',
            'data' => PropertyResource::collection($paginator)->response()->getData(true)
        ], 200);
    }

    /**
     * Création d’une nouvelle propriété.
     */
    public function store(StorePropertyRequest $request): JsonResponse
    {
        $this->authorize('create', \App\Models\Property::class);

        // Encapsulation des données de création dans un DTO
        $dto = new CreatePropertyDTO(
            userId: $request->user()->id,
            type: $request->type,
            nbr_piece: $request->nbr_piece,
            surface: $request->surface,
            price: $request->price,
            city: $request->city,
            description: $request->description,
            status: $request->status ?? 'disponible',
            published: (bool) $request->published,
            images: $request->file('images') ?? []
        );

        $property = $this->service->createFromDTO($dto);

        return response()->json([
            'success' => true,
            'message' => 'Propriété créée avec succès.',
            'data' => new PropertyResource($property)
        ], 201);
    }

    /**
     * Affichage des détails d’une propriété spécifique.
     */
    public function show(int $id): JsonResponse
    {
        $property = $this->repo->findOrFail($id);
        $this->authorize('view', $property);

        return response()->json([
            'success' => true,
            'message' => 'Détails de la propriété récupérés avec succès.',
            'data' => new PropertyResource($property)
        ], 200);
    }

    /**
     * Mise à jour d’une propriété existante.
     */
    public function update(UpdatePropertyRequest $request, int $id): JsonResponse
    {
        $property = $this->repo->findOrFail($id);

        // Vérification d’autorisation via Policy
        try {
            $this->authorize('update', $property);
        } catch (AuthorizationException $e) {
            throw new AuthorizationException('property.update');
        }

        // Encapsulation des données dans un DTO d’update
        $dto = new UpdatePropertyDTO(
            type: $request->input('type'),
            nbr_piece: $request->input('nbr_piece'),
            surface: $request->input('surface'),
            price: $request->input('price'),
            city: $request->input('city'),
            description: $request->input('description'),
            status: $request->input('status'),
            published: $request->input('published'),
            images: $request->file('images') ?? []
        );

        $property = $this->service->updateFromDTO($property, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Propriété mise à jour avec succès.',
            'data' => new PropertyResource($property)
        ], 200);
    }

    /**
     * Suppression d’une propriété.
     */
    public function destroy(int $id): JsonResponse
    {
        $property = $this->repo->findOrFail($id);

        // Vérification d’autorisation avant suppression
        try {
            $this->authorize('delete', $property);
        } catch (AuthorizationException $e) {
            throw new AuthorizationException('property.delete');
        }

        $this->service->delete($property);

        return response()->json([
            'success' => true,
            'message' => 'Propriété supprimée avec succès.'
        ], 200);
    }
}
