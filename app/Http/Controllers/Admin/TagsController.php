<?php

namespace App\Http\Controllers\Admin;


use App\Traits\ApiControllerTrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Tag\TagStoreRequest;
use App\Http\Requests\Admin\Tag\TagUpdateRequest;
use App\Services\Tag\TagService;
use App\Http\Resources\Tag\TagResource;

use App\Http\Controllers\Client\Api\v1\Organization\TagController as UserTagController;

use App\Models\Org\Tag;

use Exception;


class TagsController extends Controller
{
	use ApiControllerTrait;

   /**
    * @var TagService
    */
    private $tagsService;

    public function __construct()
    {
        $this->tagsService = new TagService();
	}
	
    /**
    * @param Request $request
    * @return TagCollection
    */
    public function index(Request $request)
    { 
        $tagsService = $this->tagsService->index($request);
    
        return $this->successResponse($tagsService);
    }
    
    /**
    * @param int $id
    * @return TagResource
    * @throws Exception
    */
    public function get(int $id)
    {
        $tagsService = $this->tagsService->get($id);

        if($tagsService instanceof Tag){
            return $this->successResponse(new TagResource($tagsService));
        }
        else{
            return $this->errorResponse($tagsService);
        }
    }

    /**
    * @param TagStoreRequest $request
    * @return TagResource
    * @throws Exception
    */
    public function create(TagStoreRequest $request)
    { 
        // нормализуем имя 
        $tag = UserTagController::getFirstWordsBeforeTheComma($request->name);

        $tagsService = null;

        // если есть такой тэг, сразу вернум его 
        if($tagsService = $this->tagsService->getRowValue('name',  $tag)){

        }
        else{ // иначе создадим тэг и вернем его модель
            $tagsService = $this->tagsService->create(['name' =>  $tag]);
        }

        if($tagsService instanceof Tag){
            return $this->successResponse(new TagResource($tagsService));
        }
        else{
            return $this->errorResponse($tagsService);
        }
    }

    /**
    * @param string $name
    * @return Tag
    * @throws Exception
    */
    public function localCreate($name)
    { 
        // нормализуем имя 
        $tag = UserTagController::getFirstWordsBeforeTheComma($name);

        $tagObj = null;

        // если есть такой тэг, сразу вернум его 
        if($tagObj = $this->tagsService->getRowValue('name',  $tag)){

        }
        else{ // иначе создадим тэг и вернем его модель
            $tagObj = $this->tagsService->create(['name' =>  $tag]);
        }

        return $tagObj;
    }

    /**
    * @param int $id
    * @param TagUpdateRequest $request
    * @return TagResource
    * @throws Exception
    */
    public function update(int $id, TagStoreRequest $request)
    {
        // нормализуем имя 
        $tag = UserTagController::getFirstWordsBeforeTheComma($request->name);

        $tagsService = null;
      
        // если есть такой тэг, сразу вернем его 
        if($tagsService = $this->tagsService->getRowValue('name',  $tag)){
      
        }
        else{ // иначе создадим тэг и вернем его Id
            $tagsService = $this->tagsService->update($id, ['name' =>  $tag]);
        }
      
        if($tagsService instanceof Tag){
            return $this->successResponse(new TagResource($tagsService));
        }
        else{
            return $this->errorResponse($tagsService);
        }
    }

    /**
    * @param int $id
    * @return JsonResponse
    * @throws Exception
    */
    public function delete(int $id)
    {
        $tagsService =  $this->tagsService->delete($id);

        if($tagsService instanceof Tag){
            return $this->successResponse(['id' => $tagsService->id]);
        }
        else{
            return $this->errorResponse($tagsService);
        }
    }

    /**
     * Проверяет строку на теги, которые есть и в БД, которых нет в БД - возвращает массив ['exist' => [], 'not_exist' => [] ]
     *
     * @param string $string
     * @return array
     */
    public function getArrayExistAndNotExistTagsInDbFromString($string = '')
    {
        // строку по запятой
        $arTags = explode(',',  $string); 
        $arTagsExistInDb = []; // гэги есть в бд
        $arTagsNotExistInDb = []; // тэгов нет в БД

        foreach($arTags as $tag){
            // проверяем, есть ли тэг в БД
            $tag = trim($tag);
            if ($tag === ''){
                continue;
            }
            if ($this->tagsService->getRowValue('name', $tag)) { // если тэг есть в БД
                $arTagsExistInDb[] = $tag;
            } else {
                $arTagsNotExistInDb[] = $tag;
            }
        }

       return  ['exist' => $arTagsExistInDb, 'not_exist' => $arTagsNotExistInDb ];
    }

    /**
     * Создает тэги из строки и возвращает массив их Id
     *
     * @param string $string
     * @return void
     */
    public function localCreateTagsFromString($string = '')
    { 
        // строку по запятой
        $arTags = explode(',',  $string); 
        $arTagsIds = []; // id тэгов, созданные из строки
  
        foreach($arTags as $tag){
            // проверяем, есть ли тэг в БД
            $tag = trim($tag);
            if ($tag === ''){
                continue;
            }

            $tag = UserTagController::getFirstWordsBeforeTheComma($tag);
            
            $tagObj = null;
            // если есть такой тэг, сразу вернум его 
            if ($tagObj = $this->tagsService->getRowValue('name',  $tag)){

            } else { // иначе создадим тэг и вернем его модель
                // нормализуем имя 
                $tagObj = $this->tagsService->create(['name' =>  $tag]);
            }

            $arTagsIds[] = $tagObj->id;
        }
        
        return $arTagsIds;
    }
}
