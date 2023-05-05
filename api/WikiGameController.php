<?php
//
//      /api/WikiGameController.php
//
    class WikiGameController {
        //'games(id, players_qty, start_path, finish_path, player_now_id)'
        //'results(id, game_id, user_id, user_result, path_now)'
        protected $db;

        public function __construct() {
            $this->db = new SQLite3("../db.sqlite3");
        }
        function __destruct() {
            $this->db->close();
        }

        // if(($action === 'playersQty')&&($_SERVER['REQUEST_METHOD'] === 'POST'))
        public function setPlayersQty(){
            $this->db->exec("DELETE FROM games");
            $this->db->exec("INSERT INTO games(id, players_qty) VALUES (1, 25)");
            echo json_encode(array('qty' => 2));
        }
        // else if(($action === 'startPath')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getStartPath(){

        }
        // else if(($action === 'finishPath')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getFinishPath(){
            
        }
        // else if(($action === 'linksFromPage')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getLinksFromPage(){
            
        }
        // else if(($action === 'pathNow')&&($_SERVER['REQUEST_METHOD'] === 'POST'))
        public function setPathNow(){
            
        }
        // else if(($action === 'pathNow')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getPathNow(){
            
        }
        // else if(($action === 'playerStepsQty')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getPlayerStepsQty(){
            
        }
        // else if(($action === 'resultsTable')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getResultsTable(){
            
        }
    }
?>