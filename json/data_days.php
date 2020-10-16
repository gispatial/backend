<?php

header('Content-Type: application/json');
header("access-control-allow-origin: *");

require '../admin/config.php';

$connection = mysqli_connect($database['host'],$database['user'], $database['pass'], $database['db']) 
or die("An unexpected error has occurred in the database connection");

$sql .= "SELECT exercises.*,workouts.workout_id, equipments.equipment_title AS equipment_title, levels.level_title AS level_title FROM exercises JOIN levels ON exercises.exercise_level = levels.level_id JOIN equipments ON exercises.exercise_equipment = equipments.equipment_id";

if(isset($_GET['day']) && !empty($_GET['day']) && isset($_GET['id']) && !empty($_GET['id'])) {

if ($_GET['day'] == 1) {

$sql .= " JOIN we_day1 ON we_day1.exercise_id = exercises.exercise_id JOIN workouts ON we_day1.workout_id = workouts.workout_id WHERE we_day1.workout_id = ".$_GET["id"];

}elseif($_GET['day'] == 2){

$sql .= " JOIN we_day2 ON we_day2.exercise_id = exercises.exercise_id JOIN workouts ON we_day2.workout_id = workouts.workout_id WHERE we_day2.workout_id = ".$_GET["id"];

}elseif($_GET['day'] == 3){

$sql .= " JOIN we_day3 ON we_day3.exercise_id = exercises.exercise_id JOIN workouts ON we_day3.workout_id = workouts.workout_id WHERE we_day3.workout_id = ".$_GET["id"];

}elseif($_GET['day'] == 4){

$sql .= " JOIN we_day4 ON we_day4.exercise_id = exercises.exercise_id JOIN workouts ON we_day4.workout_id = workouts.workout_id WHERE we_day4.workout_id = ".$_GET["id"];

}elseif($_GET['day'] == 5){

$sql .= " JOIN we_day5 ON we_day5.exercise_id = exercises.exercise_id JOIN workouts ON we_day5.workout_id = workouts.workout_id WHERE we_day5.workout_id = ".$_GET["id"];

}elseif($_GET['day'] == 6){

$sql .= " JOIN we_day6 ON we_day6.exercise_id = exercises.exercise_id JOIN workouts ON we_day6.workout_id = workouts.workout_id WHERE we_day6.workout_id = ".$_GET["id"];

}elseif($_GET['day'] == 7){

$sql .= " JOIN we_day7 ON we_day7.exercise_id = exercises.exercise_id JOIN workouts ON we_day7.workout_id = workouts.workout_id WHERE we_day7.workout_id = ".$_GET["id"];

}

}

 

mysqli_set_charset($connection, "utf8");

if(!$result = mysqli_query($connection, $sql)) die();

$exercises = array();
$id = 0;

while($row = mysqli_fetch_array($result)) 
{   
    $exercise_id = $row['exercise_id'];
    $exercise_title = $row['exercise_title'];
    $exercise_equipment = $row['exercise_equipment'];
    $exercise_level = $row['exercise_level'];
    $exercise_reps = $row['exercise_reps'];
    $exercise_rest = $row['exercise_rest'];
    $exercise_sets = $row['exercise_sets'];
    $exercise_video = $row['exercise_video'];
    $exercise_image = $row['exercise_image'];
    $exercise_tips = $row['exercise_tips'];
    $exercise_instructions = $row['exercise_instructions'];
    $equipment_title = $row['equipment_title'];
    $level_title = $row['level_title'];
    

    $exercises[] = array(
        'exercise_id'=> $exercise_id,
        'exercise_title'=> html_entity_decode($exercise_title),
        'exercise_equipment'=> $exercise_equipment,
        'exercise_level'=> $exercise_level,
        'exercise_reps'=> $exercise_reps,
        'exercise_rest'=> $exercise_rest,
        'exercise_sets'=> $exercise_sets,
        'exercise_video'=> $exercise_video,
        'exercise_image'=> $exercise_image,
        'exercise_tips'=> $exercise_tips,
        'exercise_instructions'=> $exercise_instructions,
        'equipment_title'=> html_entity_decode($equipment_title),
        'level_title'=> html_entity_decode($level_title),
        );

}
    
$close = mysqli_close($connection) 
or die("An unexpected error has occurred in the disconnection of the database");
  

$json_string = json_encode($exercises);
print ($json_string);

?>