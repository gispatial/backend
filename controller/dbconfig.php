<?php

include('../admin/config.php');

try
{
 $DB_con = new PDO('mysql:host='. $database['localhost:8888'] .';dbname='. $database['ijaa'], $database['ijaa'], $database['ijaa123']);
 $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
 $e->getMessage();
}
