<?php
// Include the user class for database connection
include_once 'admin/include/class.user.php'; 
$user = new User();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Booking - Room & Facilities</title>

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css">

    <style>
        .well {
            background: rgba(0, 0, 0, 0.7);
            border: none;
            height: 200px;
            color: white;
        }
        body {
            background-image: url('images/home_bg.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        h4 {
            color: #ffbb2b;
        }
        h6 {
            color: navajowhite;
            font-family: monospace;
        }
        .button {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Banner Image -->
        <img class="img-responsive" src="images/bookTitle2.jpeg" style="width:100%; height:180px;">      
        
        <!-- Navigation Bar -->
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li class="active"><a href="room.php">Room & Facilities</a></li>
                    <li><a href="reservation.php">Online Reservation</a></li>
                    <li><a href="review.php">Review</a></li>
                    <li><a href="admin.php">Admin</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="https://www.facebook.com"><img src="images/facebook.png" alt="Facebook"></a></li>
                    <li><a href="https://www.twitter.com"><img src="images/twitter.png" alt="Twitter"></a></li>                    
                </ul>
            </div>
        </nav>

        <!-- Display Room Categories -->
        <?php
        // SQL query to fetch room categories
        $sql = "SELECT * FROM room_category";
        $result = mysqli_query($user->db, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Loop through each room category and display details
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='row'>
                    <div class='col-md-3'></div>
                    <div class='col-md-6 well'>
                        <h4>{$row['roomname']}</h4><hr>
                        <h6>No of Beds: {$row['no_bed']} {$row['bedtype']} bed(s).</h6>
                        <h6>Facilities: {$row['facility']}</h6>
                        <h6>Price: {$row['price']} tk/night.</h6>
                    </div>
                    <div class='col-md-3'>
                        <a href='booknow.php?roomname=" . urlencode($row['roomname']) . "'>
                            <button class='btn btn-primary button'>Book Now</button>
                        </a>
                    </div>
                </div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No room categories available.</div>";
        }
        ?>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
