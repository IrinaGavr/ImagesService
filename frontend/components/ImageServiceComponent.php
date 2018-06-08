<?php

namespace frontend\components;

use yii\base\Component;

class ImageServiceComponent extends Component {

    public $url;

    const UPLOAD = 'upload/json';
    const DOWNLOAD_ID = 'get/id';
    const DOWNLOAD_MODEL = 'get/model';

    public function sendFile($model_id, $model_name, $file_path, $desc = '') {
        $file = file_get_contents($file_path);
        $data = [
            'file' => [
                'ext' => pathinfo($file_path, PATHINFO_EXTENSION),
                'data' => base64_encode($file)
            ],
            'Upload' => [
                'model_id' => $model_id,
                'model_name' => $model_name,
                'desc' => $desc
            ]
        ];
        $obj_to_send = [
            'ajaxUpload' => json_encode($data)
        ];

        $url = $this->url . self::UPLOAD;
        
        $curl = curl_init(); //инициализация сеанса
        curl_setopt($curl, CURLOPT_URL, $url); //урл сайта к которому обращаемся
        curl_setopt($curl, CURLOPT_HEADER, 1); //выводим заголовки
        curl_setopt($curl, CURLOPT_POST, 1); //передача данных методом POST
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //теперь curl вернет нам ответ, а не выведет
        curl_setopt($curl, CURLOPT_POSTFIELDS, $obj_to_send);
        $res = curl_exec($curl);
        return $res;
    }

    public function getFileId($file_id) {

        $url = $this->url . self::DOWNLOAD_ID . "/{$file_id}";


        $resalt = file_get_contents($url);

        if (strlen($resalt) == 0) {
            return FALSE;
        }
        return $resalt;
    }

    public function getModelVallue($model_id, $model_name) {

        $url = $this->url . self::DOWNLOAD_MODEL . "/{$model_id}" . "/{$model_name}";


        $resalt = file_get_contents($url);

        if (strlen($resalt) == 0) {
            return FALSE;
        }
        return $resalt;
    }

}
