<?php

require 'password_compat/lib/password.php';

if(isset($_POST['submit'])){
    echo "Start storing...";
    $servername = "localhost";
    $dbname = "webdata";
    $fname = !empty($_POST['fname']) ? trim($_POST['fname']) : null;
    $lname = !empty($_POST['lname']) ? trim($_POST['lname']) : null;
    $mobile = !empty($_POST['mobile']) ? trim($_POST['mobile']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $vpassword = $_POST['vpassword'];
   
  try{
    $pdoOptions = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_EMULATE_PREPARES => false
    );
    $pdo = new PDO(
        "mysql:host=" . $servername . ";dbname=" . $dbname, 'ke', 'shakexin', $pdoOptions
    );
    
    $sql = "SELECT COUNT(email) As num FROM users_info WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('email', $email);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        die('The username already exists.');
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));

    $sql = "INSERT INTO users_info (first_name, last_name, mobile, email, password) VALUES (:fname, :lname, :mobile, :email, :password)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':fname', $fname);
    $stmt->bindValue(':lname', $lname);
    $stmt->bindValue(':mobile', $mobile);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue('password', $passwordHash);

    $result = $stmt->execute();
 
    if($result) {
        echo "Data stored successfully.";
    }
  }
  catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
   
}
?>
