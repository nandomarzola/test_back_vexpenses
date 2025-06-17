<?php

namespace App\UseCases\Card;

use Throwable;
use App\UseCases\BaseUseCase;
use App\Integrations\Banking\Card\Find;

class Show extends BaseUseCase
{
    /**
     * Id do usuário
     *
     * @var string
     */
    protected string $userId;

    /**
     * Dados do cartão
     *
     * @var array
     */
    protected array $card;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Encontra o cartão
     *
     * @return void
     */
    protected function find(): void
    {
        $this->card = (new Find($this->userId))->handle();
    }

    /**
     * Retorna cartão
     *
     * @return array
     */
    public function handle(): array
    {
        try {
            $this->find();
        } catch (Throwable $th) {
            $this->defaultErrorHandling(
                $th,
                [
                    'userId' => $this->userId,
                ]
            );
        }

        return $this->card;
    }
}
