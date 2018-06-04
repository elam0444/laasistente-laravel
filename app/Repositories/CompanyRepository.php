<?php

namespace App\Repositories;

use App\Models\Company;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories
 */
class CompanyRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Company::class;
    }
}
