<?php

class DB {
    
    protected $_CONFIG;
    protected $_DB;
    
    public function __construct()
    {
        $this->_CONFIG = parse_ini_file(APPLICATION_PATH . DS. "conf". DS . "application.ini.php", TRUE);
    }
    /*
    Initiate the db connection
    */
    public function initDb()
    {
        $config = $this->_CONFIG;
        $this->_DB = new mysqli($config["database"]["server"], $config["database"]["username"], $config["database"]["password"], $config["database"]["db"], $config["database"]["port"]);
        if ($this->_DB->connect_errno) {
            throw new Exception( "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }
    }
    
        
    public function executeQuery($query)
    {
        if(!$this->_DB)
            $this->initDb();
        
        if(!$return = $this->_DB->query($query))
        {
            throw new Exception("Query failed: ". $this->_DB->error. " == $query");
        }
        
        return $return;
    }
    
    public function getLastInsertedNewsId()
    {
        return $this->_DB->insert_id;
    }

    
    public function saveNews($news_id=0)
    {
        //insert news into database
        $title = App::test_input($_POST["title"]);
        $content = App::test_input($_POST["content"]);
        $author = App::test_input($_POST["author"]);

        if($_POST["date"])
            $date = date("Y-m-d H:i:s",strtotime($_POST["date"]." ".$_POST["time"]));
        else
            $date= date("Y-m-d H:i:s");

        if(!$news_id)
            $query = "insert into posts set title=\"".$title."\", content=\"".$content."\", author=\"".$author."\", created=\"".$date."\"; ";
        else
            $query = "update posts set title=\"".$title."\", content=\"".$content."\", author=\"".$author."\", created=\"".$date."\", updated=now() WHERE id='".$news_id."';";

        $this->executeQuery($query);
        
        if($news_id)
            return $news_id;
        else
            return $this->getLastInsertedNewsId();
    }
    
    public function getNewsByQuery($search = "")
    {
        $array = array();
        
        $query = "select * from posts ";
        if($search)
            $query .= " where title like '%".$search."%' or content like '%".$search."%' or author like '%".$search."%'";

        $query .= " order by id desc";
        
        $result  = $this->executeQuery($query);
        
        while ($row = $result->fetch_assoc()) {
            $array[] = $row;
        }
        
        return $array;
    }
    
    public function getNewsById($news_id = "")
    {
        $array = array();
        
        $query = "select * from posts ";
        if($news_id)
            $query .= " where id='".$news_id."'";
        $query .= " order by id desc LIMIT 1";
        
        $result  = $this->executeQuery($query);
        
        return $result->fetch_array(MYSQLI_ASSOC);
    }
    
    public function deleteNews($news_id)
    {
        $query = "DELETE FROM posts where id='".$news_id."'";
        return $result  = $this->executeQuery($query); 
    }
    
    public function loadNews($news_id)
    {
        $query = "select * from posts where id='".$news_id."'";
        $result = $this->executeQuery($query);

        if($result)
            $row = $result->fetch_array();
        
        return $row;
        
    }
}