<?php
use D002834\Backend\configs\Database;

$db = Database::getInstance();
$pdo = $db->getConnection();
$db->createDatabase("practice");
$db->createTable("practice", "users");

