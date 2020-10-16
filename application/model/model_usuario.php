<?php

class Model_Usuario extends Model{
   
 	private $db;

	 public function __construct()
    {
        require_once 'modelo_conexion_base_de_datos.php';
        $db=BaseDeDatos::conectarBD();
    }

	public function loginDiscord(){

		  	session_start();
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)
		error_reporting(E_ALL);

		define('OAUTH2_CLIENT_ID', '758513164710051890');
		define('OAUTH2_CLIENT_SECRET', 'AJRw4y7CYSVxb7S6nV56w_XekKE01pKa');
		define('API_ENDPOINT', 'https://discord.com/api/v6');
		define('REDIRECT_URI', 'http://localhost/ParticaWeb/');

		$authorizeURL = 'https://discord.com/api/oauth2/authorize';
		$tokenURL = 'https://discord.com/api/oauth2/token';
		$apiURLBase = 'https://discord.com/api/users/@me';

		 $params = array(
		    'client_id' => OAUTH2_CLIENT_ID,
		    'redirect_uri' => 'http://'.$_SERVER['SERVER_NAME'].'/main/validateLogin',
		    'response_type' => 'code',
		    'scope' => 'identify email'
  		);

		  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));

		
    		$_SESSION['user_is_logged'] = false;
  			die();

	}

		public function validateLogin($code){


		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)
		error_reporting(E_ALL);

		define('OAUTH2_CLIENT_ID', '758513164710051890');
		define('OAUTH2_CLIENT_SECRET', 'AJRw4y7CYSVxb7S6nV56w_XekKE01pKa');
		define('API_ENDPOINT', 'https://discord.com/api/v6');
		define('REDIRECT_URI', 'http://localhost/ParticaWeb/');

		$authorizeURL = 'https://discord.com/api/oauth2/authorize';
		$tokenURL = 'https://discord.com/api/oauth2/token';
		$apiURLBase = 'https://discord.com/api/users/@me';

		function apiRequest($url, $post=FALSE, $headers=array()) {
		  $ch = curl_init($url);
		  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		  $response = curl_exec($ch);


		  if($post)
		    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

		  $headers[] = 'Accept: application/json';

		  if(session('user_is_logged'))
		    $headers[] = 'Authorization: Bearer ' . session('user_is_logged');

		  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		  $response = curl_exec($ch);
		  return json_decode($response);
		}

		function get($key, $default=NULL) {
		  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
		}

		function session($key, $default=NULL) {
		  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
		}

		// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
			

			  // Exchange the auth code for a token
			if ( !$_SESSION['user_is_logged'] ){
					  $token = apiRequest($tokenURL, array(
			    "grant_type" => "authorization_code",
			    'client_id' => OAUTH2_CLIENT_ID,
			    'client_secret' => OAUTH2_CLIENT_SECRET,
			    'redirect_uri' => 'http://'.$_SERVER['SERVER_NAME'].'/main/validateLogin',
			    'code' => $code,
			    'scope' => 'identify email'
			  ));

				 $_SESSION['user_is_logged'] = $token->access_token;
				
				}


			

			  		 $data = apiRequest($apiURLBase);
				 	 $email = $data->email;
				 	
				  	return $data;

				  	die(); //. $_SERVER['PHP_SELF']
				  

				  //$logout_token = $token->access_token;  //std Object

				 // header('Location: bettleList.php');
			

	}

	public function validateUserData($userData){

		$db=BaseDeDatos::conectarBD();

		$email = $userData->email;
		$username = $userData->username;

		$sql = 'SELECT * FROM users WHERE email = "'.$email.'" ';

		$result=mysqli_query($db, $sql);

		$countRow = mysqli_num_rows ($result);

		if($countRow == 0){
			
			$sql2 = 'INSERT INTO users (id, email, username, isAdmin) VALUES (NULL, "'.$email.'", "'.$username.'", 0)';

			mysqli_query($db, $sql2);

			$sql3 = 'SELECT * FROM users WHERE email = "'.$email.'" ';

			 $result2 = 	mysqli_query($db, $sql3);
			   return $result2;

		} else {
			return $result;
		}

        return $result2;

	}

	public function allBattles(){

		$db=BaseDeDatos::conectarBD();

		$sql = '	SELECT DISTINCT COUNT(su.id) as subCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id INNER JOIN submissions as su on su.battlesId = b.id GROUP BY b.id ORDER BY b.createdDate desc';

		/*$sql = 'SELECT DISTINCT COUNT(v.id) as votesCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
			FROM battles as b 
			INNER JOIN states as s on b.idStates = s.id 
			INNER JOIN submissions as su on su.battlesId = b.id
			INNER JOIN votes as v on v.submissionsId = su.id
				
			GROUP BY b.id
			ORDER BY b.createdDate desc';*/

			$result=mysqli_query($db, $sql);

		
			return $result;
		
			/*
			SELECT DISTINCT COUNT(su.id) as subCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id INNER JOIN submissions as su on su.battlesId = b.id GROUP BY b.id ORDER BY b.createdDate desc
			*/
		

	}

			public function allBattlesNoVotes(){

		$db=BaseDeDatos::conectarBD();

		/*$sql = 'SELECT  b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
			FROM battles as b 
			INNER JOIN states as s on b.idStates = s.id 
			
            WHERE NOT EXISTS
            
            (
            SELECT *
                FROM submissions as su
                WHERE b.id = su.battlesId
            )
						
			     
			  ORDER BY b.createdDate desc';*/

			 $sql='
			SELECT DISTINCT b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id WHERE NOT EXISTS ( SELECT * FROM submissions as su WHERE su.battlesId = b.id ) ORDER BY b.createdDate desc';

		/*SELECT DISTINCT  b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
						FROM battles as b 
						INNER JOIN states as s on b.idStates = s.id 
						INNER JOIN submissions as su on su.battlesId = b.id
			            WHERE NOT EXISTS
            
            (
            SELECT *
                FROM votes as v
                WHERE v.submissionsId = su.id
            )
						     
			  ORDER BY b.createdDate desc*/


			$result=mysqli_query($db, $sql);


			return $result;
		

	}

	public function indexBattles(){

	$db=BaseDeDatos::conectarBD();

	$sql = '	SELECT DISTINCT COUNT(su.id) as subCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id INNER JOIN submissions as su on su.battlesId = b.id GROUP BY b.id ORDER BY b.createdDate desc';

		$result=mysqli_query($db, $sql);

		/*$haveVotes = mysqli_num_rows ($result);

		if ($haveVotes == 0) {
			
			$sql2 = 'SELECT  b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
			FROM battles as b 
			INNER JOIN states as s on b.idStates = s.id 
			WHERE b.idStates = 1 OR b.idStates = 2 
			ORDER BY b.createdDate desc';

			$result2=mysqli_query($db, $sql2);

				return $result2;

		} else {*/
			return $result;
		//}

	}

		public function indexBattlesNoVotes(){

		$db=BaseDeDatos::conectarBD();

		/*$sql = 'SELECT  b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
			FROM battles as b 
			INNER JOIN states as s on b.idStates = s.id 
			
            WHERE NOT EXISTS
            
            (
            SELECT *
                FROM submissions as su
                WHERE b.id = su.battlesId
            )
						
		      
			  ORDER BY b.createdDate desc';*/

			 $sql='
			SELECT DISTINCT b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id WHERE NOT EXISTS ( SELECT * FROM submissions as su WHERE su.battlesId = b.id ) ORDER BY b.createdDate desc';

			$result=mysqli_query($db, $sql);


			return $result;
		

	}

	public function addBattlePublish($title, $samples, $tags, $maxVotes, $rules, $createdDate){

			$db=BaseDeDatos::conectarBD();

			$sql = 'INSERT INTO battles (id, title, rules, createdDate, idStates, samplesLink, maxVotes, tags) VALUES (NULL, "'.$title.'", "'.$rules.'", "'.$createdDate.'", 1 , "'.$samples.'", '.$maxVotes.' , "'.$tags.'" )';

		 	mysqli_query($db, $sql);

	}

	public function addBattleDraft($title, $samples, $tags, $maxVotes, $rules, $createdDate){

			$db=BaseDeDatos::conectarBD();

			$sql = 'INSERT INTO battles (id, title, rules, createdDate, idStates, samplesLink, maxVotes, tags) VALUES (NULL, "'.$title.'", "'.$rules.'", "'.$createdDate.'", 3 , "'.$samples.'", '.$maxVotes.' , "'.$tags.'" )';

		 	mysqli_query($db, $sql);
		
	}

	public function deletteBattle($battleId){

		$db=BaseDeDatos::conectarBD();

			$sql = 'DELETE FROM battles WHERE battles.id = '.$battleId.'';

			$sql2 = 'DELETE FROM submissions WHERE battlesId = '.$battleId.'';

			$sql3 = 'DELETE v FROM votes as v INNER JOIN submissions as s ON v.submissionsId = s.id WHERE s.battlesId = '.$battleId.' ';

		 	mysqli_query($db, $sql);
		 	mysqli_query($db, $sql3);
		 	mysqli_query($db, $sql2);
		 	
	}

	public function searchUser($email){

		$db=BaseDeDatos::conectarBD();

		$sql = 'SELECT * FROM users WHERE email = "'.$email.'" ';

		$result=mysqli_query($db, $sql);

		return $result;
	}

	public function searchBattle($idSubmission){

		$db=BaseDeDatos::conectarBD();

		$sql = 'SELECT * FROM submissions WHERE id = '.$idSubmission.' ';

		$result=mysqli_query($db, $sql);

		return $result;	
	}

	public function searchBattleWhitId($battleId){

		$db=BaseDeDatos::conectarBD();

		$sql = 'SELECT idStates FROM battles WHERE id = '.$battleId.' ';

		$result=mysqli_query($db, $sql);

		 $rows=mysqli_fetch_assoc($result);

		  $idStates = ($rows['idStates']);

		
		return $idStates;	
	}



	public function validateVote($idUser,$idSubmission,$battleId){
		
		 $db=BaseDeDatos::conectarBD();

		$maxVotes = 'SELECT b.maxVotes FROM battles as b
						INNER JOIN submissions as s 
						on s.battlesId = b.id
						INNER JOIN votes as v
						on v.submissionsId = s.id
						WHERE s.battlesId = '.$battleId.'
						LIMIT 1';

		$maxVotesResult=mysqli_query($db, $maxVotes);	
		
		$rows=mysqli_fetch_assoc($maxVotesResult);

         $maxVotes = ($rows['maxVotes']); //MAXIMO VOTOS BATALLA
   
////////////////////////////////////////////////////////////////////////

		$votesUser = 'SELECT * FROM votes as v
			INNER JOIN submissions as s 
			on v.submissionsId = s.id
			WHERE v.userId = '.$idUser.' AND s.battlesId  = '.$battleId.'';		
		

		$votesUserResult=mysqli_query($db, $votesUser);	

		$countVotes = mysqli_num_rows ($votesUserResult); //VALIDA LA CANTIDAD VOTOS USER EN LA BATALLA


		if ($countVotes == 0) { //TODAVIA NO TIENE VOTOS

				$sql2 = 'INSERT INTO votes (id, userId, submissionsId) VALUES (NULL, '.$idUser.', '.$idSubmission.')';

				

				mysqli_query($db, $sql2);
				echo "Vote Inserted";
			

		} elseif($countVotes > 0) { //OSEA QUE YA VOTO

			 if ($countVotes < $maxVotes ) {
			 	$sql3 = 'INSERT INTO votes (id, userId, submissionsId) VALUES (NULL, '.$idUser.', '.$idSubmission.')';

			 		

				mysqli_query($db, $sql3);
				echo "Vote Inserted";
				//return "true";
			
			 } else {
			 	echo "Your maximum votes is complete";
			 	//return "false";

			 }

		} else {
			echo "error";
		}


	}

	public function validateIfCanReVote($idUser, $battleId){

		 $db=BaseDeDatos::conectarBD();

		$maxVotes = 'SELECT *  FROM battles WHERE id = '. $battleId.'';

		$maxVotesResult=mysqli_query($db, $maxVotes);	
		
		$rows=mysqli_fetch_assoc($maxVotesResult);

         $maxVotes = ($rows['maxVotes']); //MAXIMO VOTOS BATALLA


		$votesUser = 'SELECT * FROM votes as v
			INNER JOIN submissions as s 
			on v.submissionsId = s.id
			WHERE v.userId = '.$idUser.' AND s.battlesId  = '.$battleId.'';		


		$votesUserResult=mysqli_query($db, $votesUser);	


		$countVotesUserResult = mysqli_num_rows ($votesUserResult); //CANTIDAD VOTOS USER EN LA BATALLA

		if($countVotesUserResult <  $maxVotes){
			return "true";
		} elseif ($countVotesUserResult >=  $maxVotes) {
			return "false";
		}

	}

	public function deleteVoteOfUser($idUser, $battleId, $submissionsId){

		$db=BaseDeDatos::conectarBD();

		$sql='SELECT  v.id as idVotes
				FROM battles as b 
				INNER JOIN submissions as s 
				on s.battlesId = b.id
				INNER JOIN votes as v 
				on v.submissionsId = s.id
				WHERE v.userId = '.$idUser.' AND s.id = '.$submissionsId.'
				ORDER BY v.id asc ';


		$result = mysqli_query($db, $sql);

		$rows=mysqli_fetch_assoc($result);

         $idVotes = ($rows['idVotes']); 

         $deleteSql = 'DELETE FROM votes WHERE votes.id = '.$idVotes.'';

         mysqli_query($db, $deleteSql);
				
	}

	public function finishBattle($battleId){
		
		$db=BaseDeDatos::conectarBD();

		$sql = 'UPDATE battles SET idStates = 2 WHERE battles.id = '.$battleId.';'; 
		
		mysqli_query($db, $sql);
	}

		public function publishBattle($battleId){
		
		$db=BaseDeDatos::conectarBD();

		$sql = 'UPDATE battles SET idStates = 1 WHERE battles.id = '.$battleId.';'; 

		
		mysqli_query($db, $sql);
	}

	public function oneBattles($battleId){

		$db=BaseDeDatos::conectarBD();

			$sql = 'SELECT * FROM battles WHERE id = '.$battleId.' ';

		$result=mysqli_query($db, $sql);

		return $result;
	}

	public function battleSubs($battleId){

		$db=BaseDeDatos::conectarBD();

		$sql = 'SELECT s.id as id, s.battlesId as idBattle, s.nickname as nickname, s.soundcloudLink as soundcloudLink, s.voteCount as voteCount, b.idStates as idStates FROM submissions as s INNER JOIN battles as b on b.id = s.battlesId WHERE battlesId = '.$battleId.' ORDER BY s.nickname asc';
		
		$result=mysqli_query($db, $sql);

		return $result;
	}

	public function addSubmissions($nickname, $iframeCodeValidated, $idBattle){

		$db=BaseDeDatos::conectarBD();

		$number = count($nickname);  

		

		if ($number > 0) {
			
			 for($i=0; $i<$number; $i++) {

			 		
			 		$sql= "INSERT INTO submissions (id, battlesId, nickname, soundcloudLink, voteCount) VALUES (NULL, ".$idBattle.", '".mysqli_real_escape_string($db, $nickname[$i])."', '".mysqli_real_escape_string($db, $iframeCodeValidated[$i])."', NULL);";

			 	


                mysqli_query($db, $sql); 
             
			 	

			 }
			   // echo "Data Inserted"; 
			
		}
	}

	public function votosGrafico($battleId){

			$db=BaseDeDatos::conectarBD();
		
		$sql='SELECT s.nickname as nickname, COUNT(v.id) as votes FROM battles as b INNER JOIN submissions as s ON s.battlesId = b.id INNER JOIN votes as v ON v.submissionsId = s.id 
			WHERE s.battlesId = '.$battleId.'
			GROUP BY s.nickname';
	
			//echo $sql;
		$result=mysqli_query($db, $sql);
		 
		 return $result;	

	}

	public function createSoundcloudIframe($soundcloudLink){

			$db=BaseDeDatos::conectarBD();

			//$array_num = count($soundcloudLink);

			//$iframeCode = array();

			//for ($i=0; $i < $array_num; ++$i) { 
                   
              $resolveUrl = "https://api.soundcloud.com/resolve.json?url=".$soundcloudLink."&client_id=d438c4a17e1716c6db0c5fbefc2c8876";

			$response = $this->apiRequest($resolveUrl);

			$iframeCode = '<iframe width="100%" height="150px" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url='. urlencode($response->uri) .'&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>';

			//echo $iframeCodeUrl;

			 //$iframeCode[] = $iframeCodeUrl;  
			//array_push($iframeCode, "".$iframeCodeUrl."");


               //}

               //print_r($iframeCode);
	
			return $iframeCode;
          
		
	}


				function apiRequest($url, $post=FALSE, $headers=array()) {
		  $ch = curl_init($url);
		  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		  $response = curl_exec($ch);


		  if($post)
		    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

		  $headers[] = 'Accept: application/json';

		  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		  $response = curl_exec($ch);
		  return json_decode($response);
		}

		function userVotes($idUser){

			$db=BaseDeDatos::conectarBD();
		
		$sql='SELECT * FROM votes WHERE userId = '.$idUser.' ';
	
			//echo $sql;
		$result=mysqli_query($db, $sql);
		 
		 return $result;	

		}

} 

