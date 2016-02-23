<?php
/**
* @file Face.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version 1.0
* @date 2016-01-11
 */
namespace Business\Facepp;

class FaceModel extends \Business\AbstractModel{

    public function getImageFaces( $img ){
        $params = array();
        echo $img['thumb_path'] . "\r\n";
        $params['img'] = $img['thumb_path'];
        $face = \Yaf_Registry::get('facepp');
        $response = $face->execute( '/detection/detect', $params );
        if( $response['http_code'] == 200 ){

            $body = json_decode( $response['body'], true );
            $faces = array();
            if( is_array( $body['face'] ) && !empty( $body['face'] )  ){
                foreach( $body['face'] as $item ){
                    $this->insertFace(array('face_face_id'=>$item['face_id'],'image_id'=>$img['image_id']));
                }
                return $body['face'];
            }else{
                //未发现face
                \Dao\ImagesModel::getInstance()->updateAnalyzerImage( $img['image_id'] );

                //记录未发现face
                $log = \Log\File::getInstance();
                $log->setFilename('image_no_face');
                $log->log( "\r\n imageId: " . $img['image_id'] . " thumbPath: " . $img['thumb_path'], \Log::ERROR );
            }
        }
        return false;
    }

    public function insertFace( $face ){
        //insert face
        //update images status=1 face_handle_date=current, is_face='Y'
        if( $face['face_face_id'] && $face['image_id'] ){
            return \Dao\FaceModel::getInstance()->insert( $face );
        }
    }
}

?>
