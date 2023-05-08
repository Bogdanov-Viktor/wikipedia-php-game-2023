<?php
//
//      /api/WikiGameController.php
//
    include 'WikipediaApi.php';
    

    class WikiGameController {
        //'games(id, players_qty, start_path, finish_path, player_now_id)'
        //'results(id, game_id, user_id, user_steps_qty, user_path_now)'
        protected $db;

        public function __construct() {
            $this->db = new SQLite3("../db.sqlite3");
        }
        function __destruct() {
            $this->db->close();
        }

        // if(($action === 'playersQty')&&($_SERVER['REQUEST_METHOD'] === 'POST'))
        public function setPlayersQty(){
            $reqBody = json_decode(file_get_contents('php://input'), true);
            if (isset($reqBody['qty'])) {
                $players_qty = $reqBody['qty'];
                // echo gettype($qty);
                if(gettype($players_qty)==='integer'){
                    if(($players_qty >= 1)&&($players_qty < 200)){

                        $this->db->exec("DELETE FROM games");
                        $this->db->exec("DELETE FROM results");

                        
                        
                        $start = WikipediaApi::getStartUrlPath();
                        // echo $x;
                        $finish = WikipediaApi::getFinishUrlPath(); //getFinishUrlPath
                        
                        //'games(id, players_qty, start_path, finish_path, player_now_id)'
                        //for($i=1;$i<=$players_qty;$i++)
                        //   'results(id, game_id, user_id, user_result, user_path_now)'

                        $this->db->exec(
                            "INSERT INTO games(id, players_qty, start_path, finish_path, player_now_id)
                             VALUES (1, $players_qty, '$start', '$finish', 1)"
                        );
                        for($user_id=1; $user_id<=$players_qty; $user_id++)
                            $this->db->exec(
                                "INSERT INTO results(game_id, user_id, user_steps_qty, user_path_now)
                                 VALUES (1, $user_id, 0, '$start')"
                            );

                        echo json_encode(array(
                            'status' => true,
                            // 'qty' => $players_qty,
                            'hostUrl' => WikipediaApi::WIKI_HOST,
                            'pathStart' => $start,
                            'pathFinish' => $finish //,

                            // 'getLinksFromPage' => WikipediaApi::getLinksFromPage($x),
                            // 'getFastPathFromToLength' => WikipediaApi::getFastPathFromToLength($x,$y)
                        ));
                        return;

                    }
                }
            }
            echo json_encode(array(
                'status' => false
               ));
        }
        /*
        // else if(($action === 'path.start')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getStartPath(){
            // games->start_path
        }
        // else if(($action === 'path.finish')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getFinishPath(){
            // games->finish_path
        }
        */
        protected function getPlayerNowIdFromDb(){
            return $this->db->querySingle(
                'SELECT player_now_id FROM games WHERE id=1'
            );
        }
        protected function getPathNowFromDb(){
            //'games(!id!, players_qty, start_path, finish_path, !player_now_id!)'
            //'results(id, game_id, !user_id!, user_steps_qty, !user_path_now!)
            $user_id = $this->getPlayerNowIdFromDb();
            return $this->db->querySingle(
                "SELECT user_path_now FROM results WHERE user_id=$user_id"
            );
        }
        protected function getPathFinishFromDb(){
            return $this->db->querySingle(
                'SELECT finish_path FROM games WHERE id=1'
            );
        }
        protected function setPathNowFromDb($newPath){
            $user_id = $this->getPlayerNowIdFromDb();
            $this->db->exec(
                "UPDATE results SET user_path_now='$newPath' WHERE user_id=$user_id"
            );
        }
        protected function incrementUserStepsQtyNowFromDb(){
            $user_id = $this->getPlayerNowIdFromDb();
            $this->db->exec(
                "UPDATE results SET user_steps_qty=user_steps_qty+1 WHERE user_id=$user_id"
            );
        }
        protected function incrementPlayerNowFromDb(){
            $user_id = $this->getPlayerNowIdFromDb();
            $this->db->exec(
                "UPDATE games SET player_now_id=player_now_id+1 WHERE id=1"
            );
        }
        protected function getResultsTableFromDb(){
            //'results(id, game_id, !user_id!, !user_steps_qty!, user_path_now)
            // results->user_id (все) ->user_steps_qty
            $resultsQuery = $this->db->query(
                'SELECT user_id, user_steps_qty FROM results WHERE game_id=1 ORDER BY user_steps_qty'
            );
            $results = array();
            while ($row = $resultsQuery->fetchArray(SQLITE3_ASSOC)) {
                array_push($results, $row);
            }
            return $results;
        }

        // else if(($action === 'path.now.links')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getLinksFromPage(){
            // $page = '/wiki/Сафроновская_(Виноградовский_район)';
            $user_path_now = $this->getPathNowFromDb();

            echo json_encode(array(
             'paths' => WikipediaApi::getLinksFromPage($user_path_now)
            ));
        }
        // else if(($action === 'path.now')&&($_SERVER['REQUEST_METHOD'] === 'POST'))
        public function setPathNow(){
            $reqBody = json_decode(file_get_contents('php://input'), true);
            if (isset($reqBody['id'])) {
                $pathId = $reqBody['id'];
                if(gettype($pathId)==='integer'){
                    // $page
                    $linkPaths = WikipediaApi::getLinksFromPage($this->getPathNowFromDb());
                    if(($pathId >= 0)&&($pathId < count($linkPaths))){
                        $path = $linkPaths[$pathId];
                        // results->user_id->user_path_now
                        // results->user_id->user_steps_pty   +=1
                        $this->setPathNowFromDb($path);
                        $this->incrementUserStepsQtyNowFromDb();

                        if($path==$this->getPathFinishFromDb()){
                            $this->incrementPlayerNowFromDb();
                        }

                        echo json_encode(array(
                            'status' => true,
                            'pathNew' => $path
                           ));
                        return;
                    }
                }
            }
            echo json_encode(array(
                'status' => false
               ));
        }
        // else if(($action === 'path.now')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getPathNow(){
            echo json_encode(array(
                'path' => $this->getPathNowFromDb()
            ));
            // results->user_id->user_path_now//path_now
        }
        /*
        // else if(($action === 'player.stepsQty')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getPlayerStepsQty(){
            // results->user_id->user_steps_qty //user_result
        }
        */

        // else if(($action === 'resultsTable')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        public function getResultsTable(){
            echo json_encode(array(
                'resultsTable' => $this->getResultsTableFromDb()
            ));
        }
    }
?>