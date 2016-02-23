<?php
/**
* @file Faceset.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version 1.0
* @date 2016-01-12
 */

namespace Dao;

class FacesetModel extends \Dao\AbstractModel{

    public function insert( $data ){
        return \Mysql\FacesetModel::getInstance()->insert( $data );
    }

    public function getCurrentFaceset(){
        $faceset = \Mysql\FacesetModel::getInstance();
        return $faceset->getDb()->get( $faceset->getTable(), '*', ["AND"=>[
            'create_date'=>\Utils\Date::currentDate('short'),
            'status'=>1 //当前正在使用
        ]]);
    }

    public function updateFacesetTot( $facesetId, $tot ){
        $faceset = \Mysql\FacesetModel::getInstance();
        return $faceset->getDb()->update( $faceset->getTable(),['tot[+]'=>intval($tot),'train_status'=>0],['faceset_id'=>$facesetId]);
    }

    public function updateStatus( $facesetId, $status ){
        $faceset = \Mysql\FacesetModel::getInstance();
        return $faceset->getDb()->update( $faceset->getTable(),['status'=>intval($status)], ['faceset_id'=>$facesetId] );
    }

    public function updateTrainStatus( $facesetId, $status ){
        $faceset = \Mysql\FacesetModel::getInstance();
        return $faceset->getDb()->update( $faceset->getTable(),['train_status'=>intval($status)], ['faceset_id'=>$facesetId] );
    }

    public function getFacesetTodayTrain(){
        $faceset = \Mysql\FacesetModel::getInstance();
        return $faceset->getDb()->select( $faceset->getTable(),'*', [
            "AND"=>[
                'status'=>0,
                'train_status'=>0
            ]
        ]);
    }

    public function getUsableSearchFaceset(){
        $faceset = \Mysql\FacesetModel::getInstance();
        return $faceset->getDb()->select($faceset->getTable(), '*', ['create_date'=>\Utils\Date::currentDate('short')]);

    }
}
?>
