<?php
$insert = false;

$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection Refused ".mysqli_connect_error());
}

if($_SERVER['REQUEST_METHOD']=='POST'){
        $title = $_POST['title'];
        $description = $_POST['description']; 

        $sql = "INSERT INTO `notes` (`sno`, `title`, `description`, `timestmp`) VALUES (NULL, '$title', '$description', current_timestamp());";

    $result = mysqli_query($conn, $sql);

    if($result){
        $insert = true;
    }
    else{
        echo "data not created because of thisssssssssssssssssss".mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.4/css/dataTables.dataTables.min.css">

    <style>
    *{
        outline: none;
        outline-offset: 0px;
    }
  </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Dropdown
                </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <?php
    
        if($insert){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your note inserted.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }
    
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <h2 class="text-center mb-4">Add a Note</h2>
                <form action="/todo/index.php" method="post">
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

    <div class="container">

        

<table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Title</th>
      <th scope="col">description</th>
    </tr>
  </thead>
  <tbody>



  <?php

        $sql = "SELECT * FROM `notes`";

        $result = mysqli_query($conn, $sql);

        
        // $num = mysqli_num_rows($result);
        // echo $num;
        
        $sno = 0;
        echo "<br>";

        while($row = mysqli_fetch_assoc($result)){
            $sno = $sno++;
            echo "<tr>
            <th scope='row'>".$row['sno']."</th>
            <td>".$row['title']."</td>
            <td>".$row['description']."</td>
          </tr>";
        }


        ?>
    
  </tbody>
</table>

    </div>

    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.4/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>

</body>

</html>