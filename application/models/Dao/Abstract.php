<?php
/**
* @file Abstract.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version a
* @date 2016-01-08
 */

namespace Dao;
abstract class AbstractModel{

    static private $instance = [];

    //禁止clone
    final private function __clone(){
        return false;
    }

    public static function __callStatic( $name, $args){
        if( $name == "getInstance" ){
            $class = get_called_class();
            if( !isset( self::$instance[$class] ) ){
                self::$instance[$class] = new $class();
            }

            return self::$instance[$class];
        }
    }
}

?>
