<?php
include_once(APPLICATION_PATH . DS. "controllers". DS . "news.php");
include_once(APPLICATION_PATH . DS. "classes". DS . "template.class.php");
include_once(APPLICATION_PATH . DS. "classes". DS . "db.class.php");

class App{
    
    protected $_CONFIG;

    /* 
    Main Construct Method
    */
    public function __construct()
    {
        $this->_CONFIG = parse_ini_file(APPLICATION_PATH . DS. "conf". DS . "application.ini.php", TRUE);
    }
    
    
    /*
    Initiate the controller action
    */
    public function initController($action, $args=array())
    {
        
         if(!$action)
            $action = "noRoute";
        
        //echo $action;
        //$this->$action($args);
        echo call_user_func(array("appController", $action), $args);
    }
    
    
    /*
    Basic route handling
    */
    public function handleRoute()
    {
        $path = $_SERVER['REQUEST_URI'];
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);
        $route = str_replace($baseDir, "", $path);
        $parts = explode("/", $route );
        $action = "";

        $request = $parts[1];
        if(sizeof($parts) < 2)
            $id = $parts[2];
        
        if(isset($this->_CONFIG["route"]["/".$request])){
            $action = $this->_CONFIG["route"]["/".$request];
        }
       
        $this->initController($action, array_slice($parts, 2));
        
    }
    
    public static function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    
    /* 
    Initiate the application
    */
    public function run()
    {
        $this->handleRoute();
        
    }
}
