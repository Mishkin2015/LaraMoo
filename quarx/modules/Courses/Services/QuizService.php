<?php

namespace Quarx\Modules\Courses\Services;

use Config;
use Quarx\Modules\Courses\Models\Quiz;

class QuizService
{
    public function __construct(Quiz $quiz)
    {
        $this->model = $quiz;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginated()
    {
        $model = $this->model;

        if (isset(request()->dir) && isset(request()->field)) {
            $model = $model->orderBy(request()->field, request()->dir);
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model->paginate(config('quarx.pagination', 25));
    }

    public function search($payload)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$payload.'%');

        $columns = Schema::getColumnListing('courses');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload.'%');
        };

        return $query->paginate(Config::get('quarx.pagination', 25));
    }

    public function create($payload)
    {
        return $this->model->create($payload);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $payload)
    {
        return $this->find($id)->update($payload);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

}