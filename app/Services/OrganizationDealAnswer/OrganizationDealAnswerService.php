<?php

namespace App\Services\OrganizationDealAnswer;

use App\Models\Org\OrganizationDealAnswer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class OrganizationDealAnswerService
{
  
    /**
     * Поиск первой записи по названию колонки и значению
    * @param Request $request
    * @return Collection
    */
    public function getRowIfRowValExists($vals) {
        $query = OrganizationDealAnswer::query();
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
        return OrganizationDealAnswer::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return OrganizationDealAnswer
     */
    public function getRowValue($row, $val)
    {
        /** @var OrganizationDealAnswer $dealsAnswer*/
        $dealsAnswer= OrganizationDealAnswer::where($row, '=', $val)->first();
        return $dealsAnswer;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $query = OrganizationDealAnswer::query();

        // $query->when(true === $request->has('name'), function (Builder $query) use ($request) {
        //     $query->where('name', '=', $request->input('name'));
        // });
    
        // $query->when(true === $request->has('slug'), function (Builder $query) use ($request) {
        //     $query->where('slug', '=', $request->input('slug'));
        // });

        return $query->select([ 'id',  
                                'member_id',
                                'deal_id', 
                                'organization_id', 
                                'question_id', 
                                'is_agree', 
                                'answer', 
                                'created_at', 
                                'updated_at'])->paginate(20);
    }

    /**
     * @param int $id
     * @return OrganizationDealAnswer
     */
    public function get(int $id)
    {
         
        /** @var OrganizationDealAnswer $dealsAnswer*/
        $dealsAnswer= OrganizationDealAnswer::where('id', '=', $id);

        $dealsAnswer= $dealsAnswer->first();

        if (null === $dealsAnswer) {
            $dealsAnswer= "Запись {$id} не найдена или не доступна для вас";
        }

        return $dealsAnswer;
    }

    /**
     * @param array $data
     * @return OrganizationDealAnswer
     */
    public function create(array $data)
    {

        $dealsAnswer= new OrganizationDealAnswer();
           
        $dealsAnswer->member_id         = $data['member_id'];
        $dealsAnswer->deal_id           = $data['deal_id'];
        $dealsAnswer->organization_id   = $data['organization_id'];
        $dealsAnswer->question_id       = $data['question_id'];
        $dealsAnswer->is_agree          = $data['is_agree'];
        if(isset($data['answer']))      $dealsAnswer->answer = $data['answer'];

        $dealsAnswer->save();

        return $dealsAnswer;
    }

    /**
     * @param int $id
     * @param array $data
     * @return OrganizationDealAnswer
     */
    public function update(int $id, array $data)
    {
        $dealsAnswer= $this->get($id);

        if($dealsAnswer instanceof OrganizationDealAnswer){

            if(isset($data['member_id']))     $dealsAnswer->member_id = $data['member_id'];
            if(isset($data['deal_id']))     $dealsAnswer->deal_id = $data['deal_id'];
            if(isset($data['organization_id']))     $dealsAnswer->organization_id = $data['organization_id'];
            if(isset($data['question_id'])) $dealsAnswer->question_id = $data['question_id'];
            if(isset($data['is_agree']))    $dealsAnswer->is_agree = $data['is_agree'];
            if(isset($data['answer']))      $dealsAnswer->answer = $data['answer'];

            $dealsAnswer->save();
        }

        return $dealsAnswer;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $dealsAnswer= $this->get($id);

        if($dealsAnswer instanceof OrganizationDealAnswer){
            $dealsAnswer->delete();
        }
        return $dealsAnswer;
    }
}
