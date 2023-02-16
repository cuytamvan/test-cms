<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function columns(): array
    {
        $arr = $this->fillable;
        $arr[] = 'id';

        return $arr;
    }
}
