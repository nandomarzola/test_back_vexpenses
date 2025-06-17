<?php

namespace App\UseCases\Company;

use Throwable;
use App\UseCases\BaseUseCase;
use App\UseCases\Params\Company\UpdateParams;
use App\Domains\Company\Update as UpdateDomain;
use App\Repositories\Company\Update as UpdateRepository;

class Update extends BaseUseCase
{
    /**
     * @var UpdateParams
     */
    protected UpdateParams $params;

    /**
     * Empresa
     *
     * @var array
     */
    protected array $company;

    public function __construct(
        UpdateParams $params
    ) {
        $this->params = $params;
    }

    /**
     * Valida empresa
     *
     * @return UpdateDomain
     */
    protected function validateCompany(): UpdateDomain
    {
        return (new UpdateDomain(
            $this->params->id,
            $this->params->name,
        ))->handle();
    }

    /**
     * Atualiza repositório
     *
     * @param UpdateDomain $domain
     *
     * @return void
     */
    protected function updateCompany(UpdateDomain $domain): void
    {
        $this->company = (new UpdateRepository($domain))->handle();
    }

    /**
     * Modifica a empresa
     *
     * @return array|null
     */
    public function handle()
    {
        try {
            $companyDomain = $this->validateCompany();
            $this->updateCompany($companyDomain);
        } catch (Throwable $th) {
            $this->defaultErrorHandling(
                $th,
                [
                    'params' => $this->params->toArray(),
                ]
            );
        }

        return $this->company;
    }
}
