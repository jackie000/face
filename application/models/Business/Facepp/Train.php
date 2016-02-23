<?php
/**
* @file Train.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version 1.0
* @date 2016-01-13
 */
namespace Business\Facepp;
class TrainModel extends \Business\AbstractModel{

    public function faceset( $facesetId ){
        $params = array();
        $params['faceset_id'] = $facesetId;
        $facepp = \Yaf_Registry::get('facepp');
        $response = $facepp->execute( '/train/search', $params );
        if( $response['http_code'] == 200 ){
            return json_decode( $response['body'], true )['session_id'];
        }

        return false;
    }
}
?>
