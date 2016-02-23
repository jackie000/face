<?php
class IndexController extends Yaf_Controller_Abstract {

    public function indexAction(){
        //测试数据库
        //\Dao\ImagesModel::getInstance()->insert( array('origin_path'=>'o','handle_path'=>'h','thumb_path'=>'t','upload_date'=>\Utils\Date::currentDate('short') ) );
        //
        //
        //测试图片缩略

        //$img = new \Utils\Image();
        //$imgConf = array();
        //$imgConf['source_image'] = '/root/www/face/public/..//origin_images/1.jpg';
        //$imgConf['new_image'] = '/root/www/face/public/..//thumb_images/1.jpg';
        //$imgConf['create_thumb'] = true;
        ////$imgConf['master_dim'] = "width";
        //$imgConf['width'] = 800;
        //$imgConf['height'] = 800;
        //$img->initialize( $imgConf );
        //return $img->resize();
        //echo function_exists('imagecreatefromgif') ? "exists" : "no";
        //exit;
        //
        //$test = array('thumb_path'=>'/root/www/face/thumb_images/2016-01-12/1.jpg');
        //\Business\Facepp\FaceModel::getInstance()->getImageFaces( $test );
        //exit;
        //
        //
        //\Utils\File::removeDirectory( $originPath . DIRECTORY_SEPARATOR . "2016-01-08", array('traverseSymlinks'=>1));

        $faceId = array("f51f426269487072d8db0777dd1d2663", 'fe3ef528caf927dceb49097a87ff97fc');
        //$db = Db::getInstance();
        //$faceId = array_map(array($db, 'quote'), $faceId );
        //echo implode(',', $faceId);
        //$db->select( 'images',["[><]faces"=>["image_id"=>"image_id"]],['images.image_id'],["faces.face_face_id"=>$faceId, "GROUP"=>"images.image_id"] );
        //$res = \Dao\ImagesModel::getInstance()->getImagesByFaceId( $faceId, array(10,9) );
        //var_dump($res);
        //echo \Dao\ImagesModel::getInstance()->faceToImagesSQL( $faceId ) . "\r\n";
        $val = getimagesize( "/Users/a/Downloads/dresslande.com/ccc/alexia_designs/bridesmaids/alexia_designs/4004.jpg" );
        print_r( $val );
        echo "\r\n";
        echo "\r\n";

        $val = filesize( "/Users/a/Downloads/dresslande.com/ccc/alexia_designs/bridesmaids/alexia_designs/4004.jpg" );
        echo ceil($val/1024) . "kb\r\n";
        print_r( $val );
    }
}
?>
