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

		$sql = 'SELECT DISTINCT COUNT(v.id) as votesCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
			FROM battles as b 
			INNER JOIN states as s on b.idStates = s.id 
			INNER JOIN submissions as su on su.battlesId = b.id
			INNER JOIN votes as v on v.submissionsId = su.id
				/*WHERE b.idStates = 1 OR b.idStates = 2 */
			GROUP BY b.id
			ORDER BY b.createdDate desc';

			$result=mysqli_query($db, $sql);

		
			return $result;
		

		

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
			SELECT DISTINCT  b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
						FROM battles as b 
						INNER JOIN states as s on b.idStates = s.id 
						INNER JOIN submissions as su on su.battlesId = b.id
			            WHERE NOT EXISTS
            
            (
            SELECT *
                FROM votes as v
                WHERE v.submissionsId = su.id
            )
						     
			  ORDER BY b.createdDate desc';

		

			$result=mysqli_query($db, $sql);


			return $result;
		

	}

	public function indexBattles(){

	$db=BaseDeDatos::conectarBD();

		$sql = 'SELECT DISTINCT COUNT(v.id) as votesCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
			FROM battles as b 
			INNER JOIN states as s on b.idStates = s.id 
			INNER JOIN submissions as su on su.battlesId = b.id
			INNER JOIN votes as v on v.submissionsId = su.id
			/*WHERE b.idStates = 1 OR b.idStates = 2 */
			GROUP BY b.id
			ORDER BY b.createdDate desc';

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
			SELECT DISTINCT  b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state  
						FROM battles as b 
						INNER JOIN states as s on b.idStates = s.id 
						INNER JOIN submissions as su on su.battlesId = b.id
			            WHERE NOT EXISTS
            
            (
            SELECT *
                FROM votes as v
                WHERE v.submissionsId = su.id
            )
						     
			  ORDER BY b.createdDate desc';

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

		 	mysqli_query($db, $sql);
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

	public function validateVote($idUser,$idSubmission,$idBattle){
		
		 $db=BaseDeDatos::conectarBD();
		//hacer count de los votos para ver si el user puede volver a votar con respecto a el maxVotes
		//ver si el user ya voto y puede volver a votar
		//generar voto
		//en caso de error devolver true o false

		$maxVotes = 'SELECT b.maxVotes FROM battles as b
						INNER JOIN submissions as s 
						on s.battlesId = b.id
						INNER JOIN votes as v
						on v.submissionsId = s.id
						WHERE s.battlesId = '.$idBattle.'
						LIMIT 1';

		$maxVotesResult=mysqli_query($db, $maxVotes);	
		
		$rows=mysqli_fetch_assoc($maxVotesResult);

         $maxVotes = ($rows['maxVotes']); //MAXIMO VOTOS BATALLA
   

		$votesUser = 'SELECT * FROM battles as b
			INNER JOIN submissions as s 
			on s.battlesId = b.id
			INNER JOIN votes as v
			on v.submissionsId = s.id
			WHERE v.userId = '.$idUser.' AND s.battlesId  = '.$idBattle.'';		
		

		$votesUserResult=mysqli_query($db, $votesUser);	

		$countVotesUserResult = mysqli_num_rows ($votesUserResult); //CANTIDAD VOTOS USER EN LA BATALLA


		$sql = 'SELECT * FROM votes as v
				INNER JOIN submissions as s on s.id = v.submissionsId
				WHERE v.userId = 6 AND s.battlesId = '.$idBattle.'';

		$votosDelUser=mysqli_query($db, $sql);

		$countVotes = mysqli_num_rows ($votosDelUser);

		if ($countVotes == 0) {

				$sql2 = 'INSERT INTO votes (id, userId, submissionsId) VALUES (NULL, '.$idUser.', '.$idSubmission.')';

				mysqli_query($db, $sql2);
				echo "voto añadido";
				return "true";

		} elseif($countVotes > 0) {

			 if ($countVotesUserResult < $maxVotes ) {
			 	$sql2 = 'INSERT INTO votes (id, userId, submissionsId) VALUES (NULL, '.$idUser.', '.$idSubmission.')';

				mysqli_query($db, $sql2);
				echo "voto añadido";
				return "true";
			
			 } else {
			 	echo "no se puede votar mas";
			 	return "false";

			 }

		} else {
			echo "error";
		}


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

		$sql = 'SELECT s.id as id, s.battlesId as idBattle, s.userId as userId, s.nickname as nickname, s.soundcloudLink as soundcloudLink, s.voteCount as voteCount, b.idStates as idStates FROM submissions as s INNER JOIN battles as b on b.id = s.battlesId WHERE battlesId = '.$battleId.' ';

		$result=mysqli_query($db, $sql);

		return $result;
	}

	public function addSubmissions($nickname, $soundcloudLink, $idBattle){

		$db=BaseDeDatos::conectarBD();

		$number = count($nickname);  

		

		if ($number > 0) {
			
			 for($i=0; $i<$number; $i++) {

			 		
			 		$sql= "INSERT INTO submissions (id, battlesId, userId, nickname, soundcloudLink, voteCount) VALUES (NULL, ".$idBattle.", NULL, '".mysqli_real_escape_string($db, $nickname[$i])."', '".mysqli_real_escape_string($db, $soundcloudLink[$i])."', NULL);";

			 	


                mysqli_query($db, $sql); 
             
			 	

			 }
			    echo "Data Inserted"; 
			
		} else {
				echo "No Submissions";
		}
	}

} 

