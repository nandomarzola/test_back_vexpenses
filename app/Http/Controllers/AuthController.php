<?php

namespace App\Http\Controllers;

use App\UseCases\User\Index;
use App\UseCases\User\Login;
use App\UseCases\User\AuthenticateUser;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\UseCases\User\CreateFirstUser;
use App\Http\Responses\DefaultResponse;
use App\Http\Requests\User\IndexRequest;
use App\Http\Resources\User\LoginResource;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\RegisterResource;
use App\UseCases\Params\User\CreateFirstUserParams;
use App\Http\Resources\User\IndexCollectionResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Para primeiro acesso, cria um usuário MANAGER e a empresa
     *
     * POST api/users/register
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $params = new CreateFirstUserParams(
            $request->company_name,
            $request->company_document_number,
            $request->user_name,
            $request->user_document_number,
            $request->email,
            $request->password
        );

        $useCase  = new CreateFirstUser($params);
        $response = $useCase->handle();

        return $this->response(
            new DefaultResponse(
                new RegisterResource(
                    $response
                )
            )
        );
    }

    /**
     * Endpoint de login (autenticação BASIC)
     *
     * POST api/users/login
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {

        /**
         * Nessa parte, o backend está logando passando o Auth::id porém ele só será valido quando o usuário se cadastrar
         * caso o usuário deslogue e tente logar novamente, o endpoint vai retornar um error pois Auth::id não vai existir
         * então o ideal é criar uma verificação para ver se o Auth::id existe, caso não exista, ele verifica se as credenciais estão corretas
         * e se estiverem corretas, ele cria o usuário e loga, caso contrário, retorna um erro de autenticação.
         */

        try {
            // Verifica se o usuário já está autenticado
            $userId = Auth::id();

            // Se não estiver autenticado ele entra no nessa logicca
            if (!$userId) {
                // Se não estiver autenticado, tenta autenticar através das credenciais vericando no case de AuthenticateUser
                $credentials = $request->only('email', 'password');

                $userId = (new AuthenticateUser(
                    $credentials['email'] ?? '',
                    $credentials['password'] ?? ''
                ))->handle();
            }

            //pega o token do usuario
            $response = (new Login($userId))->handle();

            return $this->response(
                new DefaultResponse(
                    new LoginResource(
                        $response
                    )
                )
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'errors' => [
                    ['message' => $e->getMessage()]
                ],
            ], 500);
        }
    }

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
}
