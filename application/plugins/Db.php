<?php
class DbPlugin extends Yaf_Plugin_Abstract {
    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }


    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }


    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        echo "plugin 6\n";
        var_dump( \Db::getInstance()->error() );
        $log = \Db::getInstance()->log();
        if(is_array( $log ) ){
            foreach( $log as $sql ){
                echo str_replace("\"", "`", $sql) . "\r\n";
            }
        }
    }
}

?>
