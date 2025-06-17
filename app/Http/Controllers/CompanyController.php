<?php

namespace App\Http\Controllers;

use App\UseCases\Company\Show;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\DefaultResponse;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\Company\ShowResource;
use App\Http\Resources\Company\UpdateResource;
use App\UseCases\Params\Company\UpdateParams;
use App\UseCases\Company\Update as CompanyUpdate;

class CompanyController extends Controller
{
    /**
     * Endpoint de dados de empresa
     *
     * GET api/company
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $response = (new Show(Auth::user()->company_id))->handle();

        return $this->response(
            new DefaultResponse(
                new ShowResource($response)
            )
        );
    }

    /**
     * Endpoint de modificação de empresa
     *
     * PATCH api/company
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        /**
         * Removendo a logica de buscar o CompanyUpdate diretamente do Repositorie e buscando de seu UseCase
         * respeitando os padrões de arquitetura DDD.
         */
        $params = new UpdateParams(
            Auth::user()->company_id,
            $request->name
        );

        $response = (new CompanyUpdate($params))->handle();

        return $this->response(
            new DefaultResponse(
                new UpdateResource($response)
            )
        );
    }
}
