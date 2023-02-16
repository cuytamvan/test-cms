<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
    ];

    public function columns(): array
    {
        $arr = $this->fillable;
        $arr[] = 'id';

        return $arr;
    }
}
