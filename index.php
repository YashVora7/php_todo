<!-- PHP FOR DATABASE CONNECTION & DATA CRUD-->
    <?php

        // SUCCESS POPUP VARIABLE INITIALIZATION
            $insert = false;
            $update = false;
            $delete = false;
        // SUCCESS POPUP VARIABLE INITIALIZATION OVER

        // CONNECTION TO THE DATABASE
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "notes";

            $conn = mysqli_connect($servername, $username, $password, $database);
        // CONNECTION TO THE DATABASE OVER

        // DATA DELETION
            if (isset($_GET['delete'])) {
                $sno = $_GET['delete'];
                $delete = true;
                $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
                $result = mysqli_query($conn, $sql);
            }
        // DATA DELETION OVER


        // DATA INSERTION AND UPDATING
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // DATA UPDATING
                    if (isset($_POST['snoEdit'], $_POST['titleEdit'], $_POST['descriptionEdit'])) {
                        $sno = $_POST['snoEdit'];
                        $title = $_POST['titleEdit'];
                        $description = $_POST['descriptionEdit'];
                    
                        // Prepared statement to update records
                        $sql = "UPDATE `notes` SET `title` = ?, `description` = ? WHERE `sno` = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                    
                        // Bind parameters
                        mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $sno);
                    
                        // Execute statement
                        if (mysqli_stmt_execute($stmt)) {
                            $update = true;
                        } else {
                            // Handle errors gracefully
                            echo "Error: " . mysqli_stmt_error($stmt);
                        }
                    
                        mysqli_stmt_close($stmt);
                    }
                // DATA UPDATING OVER

                // DATA INSERTION
                    $title = $_POST['title'];
                    $description = $_POST['description'];

                    $sql = "INSERT INTO `notes` (`sno`, `title`, `description`, `timestmp`) VALUES (NULL, '$title', '$description', current_timestamp());";

                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $insert = true;
                    } else {
                        echo "data not created because of this" . mysqli_error($conn);
                    }
                // DATA INSERTION OVER

            }
        // DATA INSERTION AND UPDATING OVER

    ?>
<!-- PHP FOR CONNECTION & DATA CRUD OVER-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.4/css/dataTables.dataTables.min.css">
    <style>
        * {
            outline: none;
            outline-offset: 0px;
        }
    </style>
</head>

<body>

    <!-- EDIT MODAL -->
        <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-6 mt-5">
                                        <h2 class="text-center mb-4">Add a Note</h2>
                                        <input type="hidden" name="snoEdit" id="snoEdit">
                                        <div class="form-group">
                                            <label for="title">Note Title</label>
                                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" placeholder="Enter Note">
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="form-label">Note description</label>
                                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3" placeholder="description"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary my-2">Update Note</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Understood</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- EDIT MODAL OVER-->

    <!-- PHP FOR INSERTION SUCCESS UI  -->
        <?php
            if ($insert) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Success!</strong> Your note inserted.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
            if ($update) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Success!</strong> Your note Updated.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
            if ($delete) {
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Success!</strong> Your note Deleted.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
        ?>
    <!-- PHP FOR INSERTION UI OVER-->



    <!-- FORM  -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-5">
                    <h2 class="text-center mb-4">Add a Note</h2>
                    <form action="/php_todo/index.php?update=true" method="post">
                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Note">
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Note description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary my-2">Add Note</button>
                    </form>
                </div>
            </div>
        </div>
    <!-- FORM OVER-->


    <!-- TABLE AND TABLE UI USING PHP -->
        <div class="container">

            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Title</th>
                        <th scope="col">description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>


                    <!-- PHP FOR SELECT AND TABLE UI  -->
                        <?php
                            $sql = "SELECT * FROM `notes`";

                            $result = mysqli_query($conn, $sql);

                            $sno = 0;
                            echo "<br>";

                            while ($row = mysqli_fetch_assoc($result)) {
                                $sno = $sno++;
                                echo "<tr>
                                    <th scope='row'>" . $row['sno'] . "</th>
                                    <td>" . $row['title'] . "</td>
                                    <td>" . $row['description'] . "</td>
                                    <td><button class='edit btn btn-sm btn-primary' id =" . $row['sno'] . " >Edit</button> <button class='delete btn btn-sm btn-primary' id =d" . $row['sno'] . " >Delete</button></td>
                                </tr>";
                            }
                        ?>
                    <!-- PHP FOR SELECT AND TABLE UI OVER -->


                </tbody>
            </table>

        </div>
    <!-- TABLE AND TABLE UI USING PHP -->





    <!-- BOOTSTRAP JS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP JS CDN OVER-->


    <!-- JQUERY UNCOMPRESSED CDN -->
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- JQUERY UNCOMPRESSED CDN OVER-->


    <!-- DATATABLE JS CDN -->
        <script src="//cdn.datatables.net/2.0.4/js/dataTables.min.js"></script>
    <!-- DATATABLE JS CDN OVER-->


    <!-- DATATABLE INTIALIZE SCRIPT -->
        <script>
            let table = new DataTable('#myTable');
        </script>
    <!-- DATATABLE INTIALIZE SCRIPT OVER-->
                

    <!-- EDIT AND DELETE SCRIPT  -->
        <script>
            edits = document.getElementsByClassName('edit');
            Array.from(edits).forEach((ele) => {
                ele.addEventListener('click', (e) => {
                    console.log("edit", );
                    tr = e.target.parentNode.parentNode
                    title = tr.getElementsByTagName('td')[0].innerHTML;
                    description = tr.getElementsByTagName('td')[1].innerHTML;

                    titleEdit.value = title;
                    descriptionEdit.value = description;
                    snoEdit.value = e.target.id
                    $("#editModal").modal("toggle")
                })
            })
            deletes = document.getElementsByClassName('delete');
            Array.from(deletes).forEach((ele) => {
                ele.addEventListener('click', (e) => {
                    console.log("edit", );
                    sno = e.target.id.substr(1, )

                    if (confirm("Are you sure about delete!")) {
                        console.log("yes");
                        window.location = `/php_todo/index.php?delete=${sno}`
                    } else {
                        console.log("no");
                    }
                })
            })
        </script>
    <!-- EDIT AND DELETE SCRIPT  -->



</body>

</html>