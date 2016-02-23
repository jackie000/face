<?php
/**
* @file Images.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version a
* @date 2016-01-08
 */

namespace Dao;
class ImagesModel extends \Dao\AbstractModel{

    public function insert( $data ){
        return \Mysql\ImagesModel::getInstance()->insert( $data );
    }

    public function getByUploadDate( $day, $status=0 ){
        $img = \Mysql\ImagesModel::getInstance();
        return $img->getDb()->select( $img->getTable(), '*', [
            "AND"=>[
                "upload_date"=>$day,
                "status"=>$status
            ]
        ]);

    }

    public function updateAnalyzerImage( $imageId, $isFace='N' ){
        $img = \Mysql\ImagesModel::getInstance();

        return $img->getDb()->update( $img->getTable(), [
            'status'=>1,
            'face_handle_date'=>\Utils\Date::currentDate(),
            'is_face'=>$isFace
        ],[
            'image_id'=>$imageId
        ]);
    }

    public function getImageByFaceId( $faceId, $notInImages=array() ){
        $img = \Mysql\ImagesModel::getInstance();
        $face = \Mysql\FaceModel::getInstance();
        $imgTable = $img->getTable();
        $faceTable = $face->getTable();
        $db = $img->getDb();

        if( count( $notInImages ) > 0 ){
            return $db->get($imgTable, ['[><]' . $faceTable=>['image_id'=>'image_id']],[
                $imgTable . ".image_id", $imgTable . ".handle_path"
            ],[ "AND"=>[
                $faceTable . ".face_face_id"=>$faceId,
                $imgTable. ".image_id[!]"=>$notInImages
            ]
            ]);
        }else{
            return $db->get($imgTable, ['[><]' . $faceTable=>['image_id'=>'image_id']],[
                $imgTable . ".image_id", $imgTable . ".handle_path"
            ],[
                $faceTable . ".face_face_id"=>$faceId
            ]);

        }
    }


    public function getImagesByFaceId( $faceIds,$notInImages=array() ){
        $img = \Mysql\ImagesModel::getInstance();
        $face = \Mysql\FaceModel::getInstance();
        $imgTable = $img->getTable();
        $faceTable = $face->getTable();
        $db = $img->getDb();
        $faceIds = array_map(array($db, 'quote'), $faceIds );

        $rowSql = "SELECT image_id FROM $faceTable WHERE face_face_id=";
        $sql = "SELECT $imgTable.* FROM $imgTable WHERE EXISTS ( SELECT * FROM ( $rowSql";
        $sql.= implode( " UNION ALL " . $rowSql, $faceIds );
        $sql.= ") as tmp WHERE tmp.image_id=$imgTable.image_id ) ";
        if( count($notInImages) > 0 ){
            $sql.= " AND $imgTable.image_id NOT IN ( ". implode(",", array_map('intval', $notInImages)) ." ) ";
        }
        $sql.= " GROUP BY $imgTable.image_id ";

        return $db->query($sql)->fetchAll();

    }
}
?>
