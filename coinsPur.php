<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BITFINEX</title>
 <link rel="stylesheet" href="../public/coinPur.css">
 <link type="text/css" rel="stylesheet" media="(max-width:890px)" href="../public/coinRes1.css">
 <link type="text/css" rel="stylesheet" media="(max-width:550px)" href="../public/coinRes2.css">



</head>
<body>
    <div class="main">
        <form action="coinsPur.php" method="POST">
            <h1>Payment Information</h1>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="username" value="<?php  
            session_start();
           
            if (isset($_SESSION["username"])) {
                 $name = $_SESSION["username"];
                    echo "$name";

             } else {
                 echo "";
                 }
     ?>">
     
            <label for="Asset-name">Asset name</label>
            <input type="text" placeholder="Asset name" name="coin-name" id="asset-name">
            <label for="amount">Amount</label>
            <input type="text" name="amount2" placeholder="Amount">
            <input type="submit" value="Submit" id="submit-payment">
        </form>
        <div class="payment-info">
            <p>You are investing amount with knowing our <a href="./privacy.html">privacy and policy</a> and <a href="">terms and conditions</a> for the money. please read terms and condtion before investing the amount , there will be no queries after submitting the amount.</p>
           <div class="imgs">
           <img src="https://www.narendrasisodiya.com/Universal-UPI-QR-Code-Generator/upi-icon-black.png" alt="">
           <img src="https://1000logos.net/wp-content/uploads/2023/03/Paytm-logo.png" alt="">
           <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/PhonePe_Logo.svg/2560px-PhonePe_Logo.svg.png" alt="">
           </div>
        </div>
    </div>

    <script src="../javascript/coinPur.js" type="module"></script>
    <!-- <script src="../javascript/home.js" type="module"></script> -->
</body
<!-- INSERT INTO `asset-purchase` (`username`, `asset-name`, `amount`) VALUES ('harshit111', 'XMR', '5000'); -->
<?php

$server = "localhost";
$username = "u896726942_investcoins";
$password = "shivasweeta989912#@aA";
$database = "u896726942_shivam"; / Specify the database name

