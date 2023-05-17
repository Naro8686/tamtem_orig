<?php

namespace App\Classes\DataLogger; 

//use Closure;
//use App\Models\Log; //Прописываем нашу модель, для возможности доступа к ней

class DataLogger
{
    
    /**
     * create - соаздет запись в логе
     *
     * @param  mixed $url
     * @param  mixed $status
     * @param  mixed $method
     * @param  mixed $parameters
     * @param  mixed $reponse
     * @param  mixed $user_id
     *
     * @return void
     */
    public static function create($url, $status, $method, $parameters, $reponse, $user_id = null)  //Функция, которая вызывается после посылки ответа пользователю
    {

        if ( config('b2b.data_logger.data_logger_active') === true) {   //Если конфиге b2b прописана опция data_logger_active = true используем логирование
            if ( config('b2b.data_logger.data_logger_use_db') === true ) {      //Если конфиге b2b прописана опция data_logger_use_db=true, то для сохранения используем БД иначе пишем просто в файл
                // сейчас нет таблицы, пишем только в файл
                // $log = new Log();
                // $log->user_id = $user_id;
                // $log->data = now(); // gmdate('Y-m-d H:i:s');
                // $log->save();     //Сохранили в базу нашу запись
            }
            else              //Если опция записи в БД недоступна пишем в файл
            {
                $filename = 'api_datalogger_' . date('Y-m-d') . '.log';
                $dataToLog  = 'Time: '   . now() /*gmdate("F j, Y, g:i a")*/ . "\n";
                $dataToLog .= 'UserId: '   . $user_id . "\n";
                $dataToLog .= 'URL: '    . $url . "\n";
                $dataToLog .= 'Status: '    . $status . "\n";
                $dataToLog .= 'Method: ' . $method . "\n";
                $dataToLog .= 'Parameters: ' . $parameters . "\n";
                $dataToLog .= 'Reponse: '  . $reponse . "\n";
                \File::append( storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");       //Записали в файл
            }
        }
    }
}
