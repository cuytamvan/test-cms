<?php

namespace App\Repositories;

use Cuytamvan\BasePattern\Repository\CoreRepository;

use App\Models\Permission;

class PermissionRepositoryEloquent extends CoreRepository implements PermissionRepository
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}
