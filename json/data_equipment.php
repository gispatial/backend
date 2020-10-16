<?php

$page = 1;
if(!empty($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if(false === $page) {
        $page = 1;
    }
}

$limit = 10;
if(!empty($_GET['limit'])) {
    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
    if(false === $limit) {
        $limit = 1;
    }
}

$offset = ($page - 1) * $limit;

header('Content-Type: application/json');
header("access-control-allow-origin: *");

require '../admin/config.php';

$connection = mysqli_connect($database['host'],$database['user'], $database['pass'], $database['db']) 
or die("An unexpected error has occurred in the database connection");

$sql .= "SELECT exercises.*,equipments.equipment_title AS equipment_title, equipments.equipment_id AS equipment_id FROM exercises JOIN equipments ON exercises.exercise_equipment = equipments.equipment_id JOIN levels ON exercises.exercise_level = levels.level_id";

if(isset($_GET['id']) && !empty($_GET['id'])) {
$sql .= " AND exercises.exercise_equipment=".$_GET["id"];
}

$sql .= " GROUP BY exercises.exercise_id";

if(isset($_GET['page']) && !empty($_GET['page'])) {
$sql .= " LIMIT ".$offset.",".$limit;
}

if(isset($_GET['limit']) && !empty($_GET['limit']) && !isset($_GET['page'])) {
$sql .= " LIMIT ".$limit;
}

mysqli_set_charset($connection, "utf8");

if(!$result = mysqli_query($connection, $sql)) die();

$exercises = array();

while($row = mysqli_fetch_array($result)) 
{   
    $exercise_id = $row['exercise_id'];
    $exercise_title = $row['exercise_title'];
    $exercise_image = $row['exercise_image'];
    $equipment_id = $row['equipment_id'];
    $equipment_title = $row['equipment_title'];

    $exercises[] = array(
        'exercise_id'=> $exercise_id,
        'exercise_title'=> html_entity_decode($exercise_title),
        'exercise_image'=> $exercise_image,
        'equipment_id'=> html_entity_decode($equipment_id),
        'equipment_title'=> $equipment_title
    );

}
    
$close = mysqli_close($connection) 
or die("An unexpected error has occurred in the disconnection of the database");
  

$json_string = json_encode($exercises);
print ($json_string)

?>