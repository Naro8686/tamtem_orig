<?php

namespace App\Services\OrganizationDealQuestion;

use App\Models\Org\OrganizationDealQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class OrganizationDealQuestionService
{
  
    /**
     * Поиск первой записи по названию колонки и значению
    * @param Request $request
    * @return Collection
    */
    public function getRowIfRowValExists($vals) {
        $query = OrganizationDealQuestion::query();
        foreach ($vals as $col_name => $value) {
            $query->where($col_name, '=', $value);
        }
        return $query->first();
    }

    /**
     * Проверка, сучествует ли колонка со значением
    * @param Request $request
    * @return Collection
    */
    public function isRowValueExists($row, $val) {
        return OrganizationDealQuestion::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return OrganizationDealQuestion
     */
    public function getRowValue($row, $val)
    {
        /** @var OrganizationDealQuestion $dealsQuestion */
        $dealsQuestion = OrganizationDealQuestion::where($row, '=', $val)->first();
        return $dealsQuestion;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $query = OrganizationDealQuestion::query();

        // $query->when(true === $request->has('name'), function (Builder $query) use ($request) {
        //     $query->where('name', '=', $request->input('name'));
        // });
    
        // $query->when(true === $request->has('slug'), function (Builder $query) use ($request) {
        //     $query->where('slug', '=', $request->input('slug'));
        // });

        return $query->select([ 'id',  
                                'deal_id', 
                                'question_id', 
                                'question', 
                                'created_at', 
                                'updated_at'])->paginate(15);
    }

    /**
     * @param int $id
     * @return OrganizationDealQuestion
     */
    public function get(int $id)
    {
         
        /** @var OrganizationDealQuestion $dealsQuestion */
        $dealsQuestion = OrganizationDealQuestion::where('id', '=', $id);

        $dealsQuestion = $dealsQuestion->first();

        if (null === $dealsQuestion) {
            $dealsQuestion = "Запись {$id} не найдена или не доступна для вас";
        }

        return $dealsQuestion;
    }

    /**
     * @param array $data
     * @return OrganizationDealQuestion
     */
    public function create(array $data)
    {

        $dealsQuestion = new OrganizationDealQuestion();
           
        $dealsQuestion->deal_id       = $data['deal_id'];
        $dealsQuestion->question_id   = $data['question_id'];
        $dealsQuestion->question      = $data['question'];

        $dealsQuestion->save();

        return $dealsQuestion;
    }

    /**
     * @param int $id
     * @param array $data
     * @return OrganizationDealQuestion
     */
    public function update(int $id, array $data)
    {
        $dealsQuestion = $this->get($id);

        if($dealsQuestion instanceof OrganizationDealQuestion){

            if(isset($data['deal_id']))     $dealsQuestion->deal_id = $data['deal_id'];
            if(isset($data['question_id'])) $dealsQuestion->question_id = $data['question_id'];
            if(isset($data['question']))    $dealsQuestion->question = $data['question'];

            $dealsQuestion->save();
        }

        return $dealsQuestion;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $dealsQuestion = $this->get($id);

        if($dealsQuestion instanceof OrganizationDealQuestion){
            $dealsQuestion->delete();
        }
        return $dealsQuestion;
    }
}
