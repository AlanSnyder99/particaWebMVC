<?php

include 'application/model/model_usuario.php';




class Controller_Main extends Controller{
    
     

    function index(){
    	 $usuario = new Model_Usuario();
    	$battles = $usuario->indexBattles();
        $battlesNoVotes = $usuario->indexBattlesNoVotes();
        $this->view->generateSt('index.php', $battles, $battlesNoVotes);
    }

    function login(){

    
    	$usuario = new Model_Usuario();
    	$usuario->loginDiscord();

    }

    function validateLogin(){

    	$code = $_GET['code'];
    	$usuario = new Model_Usuario();
    	$userData = $usuario->validateLogin($code);
		$usuarioFinal = $usuario->validateUserData($userData);
    	
 		$rows=mysqli_fetch_assoc($usuarioFinal);
 		
        $email = ($rows['email']);
 		$username = ($rows['username']);
 		$isAdmin = ($rows['isAdmin']);
 		
 		$_SESSION["isAdmin"] = $isAdmin;
 		$_SESSION["username"] = $username;
        $_SESSION["email"] = $email;

 		switch ($isAdmin){

 				case 1:
 				$battles = $usuario->allBattles();
				$battlesNoVotes = $usuario->allBattlesNoVotes();
                  $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);
				break;

			case 0:
				$usuario = new Model_Usuario();
		    	$battles = $usuario->indexBattles();
		          $battlesNoVotes = $usuario->indexBattlesNoVotes();
                 $this->view->generateSt('index.php', $battles, $battlesNoVotes);
				break;	
			
 		}


   }
    
   
 		 function logout(){
 			$_SESSION["isAdmin"] = null;
    	$_SESSION['user_is_logged'] = false;
		session_destroy();
		$usuario = new Model_Usuario();
    	$battles = $usuario->indexBattles();
		$battlesNoVotes = $usuario->indexBattlesNoVotes();
        $this->view->generateSt('index.php', $battles, $battlesNoVotes);
    }

    function newBattle(){
    	 	$this->view->generateSt('newBattle.php');
    }

    function battleList(){
    	$usuario = new Model_Usuario();
    	$battles = $usuario->allBattles();
        $battlesNoVotes = $usuario->allBattlesNoVotes();
		 $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);
    }

    function addBattle(){

    	$usuario = new Model_Usuario();

    	 $title = $_POST['title'];
    	 $samples = $_POST['samples'];
    	 $tags = $_POST['tags'];
   	     $maxVotes = $_POST['maxVotes'];
         $rules = $_POST['rules'];
         $createdDate = date('Y-m-d');

    	if ($_POST['action'] == 'publish') {
		   
		   $usuario->addBattlePublish($title, $samples, $tags, $maxVotes, $rules, $createdDate);
		   	$battles = $usuario->allBattles();
		     $battlesNoVotes = $usuario->allBattlesNoVotes();
              $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);

		} else if ($_POST['action'] == 'draft') {
		     
		     $usuario->addBattleDraft($title, $samples, $tags, $maxVotes, $rules, $createdDate);
		     $battles = $usuario->allBattles();
		      $battlesNoVotes = $usuario->allBattlesNoVotes();
                 $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);

		} else {
		 
		  $this->view->generateSt('newBattle.php');

		}


    }

    function joinBattle(){

    		$battleId = $_GET['id'];
            $email = $_GET['email'];
    	  	$usuario = new Model_Usuario();
            //$validationVote = $usuario->validateIfUserCanVote($email);
    	  	$battles = $usuario->oneBattles($battleId);
            $battleSubs = $usuario->battleSubs($battleId);

		 	$this->view->generateSt('battle.php',$battles, $battleSubs);
    }	

      function vote(){

            $idSubmission = $_GET['idSubmission'];
            $email = $_GET['email'];
            $usuario = new Model_Usuario();
            
            $user = $usuario->searchUser($email);

            $rows=mysqli_fetch_assoc($user);

            $idUser = ($rows['id']);

            $battle = $usuario->searchBattle($idSubmission);

            $rows2=mysqli_fetch_assoc($battle);

            $idBattle = ($rows2['battlesId']);

            $battleId = $idBattle;

            $vote = $usuario->validateVote($idUser,$idSubmission, $idBattle);

            if ($vote == "true") {
                     $battles = $usuario->oneBattles($battleId);
                    $battleSubs = $usuario->battleSubs($battleId);
                     echo'<script type="text/javascript">
                    alert("Thanks for voting!");
                    </script>';
                    $this->view->generateSt('battle.php',$battles, $battleSubs);
            
            } elseif ($vote == "false"){

                    $battles = $usuario->oneBattles($battleId);
                     $battleSubs = $usuario->battleSubs($battleId);
                      echo'<script type="text/javascript">
                    alert("You wanna change your vote?");
                    </script>';
                     $this->view->generateSt('battle.php',$battles, $battleSubs);
            }

            /*$battleId = $idBattle ;
            $battles = $usuario->oneBattles($battleId);
            $battleSubs = $usuario->battleSubs($battleId);

           
            $this->view->generateSt('battle.php',$battles, $battleSubs);*/

        

        

            

           
    }   

    function finishBattle(){
			$battleId = $_GET['id'];
			$usuario = new Model_Usuario();
    	  	//$battles = $usuario->oneBattles();
		 	$usuario->finishBattle($battleId);
		 	 $battles = $usuario->allBattles();
            $battlesNoVotes = $usuario->allBattlesNoVotes();
            $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);
    }

    function publishBattle(){
			$battleId = $_GET['id'];
			$usuario = new Model_Usuario();
    	  	//$battles = $usuario->oneBattles();
		 	$usuario->publishBattle($battleId);
		 	$battles = $usuario->allBattles();
            $battlesNoVotes = $usuario->allBattlesNoVotes();
            $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);
    }

       function deleteBattle(){
            $battleId = $_GET['id'];
            $usuario = new Model_Usuario();
            $usuario->deletteBattle($battleId);
           $battles = $usuario->allBattles();
            $battlesNoVotes = $usuario->allBattlesNoVotes();
            $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);
    }

    function addSubmission(){
       $nickname = $_POST["nickname"];
       $soundcloudLink = $_POST["soundcloudLink"];
       $idBattle = $_POST["idBattle"];

        $usuario = new Model_Usuario();
        $usuario->addSubmissions($nickname, $soundcloudLink,  $idBattle);
    }
    		
        
    }
