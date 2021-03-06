<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 17/03/2019
 * Time: 12:38
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "./Database/DAO/ClusterDB.php";
include_once "./Database/DAO/CauseEffectDB.php";
include_once "./Database/DAO/CauseDB.php";
include_once './Database/DAO/EffectDB.php';
include_once './Database/DAO/UserDB.php';
 
function combinations_set($set = [], $size = 0) {
    if ($size == 0) {
        return [[]];
    }
 
    if ($set == []) {
        return [];
    }
 
 
    $prefix = [array_shift($set)];
 
    $result = [];
 
    foreach (combinations_set($set, $size-1) as $suffix) {
        $result[] = array_merge($prefix, $suffix);
    }
 
    foreach (combinations_set($set, $size) as $next) {
        $result[] = $next;
    }
 
    return $result;
}
 
function combination_integer($n, $m) {
    return combinations_set(range(0, $n-1), $m);
}

function functionAPI($array){
    //$array = [2,7,8,14,22];
    $finalArray = array();
    $temparray = array();
    for($i = sizeof($array); $i > 1; $i--){
        //echo $i ." comb ".$c.":<br>";
        $counterArray = combination_integer(sizeof($array), $i);
        foreach ($counterArray as $combination) {
            foreach ($combination as $e) {
                array_push($temparray, $array[$e]);
            }
            array_push($finalArray, $temparray);
            $temparray = array();
        }
    }
    //echo $counter. "<br>";
    //var_dump($finalArray);
    //var_dump($causes);
    $causes = ClusterDB::getCausesForAPI($finalArray);
    $finalCauses = array();
    foreach ($causes as $c){
        if (!empty($c)){
            foreach ($c as $cause){
                $cause = CauseDB::getById($cause->cause);
                array_push($finalCauses, $cause[0]->id);
            }
        }
    }
    return $finalCauses;
}


$data = json_decode(file_get_contents("php://input"));
$logPassword = strtoupper(hash('sha512', $data->credentials->pass));

if (isset($data->credentials->login) && isset($data->credentials->pass)){
    if (UserDB::Login($data->credentials->login,$logPassword) == 0){
        $causes = array();
        if (sizeof($data->effects) > 1){
            $causes1 = array();
            $causes2 = array();
            $causeEffects = array();
            $array = array();
            foreach ($data->effects as $key => $value) {
                array_push($array, $value);
            }
            $ik = functionAPI($array);
            $ik = array_count_values($ik);
            arsort($ik);
            foreach ($ik as $key => $value) {
                array_push($causes1, CauseDB::getById($key));
            }
            foreach ($data->effects as $key) {
                $causeEffect = CauseEffectDB::getCausebyEffectId($key);
                foreach ($causeEffect as $c){
                    $cause = CauseDB::getById($c->cause);
                    array_push($causes2, $cause);
                }
            }
            http_response_code(200);
            array_push($causes, $causes1);
            array_push($causes, $causes2);
            echo json_encode($causes);
            die();
        }else if (sizeof($data->effects) <= 1) {
            $causes1 = CauseEffectDB::getCausebyEffectId($data->effects[0]);
            foreach ($causes1 as $c){
                $cause = CauseDB::getById($c->cause);
                array_push($causes, $cause);
            }
            http_response_code(200);
            echo json_encode($causes);
            die();
        }else
        {
            http_response_code(404);
            echo json_encode(
                array("message" => "No Effects found.")
            );
            die();
        }
    }else{
        http_response_code(401);
        echo json_encode(
            array("message" => "Unauthorized")
        );
        die();
    }
}else{
    http_response_code(401);
    echo json_encode(
        array("message" => "Unauthorized: Give Credentials")
    );
    die();
}
?>





