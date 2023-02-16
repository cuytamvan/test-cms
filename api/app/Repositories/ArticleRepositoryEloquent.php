<?php

namespace App\Repositories;

use Cuytamvan\BasePattern\Repository\CoreRepository;

use Illuminate\Support\Str;

use App\Models\Article;

class ArticleRepositoryEloquent extends CoreRepository implements ArticleRepository
{
    protected $model;

    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    public function store($id = null, $input = [])
    {
        try {
            $type = 'create';
            if (!$id && in_array('created_by', $this->model->columns())) $input['created_by'] = $this->uid();
            if ($id && in_array('updated_by', $this->model->columns())) $input['updated_by'] = $this->uid();

            if ($id) {
                $type = 'update';
                $data = $this->findById($id);
                if (!$data) throw new Exception('Not found');
                if ($data->title != $input['title']) $input['slug'] = Str::slug($input['title']);
                $data->update($input);
            } else {
                $input['slug'] = Str::slug($input['title']);
                $data = $this->model->create($input);
            }

            if ($this->user() && $this->withActivities) {
                activity($this->moduleName ?? '')
                    ->setProperties(['color' => '#3B82F6', 'type' => $type])
                    ->setCauser($this->user())
                    ->addSubject($data)
                    ->save();
            }

            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function findBySlug($slug)
    {
        $data = $this->model->where('slug', $slug)->first();

        return $data;
    }
}
