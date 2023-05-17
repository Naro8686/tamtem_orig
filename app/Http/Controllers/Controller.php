<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * getNullIsStringNullLetters - если строка содержит текстом "null", то возвращаем значение типа null
     *
     * @param  mixed $str
     *
     * @return void
     */
    public function getNullIsStringNullLetters( $str)
    {
        return (mb_strtolower($str) === "null") ? null : $str;
    }

    /**
     * getBlankStringIsStringNullLetters - если строка содержит текстом "null", то возвращаем пустую строку
     *
     * @param  mixed $str
     *
     * @return void
     */
    public function getBlankStringIsStringNullLetters( $str)
    {
        return (mb_strtolower($str) === "null") ? '' : $str;
    }


    public function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
    }

    
	public function paginateCollectionNotArrayToPaginator($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
		//return new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
		return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
