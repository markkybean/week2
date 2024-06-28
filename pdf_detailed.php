<?php
require_once('lib/ezpdfclass/class/class.ezpdf.php');
require_once('db_config.php');

// Check if the ezpdf class file exists
$ezpdf_path = 'lib/ezpdfclass/class/class.ezpdf.php';
if (!file_exists($ezpdf_path)) {
    die('ezpdf class file not found.');
}

try {
    // Initialize PDF object
    $pdf = new Cezpdf('Letter', 'portrait');

    // Select font
    $pdf->selectFont("lib/ezpdfclass/fonts/Helvetica.afm");

    // Set up header
    $xheader = $pdf->openObject();
    $pdf->saveState();

    $xfsize = 10;
    $xtop = 750;

    // Header content
    $pdf->ezPlaceData(25, $xtop, "<b>Fetcher File Report</b>", 12, 'left');
    $xtop -= 20;
    
    // Display date printed
    $pdf->ezPlaceData(25, $xtop, "Date Printed: " . date('Y-m-d'), $xfsize, 'left');
    $xtop -= 20;

    // Draw top line
    $pdf->line(25, $xtop, 587, $xtop);

    $xtop -= 10;

    // Headers
    $pdf->ezPlaceData(150, $xtop, "Student Code", $xfsize, 'left');
    $pdf->ezPlaceData(250, $xtop, "Student Name", $xfsize, 'left');
    $pdf->ezPlaceData(400, $xtop, "Relationship", $xfsize, 'left');

    $xtop -= 5;
    // Draw second line
    $pdf->line(25, $xtop, 587, $xtop);

    // Fetch data from fetchers table with filters
    $fetcher_from = $_POST['fetcher_from'];
    $fetcher_to = $_POST['fetcher_to'];
    $reg_date_from = $_POST['reg_date_from'];
    $reg_date_to = $_POST['reg_date_to'];
    $status = isset($_POST['status']) && $_POST['status'] == 'inactive';

    $xqry_fetchers = "SELECT f.fetcher_code, fs.studentcode, sf.fullname, fs.relationship
                      FROM fetchers_students fs
                      INNER JOIN fetchers f ON fs.fetcher_code = f.fetcher_code
                      INNER JOIN studentfile sf ON fs.studentcode = sf.studentcode
                      WHERE f.fetcher_code BETWEEN :fetcher_from AND :fetcher_to
                      AND f.register_date BETWEEN :reg_date_from AND :reg_date_to";

    if ($status) {
        $xqry_fetchers .= " AND f.status = 'inactive'";
    }

    $xstmt_fetchers = $link_id->prepare($xqry_fetchers);
    $xstmt_fetchers->bindParam(':fetcher_from', $fetcher_from, PDO::PARAM_STR);
    $xstmt_fetchers->bindParam(':fetcher_to', $fetcher_to, PDO::PARAM_STR);
    $xstmt_fetchers->bindParam(':reg_date_from', $reg_date_from, PDO::PARAM_STR);
    $xstmt_fetchers->bindParam(':reg_date_to', $reg_date_to, PDO::PARAM_STR);
    $xstmt_fetchers->execute();

    // Arrays to store fetcher details and unique students
    $fetchers = array();
    $unique_students = array();

    // Fetcher data processing
    while ($xrs_fetcher = $xstmt_fetchers->fetch(PDO::FETCH_ASSOC)) {
        $fetcher_code = $xrs_fetcher["fetcher_code"];
        $student_code = $xrs_fetcher["studentcode"];
        $student_name = $xrs_fetcher["fullname"];
        $relationship = $xrs_fetcher["relationship"];

        // Track unique students
        if (!isset($unique_students[$student_code])) {
            $unique_students[$student_code] = array(
                'name' => $student_name,
                'relationship' => $relationship
            );
        }

        // Track fetcher details
        if (!isset($fetchers[$fetcher_code])) {
            $fetchers[$fetcher_code] = array(
                'students' => array(),
                'total_students' => 0
            );
        }

        // Add student if not already added for the fetcher
        if (!isset($fetchers[$fetcher_code]['students'][$student_code])) {
            $fetchers[$fetcher_code]['students'][$student_code] = array(
                'name' => $student_name,
                'relationship' => $relationship
            );
            $fetchers[$fetcher_code]['total_students']++;
        }
    }

    // Display fetchers and their students in the PDF
    foreach ($fetchers as $fetcher_code => $fetcher_data) {
        // Display fetcher code
        $xtop -= 20;
        $pdf->ezPlaceData(50, $xtop, "Fetcher code: {$fetcher_code}", $xfsize, 'left');
        $xtop -= 15;

        // Display students for the fetcher
        foreach ($fetcher_data['students'] as $student_code => $student_info) {
            $pdf->ezPlaceData(150, $xtop, $student_code, $xfsize, 'left');
            $pdf->ezPlaceData(250, $xtop, $student_info['name'], $xfsize, 'left');
            $pdf->ezPlaceData(400, $xtop, $student_info['relationship'], $xfsize, 'left');
            $xtop -= 15;
        }

        // Display total students for the fetcher
        $pdf->ezPlaceData(50, $xtop, "Total Students: " . $fetcher_data['total_students'], $xfsize, 'left');
        $xtop -= 15;

        // Draw line after total students
        $pdf->line(25, $xtop, 587, $xtop);
        $xtop -= 5;
    }

    // Display total count of fetchers and total unique students
    $total_fetchers = count($fetchers);
    $total_unique_students = count($unique_students);

    $xtop -= 20;
    $pdf->ezPlaceData(50, $xtop, "Total Fetchers: " . $total_fetchers, $xfsize, 'left');
    $pdf->ezPlaceData(50, $xtop - 15, "Total Students: " . $total_unique_students, $xfsize, 'left');

    // Restore state and add header to all pages
    $pdf->restoreState();
    $pdf->closeObject();
    $pdf->addObject($xheader, 'all');

    // Output PDF
    $pdf->ezStream();
} catch (Exception $e) {
    // Handle any exceptions that occur during PDF generation
    echo 'Error generating PDF: ' . $e->getMessage();
}
?>
