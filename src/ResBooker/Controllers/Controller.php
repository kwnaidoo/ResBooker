<?php
namespace ResBooker\Controllers;

class Controller{
	protected $theme = "default";
    protected $reg = null;

    public function __construct($reg){
        $this->reg = $reg;

    }


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