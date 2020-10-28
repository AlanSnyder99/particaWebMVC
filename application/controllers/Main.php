<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

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
            redirect(base_url('main/battleList'));
        } elseif ($_POST['action'] == 'draft') {
            $this->user->addBattleDraft($title, $samples, $tags, $maxVotes, $rules, $createdDate);
            redirect(base_url('main/battleList'));
        } else {
            redirect(base_url('main/newBattle'));
        }
    }

    public function joinBattle(){
        $battleId = $_GET['id'];
        $email = $_GET['email'];
        $this->data["battleSubs"] = $this->user->battleSubs($battleId);
        $idUser = $this->user->searchUser($email)->id;
        $this->data["votesGrafic"] = $this->user->votosGrafico($battleId);
        $this->data["vote"] =  $this->user->validateIfCanReVote($idUser, $battleId);
        $this->data["userVotes"] =  $this->user->userVotes($idUser);
        $this->layout->view('battle', $this->data);
    }


    public function vote(){
            $idSubmission = $_GET['idSubmission'];
            $email = $_GET['email'];
            $idUser = $this->user->searchUser($email)->id;

            $battle = $this->user->searchBattle($idSubmission);
            $idSubmissionValidate = $battle->id;

            if ( $idSubmissionValidate == null ||  $idUser == null) {
                echo'<script type="text/javascript">alert("Error");</script>';
                redirect(base_url('main/index'));
            } else {
                $battleId = $battle->battlesId;
                
                if (in_array($this->data["idStates"], [2,3])) {
                    echo '<script type="text/javascript">alert("The battle is Finished");</script>';
                } else {
                    $this->user->validateVote($idUser,$idSubmission,$battleId);

                    $this->data["vote"] = $this->user->validateIfCanReVote($idUser,$battleId);
                }
                redirect(base_url('main/joinBattle?id='.$battleId."&email=".$email));
            }
    }   

    public function changeVote(){
        $battleId = $_GET['battleId'];
        $email = $_GET['email'];
        $submissionsId = $_GET['submissionsId'];

        $idUser = $this->user->searchUser($email)->id;
        if (in_array($this->data["idStates"], [2,3])) {
            echo '<script type="text/javascript">alert("The battle is Finished");</script>';
        } else {
            $this->user->deleteVoteOfUser($idUser,$battleId,$submissionsId);
        }
        redirect(base_url('main/joinBattle?id='.$battleId."&email=".$email));
    }
}
