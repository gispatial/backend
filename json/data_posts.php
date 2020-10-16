
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

$sql .= "SELECT posts.*,tags.tag_title AS tag_title FROM posts,tags WHERE posts.post_tag = tags.tag_id";

if(isset($_GET['id']) && !empty($_GET['id'])) {
$sql .= " AND posts.post_id=".$_GET["id"];
}

if(isset($_GET['tag']) && !empty($_GET['tag'])) {
$sql .= " AND posts.post_tag=".$_GET["tag"];
}

if(isset($_GET['featured']) && !empty($_GET['featured'])) {
$sql .= " AND posts.post_featured=".$_GET["featured"];
}

$sql .= " ORDER BY posts.post_id DESC";

if(isset($_GET['page']) && !empty($_GET['page'])) {
$sql .= " LIMIT ".$offset.",".$limit;
}

if(isset($_GET['limit']) && !empty($_GET['limit']) && !isset($_GET['page'])) {
$sql .= " LIMIT ".$limit;
}

mysqli_set_charset($connection, "utf8");

if(!$result = mysqli_query($connection, $sql)) die();

$posts = array();
$id = 0;


while($row = mysqli_fetch_array($result)) 
{	
	$post_id = $row['post_id'];
    $post_title = $row['post_title'];
    $post_description = $row['post_description'];
    $post_tag = $row['post_tag'];
    $post_featured = $row['post_featured'];
    $post_date = $row['post_date'];
    $post_image = $row['post_image'];
    $tag_title = $row['tag_title'];

    $posts[] = array(
    	'id'=> $id++,
    	'post_id'=> $post_id,
    	'post_title'=> html_entity_decode($post_title),
    	'post_description'=> $post_description,
        'post_tag'=> $post_tag,
        'post_featured'=> $post_featured,
        'published'=> $post_date,
    	'post_date'=> date("d-m-Y", strtotime($post_date)),
    	'post_image'=> $post_image,
    	'tag_title'=> $tag_title,
    	);

}
    
$close = mysqli_close($connection) 
or die("An unexpected error has occurred in the disconnection of the database");
  

$json_string = json_encode($posts);
print ($json_string)

?>

