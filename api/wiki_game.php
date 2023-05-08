<?php
//
//      /api/wiki_game.php
//
    include 'WikiGameController.php';

    header('Content-Type: application/json; charset: utf-8');
    // echo count($_POST);
    // echo count($_GET);

    $wikiGameController = new WikiGameController();

    $action = $_SERVER['REQUEST_URI'];
    $action = explode('?', $action);
    $action = explode('/',$action[0])[3];
    if(($action === 'playersQty')&&($_SERVER['REQUEST_METHOD'] === 'POST'))
        $wikiGameController->setPlayersQty();
    // else if(($action === 'path.start')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
    //     $wikiGameController->getStartPath();
    // else if(($action === 'path.finish')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
    //     $wikiGameController->getFinishPath();
    else if(($action === 'path.now.links')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        $wikiGameController->getLinksFromPage();
    else if(($action === 'path.now')&&($_SERVER['REQUEST_METHOD'] === 'POST'))
        $wikiGameController->setPathNow();
    else if(($action === 'path.now')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        $wikiGameController->getPathNow();
    // else if(($action === 'player.stepsQty')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
    //     $wikiGameController->getPlayerStepsQty();
    else if(($action === 'resultsTable')&&($_SERVER['REQUEST_METHOD'] === 'GET'))
        $wikiGameController->getResultsTable();
?>