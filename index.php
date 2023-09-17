







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitfinex </title>
    <link rel="stylesheet" href="./public/utils2.css">
    <link rel="stylesheet" href="./public/utils3.css">

    <link rel="stylesheet" href="./public/index.css">
    <link type="text/css" rel="stylesheet" media="(max-width:850px)" href="../public/indexRes1.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar--logo logo">BITFINEX</div>
        <div class="login--pallet">
            <li><a href="./templates/login.php">Login</a></li>
      
        </div>
    </div>
    

    <div class="main">
        <h2>Sign Up</h2>
        <form action="index.php" method="POST">
    

            <label for="email">Mobile</label>
            <input type="number" placeholder="Mobile" name="email" value="">
            <label for="cust">Username</label>
            <input type="text" placeholder="Username" name="cust" value="">
            <label for="lock">Password</label>
            <input type="password" placeholder="Password" name="lock" value="">
            <p>By signing up I confirm I`ve created a strong and unique password not being used anywhere else and I agree to the <a href="">terms and conditions</a> and <a href="./privacy.html">privacy and policy</a> and anti-spam policy.</p>
            <input type="submit" id="sign-up-submit">
        </form>
    </div>
</body>
</html>
<!-- INSERT INTO `sign-up` (`serial.no`, `email`, `username`, `password`, `date`) VALUES ('1', 'harshit@boss.gmail.com', 'harshit bhardwaj', 'Helloworld@', NULL); -->

<!-- INSERT INTO `sign-up` (`serial.no`, `email`, `username`, `password`) VALUES (NULL, 'harshit@boss.gmail.com', 'harshit baap', 'helloworld@'); -->

<?php

$server= "localhost";
$username="u896726942_investcoins";
$password="shivasweeta989912#@aA";

//creating a database connection
$con =mysqli_connect($server,$username,$password);

if(!$con){
    die("connection to the database is died:" . mysqli_connect_error);

}
else{

        $cust= $_POST['cust'];
        $email= $_POST['email'];
        $lock= $_POST['lock'];


        $sql="INSERT INTO `u896726942_shivam`.`sign-up` (`serial.no`, `mobile`, `username`, `password`) VALUES (NULL, '$email', '$cust', '$lock');";

        if($con->query($sql)==true){

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
          
        
                header("Location: ./templates/login.php");
                exit; // Make sure to exit after sending the redirect header
            }
        }
   
}
?>
