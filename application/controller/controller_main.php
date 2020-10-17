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
            $validateState = $usuario->battleSubs($battleId);
            $user = $usuario->searchUser($email);

            $rows=mysqli_fetch_assoc($user);

            $idUser = ($rows['id']);

            $idBattle = $battleId;
         
            $votesGrafic = $usuario->votosGrafico($battleId);
           
            $vote =  $usuario->validateIfCanReVote($idUser, $idBattle);

            $userVotes =  $usuario->userVotes($idUser);
		 	
            $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic,$validateState, $userVotes);
    }	

      function vote(){

            $idSubmission = $_GET['idSubmission']; //ID SUBMISSION DE VOTO
            $email = $_GET['email']; // EMAIL DE EL QUE VOTA
            $usuario = new Model_Usuario();
            
            $user = $usuario->searchUser($email); //BUSCA EL USER QUE VOTA

          
            $rows=mysqli_fetch_assoc($user);

            $idUser = ($rows['id']); //USER ID


             $userVotes =  $usuario->userVotes($idUser);
        

            $idSubmissionValidate = $usuario->searchBattle($idSubmission); //BUSCA LA SUBBMISSION

            $rows3=mysqli_fetch_assoc($idSubmissionValidate);

            $idSubmissionValidate2 = ($rows3['id']); //USER ID

            if ( $idSubmissionValidate2 == null ||  $idUser == null) {
              echo'<script type="text/javascript">
                    alert("Error");
                    </script>';
            $battles = $usuario->indexBattles();
            $battlesNoVotes = $usuario->indexBattlesNoVotes();
            $this->view->generateSt('index.php', $battles, $battlesNoVotes);

            } else {

                $battle = $usuario->searchBattle($idSubmission); //BUSCA LA BATALLA POR ID SUBMISSION

                $rows2=mysqli_fetch_assoc($battle);

                $idBattle = ($rows2['battlesId']); //TRAEL EL ID DE BATALLA DE LA SUBMISSION 

                $battleId = $idBattle;

                 $battles = $usuario->oneBattles($battleId);
                
                $battleSubs = $usuario->battleSubs($battleId); 

                 $idStates =  $usuario->searchBattleWhitId($battleId); 

                
                $votesGrafic = $usuario->votosGrafico($battleId); //TRAE LOS VOTOS PARA EL GRAFICO
                //SI NO HAY VOTOS NO TRAE NADA
        

                 $validateState = $usuario->battleSubs($battleId); //TRAE LOS SUBSMISSIONS PARA VALIDAR EL ESTADO DE LA BATALLA



                 if ($idStates == 2 || $idStates == 3  ) {
                   
                  
                    echo'<script type="text/javascript">
                          alert("The battle is Finished");
                          </script>';
                      /*$battles = $usuario->allBattles();
                      $battlesNoVotes = $usuario->allBattlesNoVotes();
                   $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);*/
                   $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState,  $userVotes);
                 } else {


                      $usuario->validateVote($idUser,$idSubmission, $battleId); //VALIDA EL VOTO Y VOTA

                    
                     $vote =  $usuario->validateIfCanReVote($idUser, $battleId);

                       if ($vote == "false") {
                          
                             /* echo'<script type="text/javascript">
                              alert("Thanks for vote, You cannot vote again");
                              </script>';*/
                                /*$battles = $usuario->allBattles();
                            $battlesNoVotes = $usuario->allBattlesNoVotes();
                         $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);*/
                              $this->view->generateSt('battle.php',$battles, $battleSubs, $vote,$votesGrafic, $validateState,  $userVotes);
                      } else {
                      
                           /* echo'<script type="text/javascript">
                              alert("You can vote more times");
                              </script>';*/
                                /*$battles = $usuario->allBattles();
                      $battlesNoVotes = $usuario->allBattlesNoVotes();
                   $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);*/
                              $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState, $userVotes);
                      }

                 }

            }
          

            
           
    }   

    function changeVote(){

        $usuario = new Model_Usuario();
        $battleId = $_GET['battleId'];
        $email = $_GET['email'];
        $submissionsId = $_GET['submissionsId'];

        //free result y store result.

        $user = $usuario->searchUser($email);

        $rows=mysqli_fetch_assoc($user);

        $idUser = ($rows['id']);
  
        $userVotes =  $usuario->userVotes($idUser); //
       
        $idStates =  $usuario->searchBattleWhitId($battleId); 

        $votesGrafic = $usuario->votosGrafico($battleId);

        $validateState = $usuario->battleSubs($battleId); //Trae las submissions

      
        $idBattle =  $battleId;


        $vote =  $usuario->validateIfCanReVote($idUser, $idBattle);


        $battles = $usuario->oneBattles($battleId);


        $battleSubs = $usuario->battleSubs($battleId);


         if ($idStates == 2 || $idStates == 3  ) {

        

   
              echo'<script type="text/javascript">
                alert("The battle is Finished");
                </script>';
              /*    $battles = $usuario->allBattles();
        $battlesNoVotes = $usuario->allBattlesNoVotes();
     $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);*/
                $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState, $userVotes);

         } else {

            $usuario->deleteVoteOfUser($idUser,  $battleId,  $submissionsId);
              /*echo'<script type="text/javascript">
                alert("Now you can Vote again");
                </script>';*/
                 /* $battles = $usuario->allBattles();
                  $battlesNoVotes = $usuario->allBattlesNoVotes();
               $this->view->generateSt('battleList.php',$battles, $battlesNoVotes);*/
                $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState, $userVotes);

         }
        

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
    $usuario = new Model_Usuario();
    $nicknameValidated = [];
    $iframeCodeValidated = [];
    $idBattle = $_POST["idBattle"];

    $array_num = count($_POST["soundcloudLink"]);

    for ($i=0; $i < $array_num; ++$i) { 
        if($_POST["nickname"][$i] != null && $_POST["soundcloudLink"][$i] != null) {
            $iframeCode = $usuario->createSoundcloudIframe($_POST["soundcloudLink"][$i]);
            array_push($iframeCodeValidated,$iframeCode);
            array_push($nicknameValidated,$_POST["nickname"][$i]);
        }
    }



    if (count($iframeCodeValidated)) {
        $usuario->addSubmissions($nicknameValidated, $iframeCodeValidated,  $idBattle);
    } else {
        header("JSON");
        echo json_encode(["msg" => "Error"]);
        die();
    }

    
}

   /* function addSubmission(){

       $usuario = new Model_Usuario();
       $nickname = $_POST["nickname"];
       $soundcloudLink = $_POST["soundcloudLink"];

       $idBattle = $_POST["idBattle"];

        $iframeCodeArray = array();

       $array_num = count($soundcloudLink);

    

            for ($i=0; $i < $array_num; ++$i) { 
               
            $iframeCode = $usuario->createSoundcloudIframe($soundcloudLink[$i]);
             array_push($iframeCodeArray,$iframeCode);
                //$iframeCode[] = $usuario->createSoundcloudIframe($soundcloudLink[$i]);
               
          }

           $usuario->addSubmissions($nickname, $iframeCodeArray,  $idBattle);

    }*/

    function getVotesForBattle(){
         $usuario = new Model_Usuario();
         $battleId = $_GET['id'];
         echo $battleId;
    }
    		
        
    }
