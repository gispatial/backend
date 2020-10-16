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


header('Content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

require '../admin/config.php';

$connection = mysqli_connect($database['host'],$database['user'], $database['pass'], $database['db']) 
or die("An unexpected error has occurred in the database connection");

$sql .= "SELECT diets.*,categories.category_title AS category_title FROM diets,categories WHERE diets.diet_category = categories.category_id";

if(isset($_GET['id']) && !empty($_GET['id'])) {
$sql .= " AND diets.diet_id=".$_GET["id"];
}

if(isset($_GET['category']) && !empty($_GET['category'])) {
$sql .= " AND diets.diet_category=".$_GET["category"];
}

if(isset($_GET['featured']) && !empty($_GET['featured'])) {
$sql .= " AND diets.diet_featured=".$_GET["featured"];
}

$sql .= " ORDER BY diets.diet_id DESC";

if(isset($_GET['page']) && !empty($_GET['page'])) {
$sql .= " LIMIT ".$offset.",".$limit;
}

if(isset($_GET['limit']) && !empty($_GET['limit']) && !isset($_GET['page'])) {
$sql .= " LIMIT ".$limit;
}

mysqli_set_charset($connection, "utf8");

if(!$result = mysqli_query($connection, $sql)) die();

$diets = array();
$id = 0;

while($row = mysqli_fetch_array($result)) 
{	
	$diet_id = $row['diet_id'];
    $diet_title = $row['diet_title'];
    $diet_description = $row['diet_description'];
    $diet_ingredients = $row['diet_ingredients'];
    $diet_category = $row['diet_category'];
    $diet_directions = $row['diet_directions'];
    $diet_calories = $row['diet_calories'];
    $diet_carbs = $row['diet_carbs'];
    $diet_protein = $row['diet_protein'];
    $diet_fat = $row['diet_fat'];
    $diet_time = $row['diet_time'];
    $diet_servings = $row['diet_servings'];
    $diet_image = $row['diet_image'];
    $diet_featured = $row['diet_featured'];
    $category_title = $row['category_title'];

    $diets[] = array(
    	'id'=> $id++,
    	'diet_id'=> $diet_id,
    	'diet_title'=> html_entity_decode($diet_title),
    	'diet_description'=> $diet_description,
    	'diet_ingredients'=> $diet_ingredients,
    	'diet_category'=> $diet_category,
    	'diet_directions'=> $diet_directions,
    	'diet_calories'=> $diet_calories,
    	'diet_carbs'=> $diet_carbs,
    	'diet_protein'=> $diet_protein,
    	'diet_fat'=> $diet_fat,
    	'diet_time'=> $diet_time,
    	'diet_servings'=> $diet_servings,
    	'diet_image'=> $diet_image,
        'diet_featured'=> $diet_featured,
    	'category_title'=> $category_title,
    	);

}
    
$close = mysqli_close($connection) 
or die("An unexpected error has occurred in the disconnection of the database");
  

$json_string = json_encode($diets);
print ($json_string)



?>

