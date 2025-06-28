
<?php
$conn = new mysqli("localhost", "root", "", "taskmanager");
if ($conn->connect_error) {
    die(json_encode(["error" => "DB connection failed"]));
}
?>
