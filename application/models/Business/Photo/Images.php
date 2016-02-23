<?php
/**
    * @file Images.php
    * @brief 
    * 处理图片
    * 1.把当前已有照片移动到指定位置
    * 2.生成可上传到face++的图片
    * 3.把可用信息保存到数据库
    * @author jackie <jackie@digiocean.cc>
    * @date 2016-01-06
 */

namespace Business\Photo;
class ImagesModel extends \Business\AbstractModel{

    /**
        * @brief mvPath 
        *
        * @param $files
        * @param $originPath
        * @param $handlePath
        *
        * @return 
     */
    public function mvPath( $files=array(), $originPath='', $handlePath='', $thumbPath='' ){
        echo "ImagesModel move path!\n\n";

        //创建handle,thumb存放目录
        $wantMkdir = array();
        foreach( $files as $file ){
            $targetPath = $this->getImageHandlePath( $file, $originPath, $handlePath );
            echo $targetPath . "\n";
            $path = pathinfo( $targetPath, PATHINFO_DIRNAME );
            if( !in_array( $path, $wantMkdir ) ) {
                $wantMkdir[] = $path;
            }

            $tubPath = $this->getImageThumbPath( $file, $originPath, $thumbPath );
            echo $tubPath . "\n";
            $path = pathinfo( $tubPath, PATHINFO_DIRNAME );
            if( !in_array( $path, $wantMkdir ) ) {
                $wantMkdir[] = $path;
            }
        }

        echo "\n";
        foreach( $wantMkdir as $dir ){
            if( !file_exists( $dir ) ){
                \Utils\File::createDirectory( $dir ) ;
            }
        }

        $failed = array();

        //移动照片文件
        $sucess = array();
        foreach( $files as $file ){
            $targetPath = $this->getImageHandlePath( $file, $originPath, $handlePath );
            if( rename( $file, $targetPath ) ){
                $sucess[] = $file;
            }else{
                $failed['mv'][] = $file;
            }
        }

        //移动成功的照片生成缩略图，符合face++的大小
        if( count( $sucess ) > 0 ){
            $insertDatabase = array();
            foreach( $sucess as $file ){
                if( $this->createThumb( $this->getImageHandlePath( $file, $originPath, $handlePath), $this->getImageThumbPath( $file, $originPath, $thumbPath ) ) ){
                    //缩略图成功
                    $exif = exif_read_data( $this->getImageHandlePath( $file, $originPath, $handlePath ) );

                    $insertDatabase[] = array( 'origin_path'=>$file, 'handle_path'=>$this->getImageHandlePath($file, $originPath, $handlePath ), 'thumb_path'=>$this->getImageThumbPath($file, $originPath, $thumbPath ), 'upload_date'=> \Utils\Date::currentDate('short'), 'image_create_date'=>$exif['DateTime'], 'owner'=>$exif['OwnerName'], 'camera_model'=>$exif['Model'] );
                }else{
                    //缩略图不成功
                    $failed['thumb'][] = $file;
                }
            }

            $this->insertDatabase( $insertDatabase );
        }

        //处理移动照片失败和缩略图生成失败的图片
        $log = \Log\File::getInstance();
        if( isset( $failed['mv'] ) && count( $failed['mv'] ) > 0 ){
            $log->setFilename('image_mv_error');
            $log->log( "\r\n" . implode( "\r\n", $failed['mv'] ), \Log::ERROR );
        }

        if( isset( $failed['thumb'] ) && count( $failed['thumb'] ) > 0 ){
            $log->setFilename('image_thumb_error');
            $log->log( "\r\n" . implode( "\r\n", $failed['thumb'] ), \Log::ERROR );
        }
    }

    private function getImageHandlePath( $file, $originPath, $handlePath ){
        return  str_replace( $originPath, $handlePath, $file );
    }

    private function getImageThumbPath( $file, $originPath, $thumbPath ){
        return str_replace( $originPath, $thumbPath, $file );
    }

    /**
        * @brief insertDatabase 
        *
        * @param $params
        *
        * @return 
     */
    public function insertDatabase( $params ){
        foreach( $params as $param ){
            \Dao\ImagesModel::getInstance()->insert( $param );
        }
    }


    /**
        * @brief createThumb 
        * 生成可上传到face++的图片
        * @return 
     */
    public function createThumb( $file, $thumbPath ){

        echo 'thumb' . $file . ">>>" . $thumbPath . "\r\n";
        $conf = \Yaf_Registry::get('face');
        if($conf['size'] > filesize($file) ){
            return copy($file, $thumbPath);
        }

        $img = new \Utils\Image();
        $imgConf = array();
        $imgConf['source_image'] = $file;
        $imgConf['new_image'] = $thumbPath;
        $imgConf['create_thumb'] = true;
        $imgConf['thumb_marker'] = '';
        //$imgConf['master_dim'] = "width";
        $imgConf['width'] = 800;
        $imgConf['height'] = 800;
        $img->initialize( $imgConf );
        return $img->resize();
    }

}
?>
