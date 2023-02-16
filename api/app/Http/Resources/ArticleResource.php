<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArticleResource extends JsonResource
{
    protected $disk = 'public';
    protected $path = 'articles/';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'preview' => strip_tags($this->content),
            'banner_url' => Storage::disk($this->disk)->url($this->path . $this->banner),
            'article_category_id' => $this->article_category_id,
            'article_category' => new ArticleCategoryResource($this->whenLoaded('article_category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
