<?php
/**
* @file File.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version a
* @date 2016-01-11
 */
namespace Log;

class File extends \Log{

    private $path = '/logs/';
    private $file = 'php.log';

    public function __construct(){
        $this->path = APP_PATH . $this->path;
        $this->setFilename( $this->file );
    }

    private function mkpath( $path, $mode=0755 ){
        return \Utils\File::createDirectory( $path, $mode );
    }

    public function setFilename( $name ){
        $this->file = $this->path . \Utils\Date::currentDate('short') . $name . '.txt';
    }

    public function getFilename(){
        return $this->file;
    }

    public function log( $message, $level=Log::WARNING ){
        if( !is_dir( $this->path ) ){
            $this->mkpath( $this->path );
        }

        error_log( 'Level: ' . $level . ' Time: '. \Utils\Date::currentDate() . ' ' . $message . "\r\n\r\n", 3, $this->getFilename() );

    }
}
?>
