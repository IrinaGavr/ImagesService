<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

/**
 * Description of CustomUpload
 *
 * @author irina
 */
class CustomUpload {
    public $extension;
    public $baseName;
    public $fileData;

    public function saveAs($path) {
        return file_put_contents($path, $this->fileData);
    }
}
