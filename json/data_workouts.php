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

$sql .= "SELECT workouts.*,goals.goal_title AS goal_title, levels.level_title AS level_title FROM workouts,goals,levels WHERE workouts.workout_goal = goals.goal_id AND workouts.workout_level = levels.level_id";

if(isset($_GET['id']) && !empty($_GET['id'])) {
$sql .= " AND workouts.workout_id=".$_GET["id"];
}

if(isset($_GET['goal']) && !empty($_GET['goal'])) {
$sql .= " AND workouts.workout_goal=".$_GET["goal"];
}

if(isset($_GET['level']) && !empty($_GET['level'])) {
$sql .= " AND workouts.workout_level=".$_GET["level"];
}

$sql .= " ORDER BY workouts.workout_id DESC";

if(isset($_GET['page']) && !empty($_GET['page'])) {
$sql .= " LIMIT ".$offset.",".$limit;
}

if(isset($_GET['limit']) && !empty($_GET['limit']) && !isset($_GET['page'])) {
$sql .= " LIMIT ".$limit;
}

mysqli_set_charset($connection, "utf8");

if(!$result = mysqli_query($connection, $sql)) die();

$workouts = array();
$id = 0;

while($row = mysqli_fetch_array($result)) 
{	
	$workout_id = $row['workout_id'];
    $workout_title = $row['workout_title'];
    $workout_description = $row['workout_description'];
    $workout_goal = $row['workout_goal'];
    $workout_level = $row['workout_level'];
    $workout_duration = $row['workout_duration'];
    $workout_image = $row['workout_image'];
    $goal_title = $row['goal_title'];
    $level_title = $row['level_title'];

    $workouts[] = array(
    	'id'=> $id++,
    	'workout_id'=> $workout_id,
    	'workout_title'=> html_entity_decode($workout_title),
    	'workout_description'=> $workout_description,
    	'workout_goal'=> $workout_goal,
    	'workout_level'=> $workout_level,
    	'workout_duration'=> $workout_duration,
    	'workout_image'=> $workout_image,
    	'goal_title'=> $goal_title,
    	'level_title'=> $level_title,
    	);

}
    
$close = mysqli_close($connection) 
or die("An unexpected error has occurred in the disconnection of the database");
  

$json_string = json_encode($workouts);
print ($json_string)

?>