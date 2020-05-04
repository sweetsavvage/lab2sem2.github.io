<?php 
    $startYear = $_GET["startYear"];
    $finalYear = $_GET["finalYear"];

    require_once __DIR__ . "/vendor/autoload.php";
    $collection = (new MongoDB\Client) -> dbforlab -> literature;
    
    $res = [];

    $js = "function() {
        return this.year >= ".$startYear." && this.year <= ".$finalYear." ;
    }";
    
    $cursor = $collection -> find(array('$where' => $js));

    foreach($cursor as $document){
        array_push($res, $document["title"]);
    }
    echo json_encode($res);
?>