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
    $pdf->ezPlaceData(50, $xtop, "Fetcher Code", $xfsize, 'left');
    $pdf->ezPlaceData(150, $xtop, "Fetcher Name", $xfsize, 'left');
    $pdf->ezPlaceData(300, $xtop, "Register Date", $xfsize, 'left');
    $pdf->ezPlaceData(450, $xtop, "No. of Students", $xfsize, 'left');

    $xtop -= 5;
    // Draw second line
    $pdf->line(25, $xtop, 587, $xtop);

    // Fetch data from fetchers table with filters
    $fetcher_from = $_POST['fetcher_from'];
    $fetcher_to = $_POST['fetcher_to'];
    $reg_date_from = $_POST['reg_date_from'];
    $reg_date_to = $_POST['reg_date_to'];
    $status = isset($_POST['status']) && $_POST['status'] == 'inactive';

    $xqry_fetchers = "SELECT f.fetcher_code, f.fetcher_name, f.register_date, COUNT(fs.studentcode) as student_count
                      FROM fetchers f
                      LEFT JOIN fetchers_students fs ON f.fetcher_code = fs.fetcher_code
                      WHERE f.fetcher_code BETWEEN :fetcher_from AND :fetcher_to
                      AND f.register_date BETWEEN :reg_date_from AND :reg_date_to";

    if ($status) {
        $xqry_fetchers .= " AND f.status = 'inactive'";
    }

    $xqry_fetchers .= " GROUP BY f.fetcher_code";

    $xstmt_fetchers = $link_id->prepare($xqry_fetchers);
    $xstmt_fetchers->bindParam(':fetcher_from', $fetcher_from, PDO::PARAM_STR);
    $xstmt_fetchers->bindParam(':fetcher_to', $fetcher_to, PDO::PARAM_STR);
    $xstmt_fetchers->bindParam(':reg_date_from', $reg_date_from, PDO::PARAM_STR);
    $xstmt_fetchers->bindParam(':reg_date_to', $reg_date_to, PDO::PARAM_STR);
    $xstmt_fetchers->execute();

    // Fetcher data processing
    $total_fetchers = 0;
    $total_students = 0;

    while ($xrs_fetcher = $xstmt_fetchers->fetch(PDO::FETCH_ASSOC)) {
        $fetcher_code = $xrs_fetcher["fetcher_code"];
        $fetcher_name = $xrs_fetcher["fetcher_name"];
        $register_date = $xrs_fetcher["register_date"];
        $student_count = $xrs_fetcher["student_count"];

        // Display fetcher details
        $xtop -= 20;
        $pdf->ezPlaceData(50, $xtop, $fetcher_code, $xfsize, 'left');
        $pdf->ezPlaceData(150, $xtop, $fetcher_name, $xfsize, 'left');
        $pdf->ezPlaceData(300, $xtop, $register_date, $xfsize, 'left');
        $pdf->ezPlaceData(450, $xtop, $student_count, $xfsize, 'left');

        // Update totals
        $total_fetchers++;
        $total_students += $student_count;

        $xtop -= 15;
        // Draw line after each fetcher
        // $pdf->line(25, $xtop, 587, $xtop);
        $xtop -= 5;
    }

    // Draw bottom line after all fetchers
    $pdf->line(25, $xtop, 587, $xtop);

    // Display total count of fetchers and students
    $xtop -= 20;
    // $pdf->ezPlaceData(50, $xtop, "Total Fetchers: " . $total_fetchers, $xfsize, 'left');
    // $pdf->ezPlaceData(50, $xtop - 15, "Total Unique Students: " . $total_students, $xfsize, 'left');

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
