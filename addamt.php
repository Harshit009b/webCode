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
        <form action="addamt.php" method="POST">
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
     
            <label for="mobile">Phone no.</label>
            <input type="number" placeholder="Phone no." name="mobile">
            <label for="amount">Amount</label>
            <input type="number" name="amount" placeholder="Amount">
            <input type="submit" value="Submit" id="submit-payment">
        </form>
        <div class="payment-info">
            <p>You are investing amount with knowing our <a href="./privacy.html">privacy and policy</a>and <a href="">terms and conditions</a> for the money. please read terms and condtion before investing the amount , there will be no queries after submitting the amount.</p>
           <div class="imgs">
           <img src="https://www.narendrasisodiya.com/Universal-UPI-QR-Code-Generator/upi-icon-black.png" alt="">
           <img src="https://1000logos.net/wp-content/uploads/2023/03/Paytm-logo.png" alt="">
           <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/PhonePe_Logo.svg/2560px-PhonePe_Logo.svg.png" alt="">
           </div>
        </div>
    </div>
</body>
</html>
<!-- INSERT INTO `add-wallet` (`username`, `phone no.`, `amount`) VALUES ('harshit', '451245152', '1500'); -->
<?php
$server = "localhost";
$username = "u896726942_investcoins";
$password = "shivasweeta989912#@aA";
$database = "u896726942_shivam"; // Specify the database name

// Creating a database connection
$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("Connection to the database has failed: " . mysqli_connect_error());
} else {
    // Assuming this code is part of a form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cust = $_POST['username'];
        $mobileno = $_POST['mobile'];
        $amount = $_POST['amount'];

        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM `add-wallet` WHERE username = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $cust);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows == 1) {
            // User exists, update the amount
            $user = $result->fetch_assoc();
            $currentAmount = $user["amount"];

            // Calculate the new amount
            $newTotalAmount = $currentAmount + $amount;

            // Update the user's amount in the database
            $updateQuery = "UPDATE `add-wallet` SET `amount` = ? WHERE `username` = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ss", $newTotalAmount, $cust);

            if (mysqli_stmt_execute($updateStmt)) {
                $_SESSION["amount"] = $newTotalAmount;
               
                header("Location: dashboard.php"); // Redirect to the user's profile page
                exit; // Make sure to exit after sending the redirect header
            } else {
                echo "Error updating data: " . mysqli_error($con);
            }
        } else {
            // User does not exist, insert a new row
            $insertQuery = "INSERT INTO `add-wallet` (`username`, `amount`) VALUES (?, ?)";
            $insertStmt = mysqli_prepare($con, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ss", $cust, $amount);

            if (mysqli_stmt_execute($insertStmt)) {
                $_SESSION["amount"] = $amount;
              
                header("Location: dashboard.php"); // Redirect to the user's profile page
                exit; // Make sure to exit after sending the redirect header
            } else {
                echo "Error inserting data: " . mysqli_error($con);
            }
        }

        // Close the statements and database connection
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($updateStmt);
        mysqli_stmt_close($insertStmt);
        mysqli_close($con);
    }
}
?>



