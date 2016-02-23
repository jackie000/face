<?php
/**
* @file Face.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version 1.0
* @date 2016-01-12
 */
namespace Dao;
class FaceModel extends \Dao\AbstractModel{

    public function insert( $data ){
        if( \Mysql\FaceModel::getInstance()->insert( $data ) ){
            return \Dao\ImagesModel::getInstance()->updateAnalyzerImage( $data['image_id'], 'Y');
        }

        return false;
    }


    public function getNoFacesetId(){
        $face = \Mysql\FaceModel::getInstance();
        return $face->getDb()->select( $face->getTable(), '*', ['faceset_id'=>0] );
    }

    public function updateFacesetId($faceIds, $facesetId){
        $face = \Mysql\FaceModel::getInstance();
        return $face->getDb()->update( $face->getTable(), ['faceset_id'=>intval($facesetId)], ['face_id'=>$faceIds] );

    }

}

?>
