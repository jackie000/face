<?php
/**
* @file Faceset.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @on 1.0
* @date 2016-01-12
 */
namespace Business\Facepp;

class FacesetModel extends \Business\AbstractModel {

    public function createFaceset(){

        $params = array();
        $facepp = \Yaf_Registry::get('facepp');
        $response = $facepp->execute( '/faceset/create', $params );
        if( $response['http_code'] == 200 ){
            $facesetId = json_decode( $response['body'], true )['faceset_id'];
            $facesetName = json_decode( $response['body'], true )['faceset_name'];
            $data = array();
            $data['face_faceset_id'] = $facesetId;
            $data['face_faceset_name'] = $facesetName;
            $data['train_status'] = 0;
            $data['create_date'] = \Utils\Date::currentDate('short');
            $data['tot'] = 0;
            $data['status'] = 1;
            if( ( $fasId = \Dao\FacesetModel::getInstance()->insert( $data ) ) ){
                $data['faceset_id'] = $fasId;
            }
            return $data;
        }

        return false;

    }

    public function getCurrentFaceset( $tot ){
        $faceset = \Dao\FacesetModel::getInstance()->getCurrentFaceset();
        if( $faceset ){
            $curTot = isset( $faceset['tot'] ) ? intval( $faceset['tot'] ) : 0;
            $face = \Yaf_Registry::get('face');
            if( ( $curTot + intval( $tot ) ) < intval( $face['faceset_max'] ) ){
                return $faceset;
            }else{
                //更新当前faceset status = 0
                //创建新faceset
                \Dao\FacesetModel::getInstance()->updateStatus( $faceset['faceset_id'], 0 );
            }
        }
        return $this->createFaceset();
    }

    public function addFaceset( $facesetId, $faceFacesetId, $faces=array() ){
        $params = array();
        $params['faceset_id'] = $faceFacesetId;
        $params['face_id'] = implode( ",", $faces );
        $facepp = \Yaf_Registry::get('facepp');
        $response = $facepp->execute( '/faceset/add_face', $params );
        if( $response['http_code'] == 200 ){
            if( json_decode($response['body'],true)['success'] == "true" ){
                $body = json_decode( $response['body'], true );
                return \Dao\FacesetModel::getInstance()->updateFacesetTot( $facesetId, $body['added'] );
            }
        }
        return false;
    }

    public function deleteFacesetByName( $facesetName ){
        $params = array();
        $params['faceset_name'] = $facesetName;
        return $this->deleteFaceset( $params );
    }

    public function deleteFacesetById( $facesetId ){
        $params = array();
        $params['faceset_id'] = $facesetId;
        return $this->deleteFaceset( $params );
    }

    public function deleteFaceset( $params ){
        $facepp = \Yaf_Registry::get('facepp');
        $response = $facepp->execute( '/faceset/delete', $params );
        if( $response['http_code'] == 200 ){
            if( json_decode($response['body'],true)['success'] == "true" ){
                return true;
            }
        }
        return false;

    }

}
?>
