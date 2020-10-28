<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function allBattles(){
        $sql = 'SELECT DISTINCT COUNT(su.id) as subCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id INNER JOIN submissions as su on su.battlesId = b.id GROUP BY b.id ORDER BY b.createdDate desc';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function allBattlesNoVotes(){
        $sql='SELECT DISTINCT b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id WHERE NOT EXISTS ( SELECT * FROM submissions as su WHERE su.battlesId = b.id ) ORDER BY b.createdDate desc';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function indexBattles(){
        $sql = 'SELECT DISTINCT COUNT(su.id) as subCount , b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id INNER JOIN submissions as su on su.battlesId = b.id GROUP BY b.id ORDER BY b.createdDate desc';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function indexBattlesNoVotes(){
        $sql = 'SELECT DISTINCT b.id as idBattle, b.title as title, b.rules as rules, b.createdDate as createdDate, b.idStates as idStates, b.samplesLink as samplesLink, b.maxVotes as maxVotes, b.tags as tags, s.state as state FROM battles as b INNER JOIN states as s on b.idStates = s.id WHERE NOT EXISTS ( SELECT * FROM submissions as su WHERE su.battlesId = b.id ) ORDER BY b.createdDate desc';

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function validateUserData($userData){
        $email = $userData->email;
        $username = $userData->username;

        $sqlSelect = 'SELECT * FROM users WHERE email = "'.$email.'"';
        $query = $this->db->query($sqlSelect);

        if($query->num_rows() == 0){
            $sqlInsert = 'INSERT INTO users (id, email, username, isAdmin) VALUES (NULL, "'.$email.'", "'.$username.'", 0)';
            $this->db->query($sqlInsert);

            $query = $this->db->query($sqlSelect);
        }
        return $query->row();
    }

    public function deleteBattle($battleId){
        $sql = 'DELETE FROM battles WHERE battles.id = '.$battleId.'';
        $this->db->query($sql);

        $sql = 'DELETE FROM submissions WHERE battlesId = '.$battleId.'';
        $this->db->query($sql);

        $sql = 'DELETE v FROM votes as v INNER JOIN submissions as s ON v.submissionsId = s.id WHERE s.battlesId = '.$battleId.' ';
        $this->db->query($sql);
    }

    public function finishBattle($battleId){
        $sql = 'UPDATE battles SET idStates = 2 WHERE battles.id = '.$battleId.';';
        $this->db->query($sql);
    }

    public function publishBattle($battleId){
        $sql = 'UPDATE battles SET idStates = 1 WHERE battles.id = '.$battleId.';';
        $this->db->query($sql);
    }

    public function addSubmissions($nickname, $iframeCodeValidated, $idBattle){
        $number = count($nickname);         
        if ($number > 0) {          
            for($i=0; $i<$number; $i++) {                  
                $sql= "INSERT INTO submissions (id, battlesId, nickname, soundcloudLink, voteCount) VALUES (NULL, ".$idBattle.", '".$nickname[$i]."', '".$iframeCodeValidated[$i]."', NULL);";
                $this->db->query($sql);
            }
        }
    }

    public function addBattleDraft($title, $samples, $tags, $maxVotes, $rules, $createdDate){
        $sql = 'INSERT INTO battles (id, title, rules, createdDate, idStates, samplesLink, maxVotes, tags) VALUES (NULL, "'.$title.'", "'.$rules.'", "'.$createdDate.'", 3 , "'.$samples.'", '.$maxVotes.' , "'.$tags.'" )';
        $this->db->query($sql);
    }

    public function addBattlePublish($title, $samples, $tags, $maxVotes, $rules, $createdDate){
        $sql = 'INSERT INTO battles (id, title, rules, createdDate, idStates, samplesLink, maxVotes, tags) VALUES (NULL, "'.$title.'", "'.$rules.'", "'.$createdDate.'", 1 , "'.$samples.'", '.$maxVotes.' , "'.$tags.'" )';
        $this->db->query($sql);
    }

    public function battleSubs($battleId){
        $sql = 'SELECT s.id as id, s.battlesId as idBattle, s.nickname as nickname, s.soundcloudLink as soundcloudLink, s.voteCount as voteCount, b.idStates as idStates FROM submissions as s INNER JOIN battles as b on b.id = s.battlesId WHERE battlesId = '.$battleId.' ORDER BY s.nickname asc';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function searchUser($email){
        $sql = 'SELECT * FROM users WHERE email = "'.$email.'" ';
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function votosGrafico($battleId){
        $sql='SELECT s.nickname as nickname, COUNT(v.id) as votes FROM battles as b INNER JOIN submissions as s ON s.battlesId = b.id INNER JOIN votes as v ON v.submissionsId = s.id 
            WHERE s.battlesId = '.$battleId.'
            GROUP BY s.nickname';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function validateIfCanReVote($idUser, $battleId){
        $maxVotes = 'SELECT *  FROM battles WHERE id = '. $battleId;
        $maxVotesRow = $this->db->query($maxVotes)->row();
        $maxVotes = $maxVotesRow->maxVotes;

        $votesUser = 'SELECT * FROM votes as v
            INNER JOIN submissions as s 
            on v.submissionsId = s.id
            WHERE v.userId = '.$idUser.' AND s.battlesId  = '.$battleId;
        $votesUserResult = $this->db->query($votesUser);

        $countVotesUserResult = $votesUserResult->num_rows();

        if($countVotesUserResult <  $maxVotes){
            return "true";
        } elseif ($countVotesUserResult >=  $maxVotes) {
            return "false";
        }
    }

    public function userVotes($idUser){        
        $sql='SELECT * FROM votes WHERE userId = '.$idUser.' ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function oneBattles($battleId){
        $sql = 'SELECT * FROM battles WHERE id = '.$battleId.' ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function deleteVoteOfUser($idUser, $battleId, $submissionsId){
        $sql='SELECT  v.id as idVotes
                FROM battles as b 
                INNER JOIN submissions as s 
                on s.battlesId = b.id
                INNER JOIN votes as v 
                on v.submissionsId = s.id
                WHERE v.userId = '.$idUser.' AND s.id = '.$submissionsId.'
                ORDER BY v.id asc ';
        $query = $this->db->query($sql);
        if($query) {
            $deleteSql = 'DELETE FROM votes WHERE votes.id = '.($query->row())->idVotes;
            $this->db->query($deleteSql);            
        }
    }

    public function validateVote($idUser, $idSubmission, $battleId){
        $maxVotesSql = 'SELECT b.maxVotes FROM battles as b
                        INNER JOIN submissions as s 
                        on s.battlesId = b.id
                        INNER JOIN votes as v
                        on v.submissionsId = s.id
                        WHERE s.battlesId = '.$battleId.'
                        LIMIT 1';
        $maxVotesRow = $this->db->query($maxVotesSql)->row();
        $maxVotes = $maxVotesRow->maxVotes;

        $votesUserSql = 'SELECT * FROM votes as v
            INNER JOIN submissions as s 
            on v.submissionsId = s.id
            WHERE v.userId = '.$idUser.' AND s.battlesId  = '.$battleId;

        $countVotes = $this->db->query($votesUserSql)->num_rows();

        if ($countVotes == 0) {
            $sql = 'INSERT INTO votes (id, userId, submissionsId) VALUES (NULL, '.$idUser.', '.$idSubmission.')';
            $this->db->query($sql);
            echo "Vote Inserted";
        } elseif($countVotes > 0) {
            if ($countVotes < $maxVotes ) {
                $sql = 'INSERT INTO votes (id, userId, submissionsId) VALUES (NULL, '.$idUser.', '.$idSubmission.')';
                $this->db->query($sql);
                echo "Vote Inserted";            
             } else {
                echo "Your maximum votes is complete";
             }
        } else {
            echo "error";
        }
    }

    public function searchBattleWithId($battleId){
        $sql = 'SELECT idStates FROM battles WHERE id = '.$battleId.' ';
        $query = $this->db->query($sql);
        return $query->row()->idStates;
    }

    public function searchBattle($idSubmission){
        $sql = 'SELECT * FROM submissions WHERE id = '.$idSubmission.' ';
        $query = $this->db->query($sql);
        return $query->row();
    }
}