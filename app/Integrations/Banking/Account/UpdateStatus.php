<?php

namespace App\Integrations\Banking\Account;

use App\Integrations\Banking\Gateway;
use App\Repositories\Account\FindByUser;
use App\Exceptions\InternalErrorException;

class UpdateStatus extends Gateway
{
    /**
     * Id do usuário
     *
     * @var string
     */
    protected string $userId;

    /**
     * Id externo da conta
     *
     * @var string
     */
    protected string $externalId;

    /**
     * Novo Status para ser modificado
     *
     * @var string
     */
    protected string $newStatus;

    public function __construct(string $userId, string $newStatus)
    {
        $this->userId    = $userId;
        $this->newStatus = $newStatus;
    }

    /**
     * Busca os dados de conta
     *
     * @return void
     */
    protected function findAccountData(): void
    {
        $account = (new FindByUser($this->userId))->handle();

        if (is_null($account)) {
            throw new InternalErrorException(
                'ACCOUNT_NOT_FOUND',
                161001001
            );
        }

        $this->externalId = $account['external_id'];
    }

    /**
     * Constroi a url da request
     *
     * @return string
     */
    protected function requestUrl(): string
    {
        return "accounts/$this->externalId/status/$this->newStatus";
    }

    /**
     * Modifica o status de uma conta
     *
     * @return array
     */
    public function handle(): array
    {
        $this->findAccountData();
        $url = $this->requestUrl();

        /**
         * mock de retorno da API para que o fluxo de atualização de status funcione
         */

        return [
            'status' => 'success',
            'data' => [
                'status' => 'active',
            ]
        ];

        // $request = $this->sendRequest(
        //     method: 'put',
        //     url: $url,
        //     action: 'UPDATE_STATUS_ACCOUNT',
        //     params: []
        // );

        // return $this->formatDetailsResponse($request);
    }
}
