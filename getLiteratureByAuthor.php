<?php 
    $author = $_GET["author"];
    require_once __DIR__ . "/vendor/autoload.php";
    $collection = (new MongoDB\Client) -> dbforlab -> literature;
    $cond = array("author" => $author);
    $cursor = $collection -> find($cond);
    $res = [];

    foreach($cursor as $document){
        array_push($res, $document["title"]);
        
    }
    echo json_encode($res);
?>