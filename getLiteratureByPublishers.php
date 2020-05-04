<?php 
    $publisher = $_GET["publisher"];
    require_once __DIR__ . "/vendor/autoload.php";
    $collection = (new MongoDB\Client) -> dbforlab -> literature;
    $cond = array("publisher" => $publisher);
    $cursor = $collection -> find($cond);
    $res = [];

    foreach($cursor as $document){
        array_push($res, $document["title"]);
        
    }
    echo json_encode($res);
?>