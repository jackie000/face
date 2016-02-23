<?php
/**
* @file Log.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version a
* @date 2016-01-11
 */

class Log{
    const ALERT = 'alert';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';

    static private $instance = [];

    private function __construct(){}

    private function __clone(){}

    public static function __callStatic( $name, $args ){
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
