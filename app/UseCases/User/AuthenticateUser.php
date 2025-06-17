<?php

namespace App\UseCases\User;

use Illuminate\Support\Facades\Auth;
use App\UseCases\BaseUseCase;
use App\Exceptions\InternalErrorException;

class AuthenticateUser extends BaseUseCase
{
    /**
     * Email do usuário
     *
     * @var string
     */
    protected string $email;

    /**
     * Senha do usuário
     *
     * @var string
     */
    protected string $password;

    /**
     * ID do usuário
     *
     * @var string
     */
    protected string $userId;

    public function __construct(string $email, string $password)
    {
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * Tenta autenticar o usuário
     *
     * @throws InternalErrorException
     * @return string
     */
    public function handle(): string
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            throw new InternalErrorException(
                'Usuário ou senha inválidos',
                0
            );
        }

        $this->userId = (string) Auth::id();
        return $this->userId;
    }
}
