<?php
/**
* @file Face.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version 1.0
* @date 2016-01-12
 */
namespace Mysql;

class FaceModel extends \Mysql\AbstractModel{
    public function __construct(){
        $this->_tableName = 'faces';
    }
}

?>
