<?php

class Path
{
    private static $instance;
    private $paths;

    //Evita que el objeto se pueda crear desde afuera
    private function __construct($pathsFile){

        if (file_exists( $pathsFile )) {
            $this->paths = parse_ini_file($pathsFile);
        } else {
            die("Ruta de configuración inexistente");
        }

    }

    // Evita que el objeto se pueda clonar
    public function __clone()
    {
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }

    public static function getInstance($pathFile = '')
    {
        if( !isset( Path::$instance ) ){
            self::$instance = new Path($pathFile);
        }

        return self::$instance;
    }

    public function getPath($path, $file = ''){

        if (array_key_exists($path, $this->paths )) {

            $fullPath =  $this->paths['root'] .  $this->paths[$path] . $file;

            if (file_exists( $fullPath )) {
                return $fullPath;
            }else {
                //die("Archivo inexistente:" . $fullPath);
                return null;
            }
            
        }else {
            //die("Clave de ruta inexistente");
            return null;
        }
    }
    
    

}