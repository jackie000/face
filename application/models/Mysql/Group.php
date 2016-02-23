<?php
/**
* @file Group.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version 1.0
* @date 2016-01-11
 */
namespace Mysql;

class GroupModel extends \Mysql\AbstractModel{

    public function __construct(){
        $this->_tableName = 'groups';
    }
}
?>
