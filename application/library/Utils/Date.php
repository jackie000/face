<?php
/**
* @file Date.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version a
* @date 2016-01-08
 */

namespace Utils;

class Date{

    public static function currentDate( $type = 'long'){
        return $type == 'long' ? date('Y-m-d H:i:s', time() ) : date( 'Y-m-d', time() );
    }
}
?>
