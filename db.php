<?php
$db_table="data";
function getdata($user_id) {
    global $db_table;
    $mysqli = new mysqli("localhost", "prive7", "3B6d2O9y", "prive7");
    $mysqli->set_charset("utf8");
    $res = $mysqli->query("SELECT data FROM ".$db_table." WHERE id = ".$user_id);
    
    if (json_decode($res->num_rows, true) !== 0) {
        $res = $res->fetch_assoc();
        $res = $res['data'];
        $data = json_decode($res, true);
    }else{
    $data = array('loc' => 'start_loc');
    }
    $mysqli->close();
    return $data;
}
function getmem($city) {
    $db_table;
    $mysqli = new mysqli("localhost", "a0350154_prive7", "3B6d2O9y", "a0350154_prive7");
    $mysqli->set_charset("utf8");
    $res = $mysqli->query("SELECT * FROM members WHERE location = ".$city);
    $data=[];
    while($row = $res->fetch_assoc()) {
        $data[]=$row;
    }
    $mysqli->close();
    return $data;
}
function getpoll($id) {
    $db_table;
    $mysqli = new mysqli("localhost", "a0350154_prive7", "3B6d2O9y", "a0350154_prive7");
    $mysqli->set_charset("utf8");
    $res = $mysqli->query("SELECT * FROM members  WHERE id = ".$id);
    $mysqli->close();
    $data= $res->fetch_assoc();
    return $data;
}
function setpoll($mid) {
    $db_table;
    $mysqli = new mysqli("localhost", "a0350154_prive7", "3B6d2O9y", "a0350154_prive7");
    $mysqli->set_charset("utf8");
    $res = $mysqli->query("UPDATE members SET `votes` = `votes` + 1  WHERE id = ".$mid);
    $mysqli->close();
}
function writedata($user_id, $dt) {
    global $db_table;
    $mysqli = new mysqli("localhost", "a0350154_prive7", "3B6d2O9y", "a0350154_prive7");
    $mysqli->set_charset("utf8");
    $mysqli->query("UPDATE ".$db_table." SET `data` = '".json_encode($dt, JSON_UNESCAPED_UNICODE)."' WHERE ".$db_table.".`id` = '".$user_id."';");
    $mysqli->query("INSERT INTO ".$db_table." (`id`, `data`) VALUES ('".$user_id."', '".json_encode($dt, JSON_UNESCAPED_UNICODE)."');");
    $mysqli->close();
}
?>