<?php

namespace App\Repositories;

use Cuytamvan\BasePattern\Repository\CoreRepository;

use App\Models\User;

class UserRepositoryEloquent extends CoreRepository implements UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByEmail(string $email)
    {
        $data = $this->model->where('email', $email)->first();

        return $data;
    }
}
