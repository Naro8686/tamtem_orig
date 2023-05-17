<?php

namespace App\Services\DealsFile;

use App\Models\DealsFile;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Traits\ModelFileUpload;
use App\Traits\ApiControllerTrait;

class DealsFileService
{
  
    use ModelFileUpload;
    use ApiControllerTrait;

    /**
     * Проверка, сучествует ли колонка со значением
    * @param Request $request
    * @return Collection
    */
    public function isRowValueExists($row, $val) {
        return DealsFile::where($row, $val)->exists();
    }

    /**
     * @param int $id
     * @return DealsFile
     */
    public function getRowValue($row, $val)
    {
        /** @var DealsFile $dealsFile */
        $dealsFile = DealsFile::where($row, '=', $val)->first();
        return $dealsFile;
    }

    /**
     * @return Collection
     */
    public function index(Request $request)
    {

        $query = DealsFile::query();

        $query->when(true === $request->has('deal_id'), function (Builder $query) use ($request) {
            $query->where('deal_id', '=', $request->input('deal_id'));
        });
    
        $query->when(true === $request->has('user_id'), function (Builder $query) use ($request) {
            $query->where('user_id', '=', $request->input('user_id'));
        });
    
        return $query->select([ 'id',  
                                'deal_id',
                                'user_id',
                                'file_name',
                                'file_full_path',
                                'created_at', 
                                'updated_at'])->get();
    }

    /**
     * @param int $id
     * @return DealsFile
     */
    public function get(int $id)
    {
         
        /** @var DealsFile $dealsFile */
        $dealsFile = DealsFile::where('id', '=', $id);

        $dealsFile = $dealsFile->first();

        if (null === $dealsFile) {
            $dealsFile = "Запись {$id} не найдена или не доступна для вас";
        }

        return $dealsFile;
    }

    /**
     * @param array $data
     * @return DealsFile
     */
    public function create(array $data)
    {

        $file = $data['file'];
        $userId = $data['user_id'];
        $dealId = $data['deal_id'];

        $dealsFile = new DealsFile();

        $arrToImportTable = $this->uploadFileToStore($file, 'users/' . $userId . '/deals/' . $dealId . '/files');
           
        if(isset( $arrToImportTable['file_name']) and isset( $arrToImportTable['file_full_path'])){
            $dealsFile->deal_id = $dealId;
            $dealsFile->user_id = $userId;
            $dealsFile->file_name        = $arrToImportTable['file_name'];
            $dealsFile->file_full_path   = $arrToImportTable['file_full_path'];
            $dealsFile->save();
        }
        else {
            $dealsFile = "Файл {$file->getClientOriginalName()} не удалось загрузить на сервер. Обратитесь к службе поддержки сайта.";
        }

        return $dealsFile;
    }

    // /**
    //  * @param int $id
    //  * @param array $data
    //  * @return DealsFile
    //  */
    // public function update(int $id, array $data)
    // {
    //     $dealsFile = $this->get($id);

    //     if($dealsFile instanceof DealsFile){

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
        $dealsFile = $this->get($id);

        if($dealsFile instanceof DealsFile){

            $path = $dealsFile->file_full_path;
            $dealsFile->delete();

            if(!$this->deleteFileFromStoreOnPath($path)){
                
                return "Ошибка удаления файла {$path}";
            }
        }
        return $dealsFile;
    }

}
