<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ModelFileUpload;
use App\Traits\ApiControllerTrait;

class MessagesFiles extends Model
{
    
    use ModelFileUpload;
    use ApiControllerTrait;
    
    protected $table = 'messages_files';

    protected $fillable = [
        'message_id',
        'dialog_id',
        'deal_id',
        'user_id',
        'file_name',
        'file_full_path',
    ];
    static protected $sortable = ['id', 'message_id', 'dialog_id', 'deal_id', 'user_id', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(\App\Models\Message::class, 'message_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dialog()
    {
        return $this->belongsTo(\App\Models\Dialog::class, 'dialog_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDeal::class, 'deal_id'); // обратное отношение , местный внешний ключ  deal_id
    }

    /**
     * uploadFileAndSaveToDB
     *
     * @param  mixed $file
     * @param  mixed $messageId
     * @param  mixed $dialogId
     * @param  mixed $dealId
     * @param  mixed $userId
     *
     * @return void
     */
    public function uploadFileAndSaveToDB($file, $messageId, $dialogId, $dealId, $userId)
    {

        try{

            $arrToImportTable = $this->uploadFileToStore($file, 'dialogs/' . $dialogId . '/deals/' . $dealId . '/files');

            if(isset( $arrToImportTable['file_name']) and isset( $arrToImportTable['file_full_path'])){
                $this->message_id = $messageId;
                $this->dialog_id  = $dialogId;
                $this->deal_id = $dealId;
                $this->user_id = $userId;
                $this->file_name        = $arrToImportTable['file_name'];
                $this->file_full_path   = $arrToImportTable['file_full_path'];
                $this->save();
                return true;
            }
    
            return false;
        }
        catch(\Exception $e){
            // return $this->errorResponse($e->getMessage());
            false;
		}
      
    }

    // public function deleteImageDeal($id)
    // {
    //     try{

    //         $path = $this->file_full_path;
    //         if(!$this->deleteImageFromStore($path)){
    //             return false;
    //         }

    //         return true;
    //     }
    //     catch(\Exception $e){
    //         //return $this->errorResponse($e->getMessage());
    //         false;
	// 	}

    // }
}
