<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Card\Register;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Card\CreateResource;
use App\Http\Resources\Card\ShowResource;
use App\Http\Responses\DefaultResponse;
use App\UseCases\Card\Show;

class CardController extends Controller
{
    /**
     * Exibe dados de um cartão
     *
     * GET api/users/{id}/card
     *
     * @return JsonResponse
     */
    public function show(string $userId): JsonResponse
    {
        $response = (new Show($userId))->handle();

        return $this->response(
            new DefaultResponse(
                new ShowResource($response['data'])
            )
        );
    }

    /**
     * Ativa um cartão
     *
     * POST api/users/{id}/card
     *
     * @return JsonResponse
     */
    public function register(string $userId, Request $request): JsonResponse
    {
        $response = (new Register($userId, $request->pin, $request->card_id))->handle();

        return $this->response(
            new DefaultResponse(
                new CreateResource($response['data'])
            )
        );
    }
}
