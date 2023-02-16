<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'article_category_id',
        'content',
        'banner',
    ];

    public function columns(): array
    {
        $arr = $this->fillable;
        $arr[] = 'id';

        return $arr;
    }

    public function article_category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class);
    }
}
