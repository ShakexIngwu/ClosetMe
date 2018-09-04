<?php

require 'password_compat/lib/password.php';

if(isset($_POST['signin'])){
	echo "Start processing...";
	$servername = 'localhost';
	$dbname = 'webdata';
	$email = !empty($_POST['email']) ? $_POST['email'] : null;
	$passwordAttempt = !empty($_POST['password']) ? $_POST['password'] : null;
    try{
	$pdoOptions = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_EMULATE_PREPARES => false
        );
        $pdo = new PDO(
            "mysql:host=" . $servername . ";dbname=" . $dbname, 'ke', 'shakexin', $pdoOptions
    );
	$sql = "SELECT first_name, password FROM users_info WHERE email = :email";
	$stmt = $pdo->prepare($sql);

	$stmt->bindValue(':email', $email);

	$stmt->execute();

	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	echo "database password: " . $user['password'] . "<br>";
	echo "input password: " . $passwordAttempt . "<br>";
	if ($user === false) {
		die('Can not find this user.');
	}else {
		$validPassword = password_verify($passwordAttempt, $user['password']);
		echo "validPassword: " . $validPassword . "<br>";
		if ($validPassword){ 
			$_SESSION['first_name'] = $user['first_name'];
			$_SESSION['logged_in'] = time();

			header('Location: info.php');
			exit;
		}else {
			die ('Incorrect username/password combination!');
		}
	}
    }catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
