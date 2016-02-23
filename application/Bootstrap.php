<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig(){
        Yaf_Registry::set('config', Yaf_Application::app()->getConfig() );
    }

    public function _initRoute(){
        //Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
        //    "supervar", new Yaf_Route_Supervar("r")
        //);
        //Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
   //    "simple", new Yaf_Route_Simple( 'm', 'c', 'a' )
        //);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher){

        if( strtolower( $dispatcher->getRequest()->getMethod() ) == 'cli' ){
            $dbPlugin = new DbPlugin();
            $dispatcher->registerPlugin($dbPlugin);
        }

    }

    public function _initDatabase(){
        Yaf_Registry::set( 'database', Yaf_Registry::get('config')->database->master->toArray() );
        Db::getInstance();
    }

    public function _initFacepp(){
        $face = new \Facepp\Facepp();
        $face->api_key = Yaf_Registry::get('config')->facepp->key;
        $face->api_secret = Yaf_Registry::get('config')->facepp->secret;
        Yaf_Registry::set( 'facepp', $face );

        Yaf_Registry::set('face', Yaf_Registry::get('config')->facepp->toArray());
    }

    public function _initCli(Yaf_Dispatcher $dispatcher){
        if( strtolower( $dispatcher->getRequest()->getMethod() ) == 'cli' ){
            Yaf_Dispatcher::getInstance()->disableView();
        }
    }

    public function _initTest(){
        var_dump("aa");

    }

}
?>
