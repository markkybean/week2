<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Classroom</title>
</head>

<body class="bg-info-subtle">

    <div class="container">
        <div class="row">

            <!-- <div class="col-2"></div> -->


            <!-- fetcher input -->

            <div class="col-4">
                <div class="container mt-5 bg-info p-4">
                    <h2 class="mb-4">Enter Fetcher Information</h2>
                    <form action="add_fetchers.php" method="post">
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="fetcherCode-addon">Fetcher Code</span>
                            <input type="number" class="form-control" id="fetcherCode" name="fetcherCode" aria-describedby="fetcherCode-addon" required>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="fetcherName-addon">Fetcher Name</span>
                            <input type="text" class="form-control" id="fetcherName" name="fetcherName" aria-describedby="fetcherName-addon" required>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="contactNo-addon">Contact no.</span>
                            <input type="text" class="form-control" id="contactNo" name="contactNo" aria-describedby="contactNo-addon" required>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="registerDate-addon">Register Date</span>
                            <input type="date" class="form-control datepicker" id="registerDate" name="registerDate" aria-describedby="registerDate-addon" required>
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="active">
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <p>Student Code</p>
                                    <?php
                                    require_once('db_config.php');
                                    $qry = "SELECT * FROM studentfile";
                                    $stmt = $link_id->prepare($qry);
                                    $stmt->execute();
                                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $numStudents = count($students);

                                    for ($i = 1; $i <= $numStudents; $i++) {
                                        echo '<select class="form-control mt-2 student-select" id="studentCode' . $i . '" name="studentCode' . $i . '" onchange="filterStudentCodes()">';
                                        echo '<option value="">Select Student</option>';
                                        foreach ($students as $student) {
                                            echo '<option value="' . htmlspecialchars($student["studentcode"]) . '">' . htmlspecialchars($student["studentcode"]) . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </div>
                                <div class="col-6">
                                    <p>Relationship</p>
                                    <?php
                                    for ($i = 1; $i <= $numStudents; $i++) {
                                        echo '<input type="text" class="form-control mt-2" id="relationship' . $i . '" name="relationship' . $i . '">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Save</button>
                    </form>
                </div>


            </div>


            <!-- student input -->

            <div class="col-4">
                <div class="container mt-5 p-4 bg-info">
                    <h2 class="mb-4">Enter Student Information</h2>
                    <form action="add_students.php" method="post">
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="studentcode-addon">Student Code</span>
                            <input type="number" class="form-control" id="studentcode" name="studentcode" aria-describedby="studentcode-addon" required>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="fullname-addon">Full Name</span>
                            <input type="text" class="form-control" id="fullname" name="fullname" aria-describedby="fullname-addon" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>

            <!-- call fetchers  -->

            <div class="col-4">
                <div class="container mt-5 p-4 bg-info">
                    <h2 class="mb-4">Fetcher File Report</h2>
                    <form id="report-form" method="post" target="_blank">
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="fetcher-from-addon">Fetcher From:</span>
                            <input type="number" class="form-control" id="fetcher-from" name="fetcher_from" aria-describedby="fetcher-from-addon" required>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="fetcher-to-addon">Fetcher To:</span>
                            <input type="text" class="form-control" id="fetcher-to" name="fetcher_to" aria-describedby="fetcher-to-addon" required>
                        </div>

                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="reg-date-from-addon">Reg. Date From:</span>
                            <input type="date" class="form-control" id="reg-date-from" name="reg_date_from" aria-describedby="reg-date-from-addon" required>
                        </div>
                        <div class="input-group flex-nowrap mt-3">
                            <span class="input-group-text" id="reg-date-to-addon">Reg Date To:</span>
                            <input type="date" class="form-control" id="reg-date-to" name="reg_date_to" aria-describedby="reg-date-to-addon" required>
                        </div>

                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="inactive">
                            <label class="form-check-label" for="status">Display Inactive fetcher only</label>
                        </div>

                        <div class="row mt-3 ms-4">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="report-type-detailed" name="report_type" value="detailed" checked>
                                    <label class="form-check-label" for="report-type-detailed">Detailed</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="report-type-summarized" name="report_type" value="summarized">
                                    <label class="form-check-label" for="report-type-summarized">Summarized</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Print</button>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.getElementById('report-form');
                        const reportTypeInputs = document.querySelectorAll('input[name="report_type"]');

                        reportTypeInputs.forEach(input => {
                            input.addEventListener('change', function() {
                                if (this.value === 'detailed') {
                                    form.action = 'pdf_detailed.php';
                                } else if (this.value === 'summarized') {
                                    form.action = 'pdf_summarized.php';
                                }
                            });
                        });

                        // Set the initial form action based on the default checked radio button
                        const initialChecked = document.querySelector('input[name="report_type"]:checked');
                        if (initialChecked) {
                            form.action = initialChecked.value === 'detailed' ? 'pdf_detailed.php' : 'pdf_summarized.php';
                        }
                    });
                </script>



            </div>

        </div>
    </div>


    <!-- table -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-8">
                <div class="container mt-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <!-- <th scope="col">ID</th> -->
                                    <th scope="col">ID</th>
                                    <th scope="col">Fetcher Code</th>
                                    <th scope="col">Fetcher Name</th>
                                    <th scope="col">Contact no.</th>
                                    <th scope="col">Register Date</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody id="fetchers">
                                <?php
                                require_once('db_config.php');
                                $xqry = "SELECT * FROM fetchers";
                                $xstmt = $link_id->prepare($xqry);
                                $xstmt->execute();
                                while ($xrs = $xstmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($xrs["id"]) ?></td>
                                        <td><?php echo htmlspecialchars($xrs["fetcher_code"]) ?></td>
                                        <td><?php echo htmlspecialchars($xrs["fetcher_name"]) ?></td>
                                        <td><?php echo htmlspecialchars($xrs["contact_no"]) ?></td>
                                        <td><?php echo htmlspecialchars($xrs["register_date"]) ?></td>
                                        <td>
                                            <?php
                                            if ($xrs["status"] == 1) {
                                                echo "Active";
                                            } else {
                                                echo "Inactive";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="container mt-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <!-- <th scope="col">ID</th> -->
                                    <th scope="col">Student Code</th>
                                    <th scope="col">Full Name</th>
                                </tr>
                            </thead>
                            <tbody id="students">
                                <?php
                                require_once('db_config.php');
                                $xqry = "SELECT * FROM studentfile";
                                $xstmt = $link_id->prepare($xqry);
                                $xstmt->execute();
                                while ($xrs = $xstmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($xrs["studentcode"]) ?></td>
                                        <td><?php echo htmlspecialchars($xrs["fullname"]) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <script>
        function filterStudentCodes() {
            const selects = document.querySelectorAll('.student-select');
            const selectedValues = Array.from(selects).map(select => select.value);

            selects.forEach(select => {
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value && selectedValues.includes(option.value) && option.value !== select.value) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', filterStudentCodes);
    </script>
</body>

</html>