<?php
/**
* @file Face.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version a
* @date 2016-01-07
 */
use Business\Photo;
class FaceController extends Yaf_Controller_Abstract{

    public function mvImagesAction(){
        echo "move images =====>\n";
        if( ( $originPath = $this->getOriginPath() ) && ( $handlePath = $this->getHandlePath() ) && ( $thumbPath = $this->getThumbPath() ) ){

            echo "foreach root dir '$originPath' \n";
            echo "\n\n";

            $conf = \Yaf_Registry::get( 'face' );
            $ext = explode( "|", $conf['extension'] );
            $filesnames = \Utils\File::findFiles( $originPath, array('fileTypes'=>$ext) );


            //是否有照片
            if( count( $filesnames ) > 0 ){
                $images = Photo\ImagesModel::getInstance();
                $images->mvPath( $filesnames, $originPath, $handlePath, $thumbPath );
            }else{
                echo "root dir not found photo ...";
            }

            //foreach( $filesnames as $file ){
            //    echo $file . "\n";
            //}
            echo "\n\n";


        }
    }

    public function analyzerFaceAction(){
        echo "analyzer face =====>\n\n";

        //1.today == 2016-01-10
        //
        //2.images status=0 and upload_date=today
        //
        //3.upload to face++, download face id
        //

        $day = \Utils\Date::currentDate('short');
        $imgs = \Dao\ImagesModel::getInstance()->getByUploadDate( $day );

        if( count( $imgs ) > 0 ){
            foreach( $imgs as $img ){
                \Business\Facepp\FaceModel::getInstance()->getImageFaces( $img );
            }
        }

    }

    public function analyzerFacesetAction(){
        echo "analyzer Faceset =====>\n\n";

        //获取当前faceset
        //
        //给faceset添加face, 更新数据库faceset tot
        //
        //faceset train

        $faces = \Dao\FaceModel::getInstance()->getNoFacesetId();

        if( ( $tot = count( $faces ) ) > 0 ){
            $faceset = \Business\Facepp\FacesetModel::getInstance()->getCurrentFaceset( $tot );
            //获取face的数量 + 当前faceset tot的数量 < config faceset_max
            //否则创建新faceset
            $faceFaceset = $faceId = array();
            foreach( $faces as $f ){
                $faceFaceset[] = $f['face_face_id'];
                $faceId[] = $f['face_id'];
            }

            if( \Business\Facepp\FacesetModel::getInstance()->addFaceset( $faceset['faceset_id'], $faceset['face_faceset_id'], $faceFaceset ) ){
                //更新face faceset_id
                \Dao\FaceModel::getInstance()->updateFacesetId($faceId, $faceset['faceset_id']);
            }
        }
    }

    public function trainFacesetAction(){
        echo "train Faceset =====>\n\n";
        //faceset status = 1 当前正在使用
        //train search
        $faceset = \Business\Facepp\FacesetModel::getInstance()->getCurrentFaceset( 0 );

        echo 'train search'."\r\nfaceset_id: ".$faceset['faceset_id']."\r\n".'session_id: ' .  \Business\Facepp\TrainModel::getInstance()->faceset( $faceset['face_faceset_id'] ) . "\r\n";

        $res = \Dao\FacesetModel::getInstance()->getFacesetTodayTrain();
        if( count( $res ) > 0 ){
            foreach( $res as $item ){
                echo 'train search'."\r\nfaceset_id: ".$faceset['faceset_id']."\r\n".'session_id: ' .  \Business\Facepp\TrainModel::getInstance()->faceset( $item['face_faceset_id'] ) . "\r\n";
            }
        }
    }


    public function testAction(){
        echo "test =====>\n\n";
        //1. 一张大头照片
        //2.给出归档照片和可能是自己的照片
        $img = $this->getRequest()->getParam('img', 0);
        $img = "/root/www/face/thumb_images/2016-01-12/1.jpg";
        if( $img ){
            $params = array();
            $params['img'] = $img;
            $face = \Yaf_Registry::get('facepp');
            $response = $face->execute( '/detection/detect', $params );
            if( $response['http_code'] == 200 ){
                $body = json_decode( $response['body'], true );
                $testFaceId = false;
                if( is_array( $body['face'] ) ){
                    foreach( $body['face'] as $item ){
                        $testFaceId = $item['face_id'];
                        break;
                    }
                }
                if( $testFaceId !== false ){
                    echo 'test face id: ' . $testFaceId . "\r\n";
                    $result = \Business\Facepp\RecognitionModel::getInstance()->search( $testFaceId );
                    if( $result && isset($result['same']) ){
                        $imgIds = $imgPath = array();
                        echo "和您匹配的照片:\r\n\r\n";
                        foreach( $result['same'] as $item ){
                            $res = \Dao\ImagesModel::getInstance()->getImageByFaceId( $item['face_id'] );
                            $imgIds[] = $res['image_id'];
                            $imgPath[] = $res['handle_path'];
                            echo 'similarity: ' . $item['similarity'] . "  " . "path: " . $res['handle_path'] . "\r\n";

                        }
                        echo "\r\n";
                        echo "\r\n";

                        $maybeImage = array();
                        if( isset( $result['maybe'] ) && count( $result['maybe'] ) > 0 ){
                            echo "和您可能匹配的照片:\r\n\r\n";
                            foreach( $result['maybe'] as $item ){
                                $res = \Dao\ImagesModel::getInstance()->getImageByFaceId( $item['face_id'], $imgIds );
                                if( $res ){
                                    echo 'similarity: ' . $item['similarity'] . "  " . "path: " . $res['handle_path'] . "\r\n";

                                }

                            }
                            echo "\r\n";
                            echo "\r\n";

                        }

                    }else{
                        echo '没发现您的照片!';
                    }
                }else{
                    echo '未获取人脸信息!';
                }
            }else{
                echo '分析照片失败!';
            }
        }else{
            echo '请上传照片!';
        }
    }

    /**
        * @brief getOriginPath 
        *
        * @return 
     */
    private function getOriginPath(){
        $config = \Yaf_Registry::get('face');
        if( $config['origin_path'] != '' && is_dir( $config['origin_path'] ) ){
            return $config['origin_path'];
        }

        echo "Not found config's origin path ! ";
        return false;
    }


    private function getHandlePath(){
        $config = \Yaf_Registry::get('face');
        if( $config['handle_path'] != '' && is_dir( $config['handle_path'] ) ){
            return $config['handle_path'];
        }

        echo "Not found config's handle path ! ";
        return false;
    }

    private function getThumbPath(){
        $config = \Yaf_Registry::get('face');
        if( $config['thumb_path'] != '' && is_dir( $config['thumb_path'] ) ){
            return $config['thumb_path'];
        }

        echo "Not found config's thumb path ! ";
        return false;

    }


    public function deleteFacesetAction(){
        $facesetName = $this->getRequest()->getParam("facesetName", 0);
        if( $facesetName ){
            \Business\Facepp\FacesetModel::getInstance()->deleteFacesetByName( $facesetName );
        }
    }

    public function getSessionAction(){
        $sessionId = $this->getRequest()->getParam("sessionId", 0);
        if( $sessionId ){
            $params = array();
            $params['session_id'] = $sessionId;
            $facepp = \Yaf_Registry::get('facepp');
            $response = $facepp->execute( '/info/get_session', $params );
            if( $response['http_code'] == 200 ){
                print_r( json_decode($response['body'], true) );
            }
        }

    }

}
?>
