<?php
namespace ResBooker\Controllers;
/**
  This is the projects base controller, All controllers
  should extend this since it provides a bunch of methods and
  parameters that all controllers must implement. 

  As the application grows - this will serve as a perfect
  place to add more functionality to all controllers.

**/
class Controller{
    /** 
      All theme assets are located in public/themes
      and all theme files are located in templates/themes

      The following theme name tells the render method below where to find
      the current themes files. 
    **/
	protected $theme = "default";
    protected $reg = null;

    /**
        All constructors for all controllers must take at minimum one argument
        namely the reg class.
    **/
    public function __construct($reg){
        $this->reg = $reg;

    }

    /**
      Render simply builds the HTML output sent to the browser
      it firstly converts the passed in $vars into individual variables, and than joins
      the themes header.php and controller method view file and footer.php file into one
      HTML document.

      This enables controller methods to send objects / variables / arrays and just
      about any other piece of data to the views.


    **/
    protected function render($template_name, $vars=[]){
    	extract($vars);
    	require_once(BASE_DIR."templates/themes/".$this->theme."/header.php");
    	require_once(BASE_DIR."templates/".$template_name.".php");
    	require_once(BASE_DIR."templates/themes/".$this->theme."/footer.php");
    }

    public function redirect($location){
        header("Location:" . BASE_URL . $location);
    }




}