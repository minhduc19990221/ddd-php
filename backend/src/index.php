<?php
// Setting the Content-Type to JSON and allowing all origins to access it.
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// MySQL database details
$host = 'localhost';
$db   = 'testdb';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Preparing PDO connection to the MySQL database
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exception on error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return associative array when fetching
    PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Getting the HTTP method of the request
$method = $_SERVER['REQUEST_METHOD'];

// Determine the requested resource
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

// Switch case based on the HTTP method
switch ($method) {
  case 'GET':
    // In case of GET, fetch the user(s)
    $id = $_GET['id'];
    $sql = "select * from users".($id?" where id=$id":'');
    break;
  case 'POST':
    // In case of POST, create a new user
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $sql = "insert into users (firstname, lastname, email) values ('$firstname', '$lastname', '$email')";
    break;
  case 'PUT':
    // In case of PUT, update an existing user
    parse_str(file_get_contents("php://input"),$_PUT);
    $id = $_PUT['id'];
    $firstname = $_PUT["firstname"];
    $lastname = $_PUT["lastname"];
    $email = $_PUT["email"];
    $sql = "update users set firstname='$firstname', lastname='$lastname', email='$email' where id=$id";
    break;
  case 'DELETE':
    // In case of DELETE, delete an existing user
    parse_str(file_get_contents("php://input"),$_DELETE);
    $id = $_DELETE['id'];
    $sql = "delete from users where id=$id";
    break;
}

// Execute the SQL statement
$result = $pdo->query($sql);

// In case of any error in SQL statement execution, return a 404 with the error message
if (!$result) {
  http_response_code(404);
  die(mysqli_error());
}

// If method is GET, return the fetched data
if ($method == 'GET') {
  echo json_encode(($id>0 ? $result->fetch(PDO::FETCH_ASSOC) : $result->fetchAll(PDO::FETCH_ASSOC)));
} else {
  // If method is not GET, return the result of the operation
  echo json_encode($result);
}
