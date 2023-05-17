<?php

namespace App\Services\Tag;

use App\Models\Org\Tag as Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
  
   /**
     * Проверка, сучествует ли колонка со значением
    * @param Request $request
    * @return Collection
    */
    public function isRowValueExists($row, $val) {
        return Model::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getRowValue($row, $val)
    {
        /** @var Model $model */
        $model = Model::where($row, '=', $val)->first();
        return $model;
    }
    
    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    { //dd($request);
        $query = Model::query()->select('id', 'name');
        
        $query->when(true === $request->has('query'), function (Builder $query) use ($request) {
            $query->where('name', 'like', '%'.$request->input('query').'%')->orderBy('name', 'ASC');
        });
        
        return $query->paginate(15);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function get(int $id)
    {
         
        /** @var Model $model */
        $model = Model::where('id', '=', $id);

        $model = $model->first();

        if (null === $model) {
            $model = "Запись {$id} не найдена или не доступна для вас";
        }

        return $model;
    }

     /**
     * @param int $id
     * @return Model
     */
    public function getSoftDeleted(int $id)
    {
         
        /** @var Model $model */
        $model = Model::withTrashed()->where('id', $id)->first();

        if (null === $model) {
            $model = "Запись {$id} не найдена среди удаленных или не доступна для вас";
        }

        return $model;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return Model::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data)
    {
        $model = $this->get($id);

        if($model instanceof Model){
            $model->update($data);
        }

        return $model;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $model = $this->get($id);

        if($model instanceof Model){
            $model->delete();
        }
        return $model;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function restore(int $id)
    {
        $model = $this->getSoftDeleted($id);

        if($model instanceof Model){
            $model->restore();
        }
        return $model;
    }

}
