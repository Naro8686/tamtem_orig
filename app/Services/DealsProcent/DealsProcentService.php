<?php

namespace App\Services\DealsProcent;

use App\Models\DealsProcent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class DealsProcentService
{
  
    /**
     * Проверка, сучествует ли колонка со значением
    * @param Request $request
    * @return Collection
    */
    public function isRowValueExists($row, $val) {
        return DealsProcent::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return DealsProcent
     */
    public function getRowValue($row, $val)
    {
        /** @var DealsProcent $dealsProcent */
        $dealsProcent = DealsProcent::where($row, '=', $val)->first();
        return $dealsProcent;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $query = DealsProcent::query();


        $query->when(true === $request->has('budget_from'), function (Builder $query) use ($request) {
            $query->where('budget_from', '=', $request->input('budget_from'));
        });
    
        $query->when(true === $request->has('budget_to'), function (Builder $query) use ($request) {
            $query->where('budget_to', '=', $request->input('budget_to'));
        });
    
        $query->when(true === $request->has('procent'), function (Builder $query) use ($request) {
            $query->where('procent', '=', $request->input('procent'));
        });


        return $query->select([ 'id',  
                                'budget_from', 
                                'budget_to', 
                                'procent', 
                                'created_at', 
                                'updated_at'])->paginate(15);
    }

    /**
     * @param int $id
     * @return DealsProcent
     */
    public function get(int $id)
    {
         
        /** @var DealsProcent $dealsProcent */
        $dealsProcent = DealsProcent::where('id', '=', $id);

        $dealsProcent = $dealsProcent->first();

        if (null === $dealsProcent) {
            $dealsProcent = "Запись {$id} не найдена или не доступна для вас";
        }

        return $dealsProcent;
    }

    /**
     * @param array $data
     * @return DealsProcent
     */
    public function create(array $data)
    {

        $dealsProcent = new DealsProcent();
           
        $dealsProcent->budget_from       = $data['budget_from'];
        $dealsProcent->budget_to         = $data['budget_to'];
        $dealsProcent->procent           = $data['procent'];

        $dealsProcent->save();

        return $dealsProcent;
    }

    /**
     * @param int $id
     * @param array $data
     * @return DealsProcent
     */
    public function update(int $id, array $data)
    {
        $dealsProcent = $this->get($id);

        if($dealsProcent instanceof DealsProcent){

            if(isset($data['budget_from'])) $dealsProcent->budget_from = $data['budget_from'];
            if(isset($data['budget_to'])) $dealsProcent->budget_to = $data['budget_to'];
            if(isset($data['procent'])) $dealsProcent->procent = $data['procent'];

            $dealsProcent->save();
        }

        return $dealsProcent;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $dealsProcent = $this->get($id);

        if($dealsProcent instanceof DealsProcent){
            $dealsProcent->delete();
        }
        return $dealsProcent;
    }
}
