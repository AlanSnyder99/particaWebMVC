<?php

class View{

    private $path;

    public function __construct(){
        $this->path = Path::getInstance();
    }

    function generate($content_view, $template_view, $data = null){

        include $this->path->getPath("view", $template_view );
    }

    function generateSt($content_view, $data = null, $data2 = null,$data3 = null,$data4 = null, $data5 =null){
        include $this->path->getPath("view",$content_view);
    }

    function generateErrorView($content_view){
        include $this->path->getPath("view_error", $content_view);
    }
}