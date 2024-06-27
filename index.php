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
            <div class="col-2"></div>
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
                                        echo '<select class="form-control mt-2" id="studentCode' . $i . '" name="studentCode' . $i . '" >';
                                        echo '<option value="">Select Student '  . '</option>';
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
                                        echo '<input type="text" class="form-control mt-2" id="relationship" name="relationship" required>';
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    $(document).ready(function() {
        $('#addStudent').click(function() {
            $('#students').append('<div class="form-row mb-2"><div class="col"><input type="text" class="form-control" name="studentcode[]" placeholder="Student Code"></div><div class="col"><input type="text" class="form-control" name="relationship[]" placeholder="Relationship"></div></div>');
        });

        $('#fetcherForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'save_fetcher.php',
                type: 'post',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                }
            });
        });
    });
</script>
</body>

</html>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetcher File</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <form id="fetcherForm">
        <div class="form-group">
            <label for="fetcher_code">Fetcher Code:</label>
            <input type="text" class="form-control" id="fetcher_code" name="fetcher_code">
        </div>
        <div class="form-group">
            <label for="fetcher_name">Fetcher Name:</label>
            <input type="text" class="form-control" id="fetcher_name" name="fetcher_name">
        </div>
        <div class="form-group">
            <label for="contact_no">Contact No.:</label>
            <input type="text" class="form-control" id="contact_no" name="contact_no">
        </div>
        <div class="form-group">
            <label for="register_date">Registered Date:</label>
            <input type="date" class="form-control" id="register_date" name="register_date">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="status" name="status">
            <label class="form-check-label" for="status">Active</label>
        </div>
        <div class="mt-3">
            <label>Students:</label>
            <div id="students">
                <div class="form-row mb-2">
                    <div class="col">
                        <input type="text" class="form-control" name="studentcode[]" placeholder="Student Code">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="relationship[]" placeholder="Relationship">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="addStudent">Add Student</button>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addStudent').click(function() {
            $('#students').append('<div class="form-row mb-2"><div class="col"><input type="text" class="form-control" name="studentcode[]" placeholder="Student Code"></div><div class="col"><input type="text" class="form-control" name="relationship[]" placeholder="Relationship"></div></div>');
        });

        $('#fetcherForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'save_fetcher.php',
                type: 'post',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                }
            });
        });
    });
</script>
</body>
</html>
 -->