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

$sql = "SELECT exercises.*,bodyparts.bodypart_title, equipments.equipment_title AS equipment_title, levels.level_title AS level_title, exercises_bodyparts.bodypart_id AS bodypart_id FROM exercises JOIN exercises_bodyparts ON exercises_bodyparts.exercise_id = exercises.exercise_id JOIN bodyparts ON exercises_bodyparts.bodypart_id = bodyparts.bodypart_id JOIN equipments ON exercises.exercise_equipment = equipments.equipment_id JOIN levels ON exercises.exercise_level = levels.level_id";

if(isset($_GET['id']) && !empty($_GET['id'])) {
$sql .= " AND exercises_bodyparts.bodypart_id=".$_GET["id"];
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
    $bodypart_title = $row['bodypart_title'];
    $bodypart_id = $row['bodypart_id'];

    $exercises[] = array(
        'exercise_id'=> $exercise_id,
        'exercise_title'=> html_entity_decode($exercise_title),
        'exercise_image'=> $exercise_image,
        'bodypart_title'=> html_entity_decode($bodypart_title),
        'bodypart_id'=> $bodypart_id
    );

}
    
$close = mysqli_close($connection) 
or die("An unexpected error has occurred in the disconnection of the database");
  

$json_string = json_encode($exercises);
print ($json_string)

?>