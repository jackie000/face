<?php
/**
 * @file File.php
 * @brief 
 * @author jackie <jackie@digiocean.cc>
 * @version a
 * @date 2016-01-07
 */

namespace Utils;
class File{

    public static function findFiles( $dir, $options=array() ){
        $fileTypes=array();
        $exclude=array();
        $level=-1;
        $absolutePaths=true;
        extract($options);
        $list=self::findFilesRecursive($dir,'',$fileTypes,$exclude,$level,$absolutePaths);
        sort($list);
        return $list;
    }

    public static function removeDirectory( $directory, $options = array() ){
        if( !isset( $options['traverseSymlinks'] ) )
            $options['traverseSymlinks'] = false;
        $items=glob($directory.DIRECTORY_SEPARATOR.'{,.}*',GLOB_MARK | GLOB_BRACE);
        foreach( $items as $item ){
            if( basename($item)=='.' || basename($item)=='..' )
                continue;

            if( substr($item,-1)==DIRECTORY_SEPARATOR ){
                if(!$options['traverseSymlinks'] && is_link(rtrim($item,DIRECTORY_SEPARATOR)))
                    unlink(rtrim($item,DIRECTORY_SEPARATOR));
                else
                    self::removeDirectory($item,$options);
            }else
                unlink($item);
        }

        if(is_dir($directory=rtrim($directory,'\\/'))){
            if(is_link($directory))
                unlink($directory);
            else
                rmdir($directory);
        }
    }

    public static function findFilesRecursive($dir,$base,$fileTypes,$exclude,$level,$absolutePaths){
        $list=array();
        $handle=opendir($dir.$base);
        if($handle===false)
            throw new Exception('Unable to open directory: ' . $dir);
        while(($file=readdir($handle))!==false){
            if($file==='.' || $file==='..')
                continue;
            $path=substr($base.DIRECTORY_SEPARATOR.$file,1);
            $fullPath=$dir.DIRECTORY_SEPARATOR.$path;
            $isFile=is_file($fullPath);
            if(self::validatePath($base,$file,$isFile,$fileTypes,$exclude)){
                if($isFile)
                    $list[]=$absolutePaths?$fullPath:$path;
                elseif($level)
                    $list=array_merge($list,self::findFilesRecursive($dir,$base.DIRECTORY_SEPARATOR.$file,$fileTypes,$exclude,$level-1,$absolutePaths));
            }
        }
        closedir($handle);
        return $list;
    }

    public static function validatePath($base,$file,$isFile,$fileTypes,$exclude){
        foreach($exclude as $e){
            if($file===$e || strpos($base.DIRECTORY_SEPARATOR.$file,$e)===0)
                return false;
        }
        if(!$isFile || empty($fileTypes)){
            return true;
        }

        if(($type=self::getExtension($file))!=='')
            return in_array($type,$fileTypes);
        else
            return false;
    }

    public static function getExtension($path)
    {
        return pathinfo($path,PATHINFO_EXTENSION);
    }

    public static function createDirectory( $path, $mode=0755 ){
        return mkdir( $path, $mode, true );
    }
}
?>
