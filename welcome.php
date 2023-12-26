<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Database connection
    $conn = new mysqli('localhost:3307', 'root', '', 'users');
    if ($conn->connect_error) {
        echo "$conn->connect_error";
        die("Connection Failed : " . $conn->connect_error);
    } else {
        // Check if user exists with the provided email and password
        $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();

        if ($stmt->error) {
            echo "Query failed: " . $stmt->error;
        } else {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Store user data in the session for future use
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                // Redirect to the welcome page
                header("Location: welcome.php");
                exit();
            } else {
                echo "Invalid email or password. Please try again.";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!-- welcome.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome Page</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
</head>
<body>
    <div class="container">
        <div class="row col-md-6 offset-md-3">
            <div class="panel panel-success">
                <div class="panel-heading text-center">
                    <h1>Welcome</h1>
                </div>
                <div class="panel-body">
                    <p>Welcome, <?php echo $_SESSION['user_email']; ?>!</p>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
