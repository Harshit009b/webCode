<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitfinex </title>
    <link rel="stylesheet" href="../public/utils2.css">
    <link rel="stylesheet" href="../public/login.css">
    <link type="text/css" rel="stylesheet" media="(max-width:850px)" href="../public/indexRes1.css">
    <link type="text/css" rel="stylesheet" media="(max-width:850px)" href="../public/loginRes1.css">
  
   
   
</head>
<body>
    <div class="navbar">
        <div class="navbar--logo logo">BITFINEX</div>
        <div class="login--pallet">
            <li><a href="../index.php">Sign up</a></li>
      
        </div>
    </div>
    

    <div class="main">
        <h2>Log In</h2>
        <form action="login.php" method="POST">
           
            <label for="name">Username</label>
            <input type="text" placeholder="Name" name="username">
            <label for="password">Password</label>
            <input type="password" placeholder="Password" name="password">
          
            <input type="submit" id="log-in-submit" name="submit">
        </form>
    </div>
</body>
</html>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to your user database
    $user_db = new mysqli("localhost", "u896726942_investcoins", "shivasweeta989912#@aA", "u896726942_shivam");

    // Check connection
    if ($user_db->connect_error) {
        die("Connection failed: " . $user_db->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to check if the user exists
    $query = "SELECT * FROM `sign-up` WHERE username = '$username' AND password = '$password'";
    $result = $user_db->query($query);

    if ($result->num_rows == 1) {
        // User exists and credentials are correct
        $user = $result->fetch_assoc();
        $_SESSION["username"] = $user["username"];

        // Connect to your wallet database
        $wallet_db = new mysqli("localhost", "root", "", "shivam");

        // Check connection
        if ($wallet_db->connect_error) {
            die("Connection failed: " . $wallet_db->connect_error);
        }

        // Query to check if the user exists in the wallet table
        $wallet_query = "SELECT `amount` FROM `add-wallet` WHERE username = '$username'";
        $wallet_result = $wallet_db->query($wallet_query);

        if ($wallet_result->num_rows == 1) {
            // User exists in the wallet table, fetch wallet amount
            $wallet_data = $wallet_result->fetch_assoc();
            $_SESSION["amount"] = $wallet_data["amount"];
        } else {
            // User does not exist in the wallet table, set wallet amount to 0
            $_SESSION["amount"] = 0;
        }

        // Close the wallet database connection
        $wallet_db->close();

        if (isset($_SESSION["username"])) {
            $name = $_SESSION["username"];
            
            // Query to fetch amount2 from asset-purchase table
            $query = "SELECT `amount2` FROM `asset-purchase` WHERE username = ?";
            $stmt = mysqli_prepare($user_db, $query);
            mysqli_stmt_bind_param($stmt, "s", $name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result->num_rows == 1) {
                // User exists, fetch and set the amount2 value in the session
                $user = $result->fetch_assoc();
                $currentAmount2 = $user["amount2"];
                $_SESSION["amount2"] = $currentAmount2;
            } else {
                // User does not exist in asset-purchase table, set amount2 to 0
                $_SESSION["amount2"] = 0;
            }
            
            header("Location: dashboard.php"); // Redirect to the user's profile page
            exit;
        } else {
            echo "";
        }

        header("Location: home.html"); // Redirect to the user's profile page
    } else {
        // Invalid login credentials
        echo "Invalid username or password. <a href='login.php'>Try again</a>";
    }

    // Close the user database connection
    $user_db->close();





// Check if the user is logged in and the session variable is set
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Creating a database connection
    $server = "localhost";
    $dbUsername = "u896726942_investcoins"; // Use a different variable name to store the database username
    $password = "shivasweeta989912#@aA";
    $database = "u896726942_shivam"; // Specify the database name

    $con = mysqli_connect($server, $dbUsername, $password, $database); // Use $dbUsername instead of $username

    if (!$con) {
        die("Connection to the database has failed: " . mysqli_connect_error());
    }

    // Query to fetch the user's after-intrest value
    $intrestQuery = "SELECT `after-intrest` FROM `intrest` WHERE `username` = ?";
    $intrestStmt = mysqli_prepare($con, $intrestQuery);
    mysqli_stmt_bind_param($intrestStmt, "s", $username);

    if (mysqli_stmt_execute($intrestStmt)) {
        $intrestResult = mysqli_stmt_get_result($intrestStmt);

        if ($intrestResult->num_rows == 1) {
            $intrestUser = $intrestResult->fetch_assoc();
            $previousAmount = $intrestUser["after-intrest"];
            // Format the amount as needed
            $_SESSION["after-intrest"] = $previousAmount;
        } else {
            // No record found for the user, display 0
            $_SESSION["after-intrest"] = 0;
        }
    } else {
        echo "Error executing the query: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);

    // Other content for the dashboard goes here
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: dashboard.php");
    exit();
}



}
?>


