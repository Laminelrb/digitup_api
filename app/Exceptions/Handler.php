<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Gestionnaire central des exceptions pour l'API.
 * Retourne toujours des réponses JSON adaptées aux erreurs courantes.
 */
class Handler extends ExceptionHandler
{
    // Champs à ne jamais afficher en cas d'erreur
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void {}



    /**
     * Préparer les exceptions spécifiques avant le rendu.
     */
    protected function prepareException(Throwable $e): Throwable
    {
        // Les exceptions d'autorisation seront gérées dans render()
        if ($e instanceof AuthorizationException && request()->expectsJson()) {
            return $e;
        }

        return parent::prepareException($e);
    }



    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {


            // Authorization

            if ($exception instanceof AuthorizationException) {
                $message = $this->getAuthorizationMessage($exception);
                return response()->json(['message' => $message], 403);
            }

            if ($exception instanceof AccessDeniedHttpException) {
                return response()->json(['message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
            }


            // Authentication

            if ($exception instanceof AuthenticationException) {
                return response()->json(['message' => 'Non authentifié.'], 401);
            }


            // Modèle non trouvé

            if ($exception instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Ressource non trouvée.'], 404);
            }


            // Route non trouvée

            if ($exception instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Route non trouvée.'], 404);
            }


            // Erreurs de validation

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Erreur de validation.',
                    'errors' => $exception->errors()
                ], 422);
            }


            // Autres exceptions HTTP

            if ($exception instanceof HttpExceptionInterface) {
                return response()->json([
                    'message' => $exception->getMessage() ?: 'Une erreur est survenue.'
                ], $exception->getStatusCode());
            }


            // Erreurs serveur (500)

            return response()->json([
                'message' => env('APP_DEBUG') ? $exception->getMessage() : 'Une erreur est survenue.'
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Message spécifique selon le contexte de l'AuthorizationException
     */
    private function getAuthorizationMessage(AuthorizationException $exception): string
    {
        $context = $exception->getMessage();

        return match ($context) {
            'property.update' => 'Vous n\'êtes pas autorisé à modifier cette propriété.',
            'property.delete' => 'Vous n\'êtes pas autorisé à supprimer cette propriété.',
            default => 'Vous n\'êtes pas autorisé à effectuer cette action.',
        };
    }
}
