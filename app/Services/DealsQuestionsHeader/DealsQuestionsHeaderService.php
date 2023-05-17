<?php

namespace App\Services\DealsQuestionsHeader;

use App\Models\DealQuestionHeader;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class DealsQuestionsHeaderService
{
  
    /**
     * Проверка, сучествует ли колонка со значением
    * @param Request $request
    * @return Collection
    */
    public function isRowValueExists($row, $val) {
        return DealQuestionHeader::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return DealQuestionHeader
     */
    public function getRowValue($row, $val)
    {
        /** @var DealQuestionHeader $dealsQuestionsHeader */
        $dealsQuestionsHeader = DealQuestionHeader::where($row, '=', $val)->first();
        return $dealsQuestionsHeader;
    }

    /**
     * @param string $ids
     * @return DealQuestionHeader
     */
    public static function getArrFromIdsArray($ids)
    {
        /** @var DealQuestionHeader $dealsQuestionsHeader */
        $dealsQuestionsHeader = DealQuestionHeader::whereIn('id', $ids)->get();
        return $dealsQuestionsHeader;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $query = DealQuestionHeader::query();

        $query->when(true === $request->has('name'), function (Builder $query) use ($request) {
            $query->where('name', '=', $request->input('name'));
        });
    
        $query->when(true === $request->has('slug'), function (Builder $query) use ($request) {
            $query->where('slug', '=', $request->input('slug'));
        });

        return $query->select([ 'id',  
                                'name', 
                                'slug', 
                                'created_at', 
                                'updated_at'])->paginate(15);
    }

    /**
     * @param int $id
     * @return DealQuestionHeader
     */
    public function get(int $id)
    {
         
        /** @var DealQuestionHeader $dealsQuestionsHeader */
        $dealsQuestionsHeader = DealQuestionHeader::where('id', '=', $id);

        $dealsQuestionsHeader = $dealsQuestionsHeader->first();

        if (null === $dealsQuestionsHeader) {
            $dealsQuestionsHeader = "Запись {$id} не найдена или не доступна для вас";
        }

        return $dealsQuestionsHeader;
    }

    /**
     * @param array $data
     * @return DealQuestionHeader
     */
    public function create(array $data)
    {

        $dealsQuestionsHeader = new DealQuestionHeader();
           
        $dealsQuestionsHeader->name       = $data['name'];
        $dealsQuestionsHeader->slug       = $data['slug'];

        $dealsQuestionsHeader->save();

        return $dealsQuestionsHeader;
    }

    /**
     * @param int $id
     * @param array $data
     * @return DealQuestionHeader
     */
    public function update(int $id, array $data)
    {
        $dealsQuestionsHeader = $this->get($id);

        if($dealsQuestionsHeader instanceof DealQuestionHeader){

            if(isset($data['name'])) $dealsQuestionsHeader->name = $data['name'];
            if(isset($data['slug'])) $dealsQuestionsHeader->slug = $data['slug'];

            $dealsQuestionsHeader->save();
        }

        return $dealsQuestionsHeader;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $dealsQuestionsHeader = $this->get($id);

        if($dealsQuestionsHeader instanceof DealQuestionHeader){
            $dealsQuestionsHeader->delete();
        }
        return $dealsQuestionsHeader;
    }
}
