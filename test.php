<?php
$a = array();
echo empty( $a ) ? "empty" : "not empty";
echo "\r\n";
echo $a ? "if yes" : "if not";
echo "\r\n";
exit;
$a = array( 1,2,"t" );
echo implode(",", array_map('intval', $a ) );
exit;

$path = "a/b/c";
echo str_replace( '', '/', $path );

var_dump( explode( '/', $path ) );
class aa{
    const WARNING = 'warning';
}

echo aa::WARNING;
$a = "  './configure' '--prefix=/usr/local/php-7.0.0' '--with-apx2' '--enable-fpm' '--with-mysqli' '--with-pdo-mysql' '--with-icon-dir' '--with-freetype-dir=/usr/local/homebrew/homebrew/Cellar/freetype/2.6_1/lib' '--with-jpeg-dir=/usr/local/homebrew/homebrew/Cellar/jpeg/8d/lib' '--with-png-dir=/usr/local/homebrew/homebrew/Cellar/libpng/1.6.19/lib' '--with-zlib' '--with-curl' '--with-gd' '--with-mhash' '--with-mbstring' '--with-mcrypt=/usr/local/homebrew/homebrew/Cellar/mcrypt/2.6.8/lib' '--with-config-file-path=/etc/myconf/php-7.0.0'''''''''''''''''''''''''''''''''''";

echo str_replace("'", "", $a);


?>