// Creating a database connection
$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("Connection to the database has failed: " . mysqli_connect_error());
} else {

    // Assuming this code is part of a form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cust = $_POST['username'];
        $assetName = $_POST['coin-name'];
        $amount2 = $_POST['amount2'];

        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM `asset-purchase` WHERE username = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $cust);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows == 1) {
            // User exists, update the amount
            $user = $result->fetch_assoc();
            $currentAmount = $user["amount2"];

            // Calculate the new amount
            $newTotalAmount = $currentAmount + $amount2;

            // Update the user's amount in the database
            $updateQuery = "UPDATE `asset-purchase` SET `amount2` = ? WHERE `username` = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ss", $newTotalAmount, $cust);

            if (mysqli_stmt_execute($updateStmt)) {
                $_SESSION["amount2"] = $newTotalAmount;
                $_SESSION["asset-name"] = $assetName;

                // Check if the user exists in the 'intrest' table
                $intrestQuery = "SELECT * FROM `intrest` WHERE `username` = ?";
                $intrestStmt = mysqli_prepare($con, $intrestQuery);
                mysqli_stmt_bind_param($intrestStmt, "s", $cust);
                mysqli_stmt_execute($intrestStmt);
                $intrestResult = mysqli_stmt_get_result($intrestStmt);

                if ($intrestResult->num_rows == 1) {
                    // User exists in 'intrest' table, calculate interest
                    $intrestUser = $intrestResult->fetch_assoc();
                    $previousAmount = $intrestUser["previous-balance"];
                    $interest = 0.10 * $previousAmount; // Calculate interest as 10% of previous amount

                    // Update 'intrest' table with new values
                    $updateIntrestQuery = "UPDATE `intrest` SET `previous-balance` = ?, `after-intrest` = ? WHERE `username` = ?";
                    $updateIntrestStmt = mysqli_prepare($con, $updateIntrestQuery);
                    $newPreviousAmount = $previousAmount + $amount2; // Update the previous amount
                    $newAfterInterest = $newPreviousAmount + $interest; // Calculate the new amount after interest
                $_SESSION["after-intrest"]=$newAfterInterest;

                    mysqli_stmt_bind_param($updateIntrestStmt, "dds", $newPreviousAmount, $newAfterInterest, $cust);

                    if (mysqli_stmt_execute($updateIntrestStmt)) {
                        // Redirect to the user's profile page
                        header("Location: dashboard.php");
                        exit; // Make sure to exit after sending the redirect header
                    } else {
                        echo "Error updating interest data: " . mysqli_error($con);
                    }
                } else {
                    // User does not exist in 'intrest' table, insert a new row
                    $insertIntrestQuery = "INSERT INTO `intrest` (`username`, `previous-balance`, `after-intrest`) VALUES (?, ?, ?)";
                    $insertIntrestStmt = mysqli_prepare($con, $insertIntrestQuery);
                    $newPreviousAmount = $amount2; // Initial previous amount
                    $newAfterInterest = $newPreviousAmount + (0.10 * $newPreviousAmount); // Calculate the new amount after interest
                $_SESSION["after-intrest"]=$newAfterInterest;

                    mysqli_stmt_bind_param($insertIntrestStmt, "sdd", $cust, $newPreviousAmount, $newAfterInterest);

                    if (mysqli_stmt_execute($insertIntrestStmt)) {
                        // Redirect to the user's profile page
                        header("Location: dashboard.php");
                        exit; // Make sure to exit after sending the redirect header
                    } else {
                        echo "Error inserting interest data: " . mysqli_error($con);
                    }
                }
            } else {
                echo "Error updating data: " . mysqli_error($con);
            }
        } else {
            // User does not exist, insert a new row
            $insertQuery = "INSERT INTO `asset-purchase` (`username`, `asset-name`, `amount2`) VALUES (?, ?, ?)";
            $insertStmt = mysqli_prepare($con, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "sss", $cust, $assetName, $amount2);

            if (mysqli_stmt_execute($insertStmt)) {
                $_SESSION["amount2"] = $amount2;
                $_SESSION["asset-name"] = $assetName;

                // Insert a new row in 'intrest' table for this user with initial values
                $insertIntrestQuery = "INSERT INTO `intrest` (`username`, `previous-balance`, `after-intrest`) VALUES (?, ?, ?)";
                $insertIntrestStmt = mysqli_prepare($con, $insertIntrestQuery);
                $newPreviousAmount = $amount2; // Initial previous amount
                $newAfterInterest = $newPreviousAmount + (0.10 * $newPreviousAmount); // Calculate the new amount after interest
                mysqli_stmt_bind_param($insertIntrestStmt, "sdd", $cust, $newPreviousAmount, $newAfterInterest);
                $_SESSION["after-intrest"]=$newAfterInterest;

                if (mysqli_stmt_execute($insertIntrestStmt)) {
                    // Redirect to the user's profile page
                    header("Location: dashboard.php");
                    exit; // Make sure to exit after sending the redirect header
                } else {
                    echo "Error inserting interest data: " . mysqli_error($con);
                }
            } else {
                echo "Error inserting data: " . mysqli_error($con);
            }
        }

        // Close the statements and database connection
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($updateStmt);
        mysqli_stmt_close($insertStmt);
        mysqli_stmt_close($intrestStmt);
        mysqli_stmt_close($updateIntrestStmt);
        mysqli_stmt_close($insertIntrestStmt);
        mysqli_close($con);
    } else {
        // This code block will run when a new user logs in, so initialize the session variables here.
        $_SESSION["amount2"] = 0; // Initialize amount2 to 0 for new user
        $_SESSION["asset-name"] = "";
        $_SESSION["after-intrest"]=0;
        // Initialize asset-name to an empty string for new user
    }
}

?>
