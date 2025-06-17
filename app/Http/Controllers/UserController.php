<?php

namespace App\Http\Controllers;

use App\UseCases\User\Show;
use App\UseCases\User\Index;
use App\UseCases\User\Create;
use App\UseCases\User\Update;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\DefaultResponse;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\ShowResource;
use App\UseCases\Params\User\CreateParams;
use App\UseCases\Params\User\UpdateParams;
use App\Http\Resources\User\CreateResource;
use App\Http\Resources\User\UpdateResource;
use App\Http\Resources\User\IndexCollectionResource;

class UserController extends Controller
{

    /**
     * Endpoint de listagem de usuários
     *
     * GET api/users
     *
     * @return JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $response = (new Index(
            Auth::user()->company_id,
            $request->name,
            $request->email,
            $request->status
        ))->handle();

        return $this->response(
            new DefaultResponse(
                new IndexCollectionResource($response)
            )
        );
    }

    /**
     * Endpoint de dados de usuário
     *
     * GET api/users/{id}
     *
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $response = (new Show($id, Auth::user()->company_id))->handle();

        return $this->response(
            new DefaultResponse(
                new ShowResource($response)
            )
        );
    }

    /**
     * Endpoint de criação de usuário
     *
     * POST api/users
     *
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        $params = new CreateParams(
            Auth::user()->company_id,
            $request->name,
            $request->document_number,
            $request->email,
            $request->password,
            $request->type
        );

        $response = (new Create($params))->handle();

        return $this->response(
            new DefaultResponse(
                new CreateResource($response)
            )
        );
    }

    /**
     * Endpoint de modificação de usuário
     *
     * PATCH api/users/{id}
     *
     * @return JsonResponse
     */
    public function update(string $id, UpdateRequest $request): JsonResponse
    {
        $params = new UpdateParams(
            $id,
            Auth::user()->company_id,
            $request->name,
            $request->email,
            $request->password,
            $request->type
        );

        $response = (new Update($params))->handle();

        return $this->response(
            new DefaultResponse(
                new UpdateResource($response)
            )
        );
    }
}
