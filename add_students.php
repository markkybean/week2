<?php
// Include the database connection file
require_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve POST data
    $studentCode = htmlspecialchars($_POST["studentcode"]);
    $fullName = htmlspecialchars($_POST["fullname"]);

    // Prepare data for insertion
    $params = array(
        "studentcode" => $studentCode,
        "fullname" => $fullName
    );

    // Insert data into database
    $success = PDO_InsertRecord($link_id, "studentfile", $params, false);

    // Check if insertion was successful
    if ($success) {
        $response["status"] = "success";
        $response["message"] = "Student added successfully";
    } else {
        $response["status"] = "error";
        $response["message"] = "Failed to add student";
    }
} else {
    // If request method is not POST
    $response["status"] = "error";
    $response["message"] = "Invalid request method";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Redirect back to index.php
header('Location: index.php');
exit();
?>
