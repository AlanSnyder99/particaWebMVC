<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout{

    private $ci;
    public $layout;
    public $folder;
    public $pagename;

    public function __construct($layout = "layout", $folder=""){
        $this->ci =& get_instance();
        
        $this->pagename = $this->ci->uri->segment(1);

        $this->setLayout($layout);
        $this->setFolder($folder);
    }

    public function setLayout($layout){
      $this->layout = $layout;
    }
    
    public function setFolder($folder){
      $this->folder = $folder;
    }

    public function view($view, $data=array(), $return=false){
        $data["server_error"] = $this->ci->session->flashdata('server_error');
        $data["pagename"] = $this->pagename;
        $data["method"] = $this->ci->router->method;

        $view = $this->folder . '/' . $view;
        
        $loadedData = array();
        $loadedData['content_for_layout'] = $this->ci->load->view($view,$data,true);

        if($return){
            $output = $this->ci->load->view($this->layout, $loadedData, true);
            return $output;
        }else{
            $this->ci->load->view($this->layout, $loadedData, false);
        }
        
    }
}
?>