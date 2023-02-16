<?php

namespace App\Repositories;

use Cuytamvan\BasePattern\Repository\CoreRepository;

use App\Models\ArticleCategory;

class ArticleCategoryRepositoryEloquent extends CoreRepository implements ArticleCategoryRepository
{
    protected $model;

    public function __construct(ArticleCategory $model)
    {
        $this->model = $model;
    }
}
