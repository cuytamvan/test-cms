<?php

namespace App\Repositories;

use Cuytamvan\BasePattern\Repository\CoreRepository;

use App\Models\Role;

class RoleRepositoryEloquent extends CoreRepository implements RoleRepository
{
    protected $model;

    public function __construct(Role $model)
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
                $data->update($input);
            } else $data = $this->model->create($input);

            if (!$id) $data->permissions()->attach($input['permissions']);
            else $data->permissions()->sync($input['permissions']);

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

    public function delete(...$id)
    {
        if ($this->user() && $this->withActivities) {
            $activity = activity($this->moduleName ?? '')
                ->setProperties(['color' => '#EF4444', 'type' => 'delete'])
                ->setCauser($this->user());
        }

        $query = $this->model->whereIn('id', $id);
        if ($this->user() && $this->withActivities) {
            foreach ($query->get() as $r) {
                $activity->addSubject($r);
                $r->permissions()->delete();
            }
            $activity->save();
        }
        if (in_array('deleted_by', $this->model->columns())) $query->update(['deleted_by' => $this->uid()]);
        return $query->delete();
    }
}
