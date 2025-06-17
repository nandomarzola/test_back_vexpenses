<?php

namespace App\UseCases\Params\Company;

use App\UseCases\Params\BaseParams;

class UpdateParams extends BaseParams
{
    /**
     * Id
     *
     * @var string
     */
    protected string $id;

    /**
     * Nome
     *
     * @var string|null
     */
    protected ?string $name;

    public function __construct(
        string $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }
}
