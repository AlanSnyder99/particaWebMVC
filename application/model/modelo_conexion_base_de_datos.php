<?php

	class BaseDeDatos extends Model{
		static function  conectarBD(){
		
			$server="localhost";
			$usuario="root";
			$clave="";
			$baseDeDatos="particadb";

			$conexion=mysqli_connect($server, $usuario, $clave, $baseDeDatos) or die("Error al conectar a la base de datos");
			$conexion->query("SET NAMES UTF8");
  			$conexion->query("SET CHARACTER SET utf8");

		/*
			$server="localhost";
			$usuario="root";
			$clave="";
			$baseDeDatos="particadb";

			$server="gm.itecno.com.ar";
			$usuario="alansnyder";
			$clave="q1w2e3r4";
			$baseDeDatos="particabb";
			*/

			return $conexion;
		}
		
		
	}