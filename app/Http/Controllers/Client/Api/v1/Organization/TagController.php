<?php

namespace App\Http\Controllers\Client\Api\v1\Organization;

use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Org\Tag;
use App\Services\Tag\TagService;

use Validator;
use Auth;

class TagController extends Controller
{
    use ApiControllerTrait;

    public $tagService = null;

    public function __construct()
    {
        $this->tagService = new TagService();
    }
   
    /**
     * Get first numberOfWords words before the first comma separator
     *
     * @param  mixed $string
     * @param  mixed $numberOfWords
     *
     * @return string
     */
    public static function getFirstWordsBeforeTheComma($string, $numberOfWords = 2)
    {
        
        // строку до запятой
        $string = explode(',',  $string)[0]; 

        // разделить строку в массив, по пробелам, не зависимо от-кол-ва пробелов
        $string = preg_split('/[\s]+/u',  $string, null, PREG_SPLIT_NO_EMPTY);
    
        $string = array_splice($string, 0, $numberOfWords); // оставим из массива numberOfWords первых элементов
            
        return implode(" ", $string);
    }


    /**
     * Create tag, without duplicate
     *
     * @param  mixed $string
     *
     * @return void
     */
    public function storeTag($string)
    { 

        // если есть такой тэг, сразу вернум его Id
        if($tag = $this->tagService->getRowValue('name', $string)){

            return $tag->id;
        }
        else{ // иначе создадим тэг и вернем его Id

            $tag = $this->tagService->create(['name' => $string]);

            if($tag instanceof Tag){
                return $tag->id;
            }
            else{
                return null;
            }
        }
    }
}
