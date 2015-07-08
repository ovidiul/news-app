<?php

class appTemplate {
    /**
     * The filename of the template to load.
     *
     * @access protected
     * @var string
     */
    protected $file;

    /**
     * An array of values for replacing each tag on the template (the key for each value is its corresponding tag).
     *
     * @access protected
     * @var array
     */
    protected $values = array();

    /**
     * Creates a new Template object and sets its associated file.
     *
     * @param string $file the filename of the template to load
     */
    public function __construct($file) {
        $this->file = APPLICATION_PATH .DS."views" .DS. $file;
    }
    
    public function init()
    {
        $baseUrl = appTemplate::getBaseUrl();
        $this->set("baseUrl", $baseUrl);
    }
    
    public static function getBaseUrl()
    {
        return dirname($_SERVER['PHP_SELF']);
    }

    /**
     * Sets a value for replacing a specific tag.
     *
     * @param string $key the name of the tag to replace
     * @param string $value the value to replace
     */
    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    /**
     * Outputs the content of the template, replacing the keys for its respective values.
     *
     * @return string
     */
    public function output() {

        if (!file_exists($this->file)) {
            return "Error loading template file ($this->file).<br />";
        }
        $output = file_get_contents($this->file);

        foreach ($this->values as $key => $value) {
            $tagToReplace = "[@$key]";
            $output = str_replace($tagToReplace, $value, $output);
        }
                        
        return $output;
    }

    /**
     * Merges the content from an array of templates and separates it with $separator.
     *
     * @param array $templates an array of Template objects to merge
     * @param string $separator the string that is used between each Template object
     * @return string
     */
    static public function merge($templates, $separator = "\n") {

        $output = "";

        foreach ($templates as $template) {
            $content = (get_class($template) !== "Template")
                ? "Error, incorrect type - expected Template."
                : $template->output();
            $output .= $content . $separator;
        }
        
        return $output;
    }
    
    public static function redirect ($url)
    {
        header("Location: $url");
        exit;
    }
    
    public static function loadLayout($params = array())
    {
        $layout = new appTemplate("layout.phtml");
        
        if(is_array($params))
        foreach($params as $key=>$value)
            $layout->set($key, $value);
                
        $layout->init();
        
        return preg_replace("/\[@(.*)\]/", "", $layout->output());
    }
    
   
}

?>