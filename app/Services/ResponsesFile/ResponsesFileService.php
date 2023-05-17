<?php

namespace App\Services\ResponsesFile;

use App\Models\ResponsesFile as Model;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Traits\ModelFileUpload;
use App\Traits\ApiControllerTrait;

class ResponsesFileService
{
  
    use ModelFileUpload;
    use ApiControllerTrait;

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
        /** @var Model $row */
        $row = Model::where($row, '=', $val)->first();
        return $row;
    }

    /**
     * @return Collection
     */
    public function index(Request $request)
    {

        $query = Model::query();

        $query->when(true === $request->has('response_id'), function (Builder $query) use ($request) {
            $query->where('response_id', '=', $request->input('response_id'));
        });
    
        $query->when(true === $request->has('user_id'), function (Builder $query) use ($request) {
            $query->where('user_id', '=', $request->input('user_id'));
        });
    
        return $query->select([ 'id',  
                                'response_id',
                                'user_id',
                                'file_name',
                                'file_full_path',
                                'created_at', 
                                'updated_at'])->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function get(int $id)
    {
         
        /** @var Model $row */
        $row = Model::where('id', '=', $id);

        $row = $row->first();

        if (null === $row) {
            $row = "Запись {$id} не найдена или не доступна для вас";
        }

        return $row;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {

        $file = $data['file'];
        $userId = $data['user_id'];
        $responseId = $data['response_id'];

        $row = new Model();

        $arrToImportTable = $this->uploadFileToStore($file, 'users/' . $userId . '/responses/' . $responseId . '/files');
           
        if(isset( $arrToImportTable['file_name']) and isset( $arrToImportTable['file_full_path'])){
            $row->response_id = $responseId;
            $row->user_id = $userId;
            $row->file_name        = $arrToImportTable['file_name'];
            $row->file_full_path   = $arrToImportTable['file_full_path'];
            $row->save();
        }
        else {
            $row = "Файл {$file->getClientOriginalName()} не удалось загрузить на сервер. Обратитесь к службе поддержки сайта.";
        }

        return $row;
    }

    // /**
    //  * @param int $id
    //  * @param array $data
    //  * @return Model
    //  */
    // public function update(int $id, array $data)
    // {
    //     $dealsFile = $this->get($id);

    //     if($dealsFile instanceof Model){

    //         if(isset($data['budget_from'])) $dealsFile->budget_from = $data['budget_from'];
    //         if(isset($data['budget_to'])) $dealsFile->budget_to = $data['budget_to'];
    //         if(isset($data['procent'])) $dealsFile->procent = $data['procent'];

    //         $dealsFile->save();
    //     }

    //     return $dealsFile;
    // }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id)
    {
        $row = $this->get($id);

        if($row instanceof Model){

            $path = $row->file_full_path;
            $row->delete();

            if(!$this->deleteFileFromStoreOnPath($path)){
                
                return "Ошибка удаления файла {$path}";
            }
        }
        return $row;
    }

}
