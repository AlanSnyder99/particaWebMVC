<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    private $data;

    public function __construct() {
        parent::__construct();
        $_SESSION["user_is_logged"] = isset($_SESSION["user_is_logged"]) ? $_SESSION["user_is_logged"] : null;
        $this->load->model('user');
        $this->data['battles'] = $this->user->indexBattles();
        $this->data['battlesNoVotes'] = $this->user->indexBattlesNoVotes();
    }

    public function index() {
        $this->layout->view('index', $this->data);
    }

    public function login(){
        $authorizeURL = 'https://discordapp.com/api/oauth2/authorize';

        $params = array(
            'client_id' => OAUTH2_CLIENT_ID,
            'redirect_uri' => base_url('main/validateLogin'),
            'response_type' => 'code',
            'scope' => 'identify email'
        );

        header('Location: ' . $authorizeURL . '?' . http_build_query($params));

        $_SESSION['user_is_logged'] = false;
        die();
    }

    public function validateLogin(){
        $tokenURL = 'https://discord.com/api/oauth2/token';
        $apiURLBase = 'https://discord.com/api/users/@me';

        if ( !$_SESSION['user_is_logged'] ) {
            $token = apiRequest($tokenURL, array(
                "grant_type" => "authorization_code",
                'client_id' => OAUTH2_CLIENT_ID,
                'client_secret' => OAUTH2_CLIENT_SECRET,
                'redirect_uri' => base_url('main/validateLogin'),
                'code' => $_GET['code'],
                'scope' => 'identify email'
            ));
            $_SESSION['user_is_logged'] = $token->access_token;
        }

        $data = apiRequest($apiURLBase);
        $email = $data->email;

        $usuarioFinal = $this->user->validateUserData($data);
        
        $email = $usuarioFinal->email;
        $username = $usuarioFinal->username;
        $isAdmin = $usuarioFinal->isAdmin;
        
        $_SESSION["isAdmin"] = $isAdmin;
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email;

        switch ($isAdmin){
            case 1:
                redirect(base_url('main/battleList'));
                break;
            case 0:
                $this->layout->view('index', $this->data);
                break;
        }
   }
   
    public function logout(){
        $_SESSION["isAdmin"] = null;
        $_SESSION['user_is_logged'] = false;
        session_destroy();
        redirect(base_url());
    }

    public function newBattle(){
        if ($_SESSION["isAdmin"] == 0 || $_SESSION['user_is_logged'] == false ) redirect(base_url());
        $this->layout->view('newBattle');
    }

    public function battleList(){
        $this->layout->view('battleList', $this->data);
    }

    public function getVotesForBattle(){
        echo $_GET['id'];
    }

    public function deleteBattle(){
        $this->user->deleteBattle($_GET['id']);
        redirect(base_url('main/battleList'));
    }

    public function finishBattle(){
        $this->user->finishBattle($_GET['id']);
        redirect(base_url('main/battleList'));
    }

    public function publishBattle(){
        $this->user->publishBattle($_GET['id']);
        redirect(base_url('main/battleList'));
    }

    public function addSubmission(){
        $nicknameValidated = [];
        $iframeCodeValidated = [];
        $idBattle = $_POST["idBattle"];

        $array_num = count($_POST["soundcloudLink"]);

        for ($i=0; $i < $array_num; ++$i) { 
            if($_POST["nickname"][$i] != null && $_POST["soundcloudLink"][$i] != null) {
                $iframeCode = createSoundcloudIframe($_POST["soundcloudLink"][$i]);
                array_push($iframeCodeValidated,$iframeCode);
                array_push($nicknameValidated,$_POST["nickname"][$i]);
            }
        }

        if (count($iframeCodeValidated)) {
            $this->user->addSubmissions($nicknameValidated, $iframeCodeValidated,  $idBattle);
        } else {
            header("JSON");
            echo json_encode(["msg" => "Error"]);
            die();
        }        
    }

    public function addBattle(){
        $title = $_POST['title'];
        $samples = $_POST['samples'];
        $tags = $_POST['tags'];
        $maxVotes = $_POST['maxVotes'];
        $rules = $_POST['rules'];
        $createdDate = date('Y-m-d');

        if ($_POST['action'] == 'publish') {
            $this->user->addBattlePublish($title, $samples, $tags, $maxVotes, $rules, $createdDate);
            redirect(base_url('battleList'));
        } elseif ($_POST['action'] == 'draft') {
            $this->user->addBattleDraft($title, $samples, $tags, $maxVotes, $rules, $createdDate);
            redirect(base_url('battleList'));
        } else {
            redirect(base_url('newBattle'));
        }
    }

    function joinBattle(){
        $battleId = $_GET['id'];
        $email = $_GET['email'];
        $validateState = $this->user->battleSubs($battleId);
        $user = $this->user->searchUser($email);
        $idUser = $user->id;
        $votesGrafic = $this->user->votosGrafico($battleId);
        $vote =  $this->user->validateIfCanReVote($idUser, $battleId);
        $userVotes =  $this->user->userVotes($idUser);
        $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState, $userVotes);
    }


    public function vote(){
            $idSubmission = $_GET['idSubmission']; //ID SUBMISSION DE VOTO
            $email = $_GET['email']; // EMAIL DE EL QUE VOTA
            $usuario = new Model_Usuario();
            $user = $usuario->searchUser($email); //BUSCA EL USER QUE VOTA

            $rows = mysqli_fetch_assoc($user);
            mysqli_free_result($user);

            $idUser = $rows['id']; //USER ID

            $userVotes =  $usuario->userVotes($idUser);
            // print_r($userVotes);
            // die();

            $idSubmissionValidate = $usuario->searchBattle($idSubmission); //BUSCA LA SUBBMISSION

            $rows3 = mysqli_fetch_assoc($idSubmissionValidate);
            mysqli_free_result($idSubmissionValidate);

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

                $idStates =  $usuario->searchBattleWithId($battleId); 

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

    public function changeVote(){

        $usuario = new Model_Usuario();
        $battleId = $_GET['battleId'];
        $email = $_GET['email'];
        $submissionsId = $_GET['submissionsId'];

        //free result y store result.

        $user = $usuario->searchUser($email);

        $rows=mysqli_fetch_assoc($user);

        $idUser = ($rows['id']);
  
        $userVotes =  $usuario->userVotes($idUser); //
       
        $idStates =  $usuario->searchBattleWithId($battleId); 

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
            $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState, $userVotes);

         } else {
            $usuario->deleteVoteOfUser($idUser,  $battleId,  $submissionsId);
            $this->view->generateSt('battle.php',$battles, $battleSubs, $vote, $votesGrafic, $validateState, $userVotes);

         }
    }




}
