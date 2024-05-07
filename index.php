<?php
$insert = false;
if (isset($_POST['name'])) {
    // Get connection variables from environment variables
    $server = getenv('DB_SERVER');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');
    $database = getenv('DB_DATABASE');
    $port = getenv('DB_PORT');

    // Create a database connection
    $con = mysqli_connect($server, $username, $password, $database, $port);

    // Check for connection success
    if (!$con) {
        die("Connection to this database failed due to: " . mysqli_connect_error());
    }

    // Collect post variables
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $desc = $_POST['desc'];

    // Prepare SQL query (using prepared statements to prevent SQL injection)
    $sql = "INSERT INTO `user_detail`(`name`, `age`, `gender`, `email`, `phone`, `other`, `dt`) 
            VALUES (?, ?, ?, ?, ?, ?, current_timestamp())";

    // Prepare statement
    $stmt = mysqli_prepare($con, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sissis", $name, $age, $gender, $email, $phone, $desc);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $insert = true;
    } else {
        echo "ERROR: Unable to execute query: $sql. " . mysqli_error($con);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Fetch data from the database
    $getemail = "SELECT name, email FROM user_detail WHERE email = ?";
    $stmt_getemail = mysqli_prepare($con, $getemail);
    mysqli_stmt_bind_param($stmt_getemail, "s", $email);
    mysqli_stmt_execute($stmt_getemail);
    $result = mysqli_stmt_get_result($stmt_getemail);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            echo "<h1>Thankyou for applying: " . $row["name"]  . "<br>";
            echo "Your email is ";
            echo "Email: " . $row["email"] . "<br>";
            echo "<h1> Your form is successfully submitted ";
            echo "<br> ";
            echo "<button onclick=\"logout()\">Go Back</button>";
        } else {
            echo "No user found with the provided email.";
        }
    } else {
        echo "Error executing query: " . mysqli_error($con);
    }





    // Close the statement
    mysqli_stmt_close($stmt_getemail);

    // Close the database connection
    mysqli_close($con);
}
?>
<script>
    function logout() {
        // Redirect to the form page
        window.location.href = "form.php";
    }
</script>