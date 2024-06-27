<?php
// Include the database connection file
require_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and retrieve POST data
        $fetcherCode = htmlspecialchars($_POST["fetcherCode"]);
        $fetcherName = htmlspecialchars($_POST["fetcherName"]);
        $contactNo = htmlspecialchars($_POST["contactNo"]);
        $registerDate = date_format(date_create($_POST["registerDate"]), "Y-m-d");
        $isActive = isset($_POST["status"]) && $_POST["status"] == "active" ? 1 : 0; // Check if active checkbox is checked

        // Prepare data for insertion into fetchers table
        $params = array(
            "fetcher_code" => $fetcherCode,
            "fetcher_name" => $fetcherName,
            "contact_no" => $contactNo,
            "register_date" => $registerDate,
            "status" => $isActive
        );

        // Insert data into fetchers table
        $success = PDO_InsertRecord($link_id, "fetchers", $params, false);

        // Check if insertion was successful
        if ($success) {
            // Prepare to insert data into fetchers_students table
            $fetcherID = $link_id->lastInsertId(); // Get the last inserted fetcher ID

            // Get the number of students again
            $qry = "SELECT * FROM studentfile";
            $stmt = $link_id->prepare($qry);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $numStudents = count($students);

            // Loop through the student codes and relationships
            for ($i = 1; $i <= $numStudents; $i++) {
                $studentCodeKey = 'studentCode' . $i;
                $relationshipKey = 'relationship' . $i;

                if (isset($_POST[$studentCodeKey]) && !empty($_POST[$studentCodeKey])) {
                    $studentCode = htmlspecialchars($_POST[$studentCodeKey]);
                    $relationship = htmlspecialchars($_POST[$relationshipKey]);

                    // Debugging output
                    error_log("Student Code: $studentCode, Relationship: $relationship");

                    // Prepare data for insertion into fetchers_students table
                    $fetchersStudentsParams = array(
                        "fetcher_code" => $fetcherCode,
                        "studentcode" => $studentCode,
                        "relationship" => $relationship
                    );

                    // Insert data into fetchers_students table
                    $success = PDO_InsertRecord($link_id, "fetchers_students", $fetchersStudentsParams, false);

                    // Check if insertion was successful
                    if (!$success) {
                        $response["status"] = "error";
                        $response["message"] = "Failed to add fetcher-student relationship for student code: $studentCode";
                        echo json_encode($response);
                        exit();
                    }
                }
            }

            if ($success) {
                $response["status"] = "success";
                $response["message"] = "Fetcher and relationships added successfully";
            }
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to add fetcher";
        }
    } catch (Exception $e) {
        $response["status"] = "error";
        $response["message"] = "Exception: " . $e->getMessage();
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
