<?php


session_start();


$username = "username";
$email = "email";
$password_1 = "password_1";
$password_2 = "password_2";


$errors = array();


 $db = mysqli_connect('localhost','root','','practice') or die("Could not connect to database");



 // register users
$username = isset($_POST['username']) ? $_POST['username'] : $username;

$email = isset($_POST['email']) ? $_POST['email'] : $email;
$password_1 = isset($_POST['password_1']) ? $_POST['password_1'] : $password_1;

$password_2 = isset($_POST['password_2']) ? $_POST['password_2'] : $password_2;

//form validation

if(empty($username)) 
{
	
		array_push($errors, "username is required");
}
    
if(empty($email))  
{
	
		array_push($errors, " email is required");
}
	
if(empty($password_1)) 
{

	array_push($errors, "Password is required");
}



if($password_1 != $password_2)
{
	array_push($errors, "Password do not match");
}


//check db for existing username

$user_check_query = "SELECT *  FROM user WHERE username = '$username' or email = '$email' LIMIT 1";  


$results = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($results);



if($user) 
{

	if($user['username'] == $username)
{
	array_push($errors, "Usermame already exists");
}
	if($user ['email'] == $email)
{
	array_push($errors, "This mail id  already has a registered username");
}

}

//register the user if no errors 


if(count($errors) == 0) {

	$password = md5($password_1); // this will encrypt the password.


	$query = " INSERT INTO user  (username, email,password)  VALUES ('$username', '$email', '$password')" ;

	mysqli_query($db,$query);

	$_SESSION['username'] = $username;
	$_SESSION['success'] = "You are now logged in";

	header('location: index.php');
}

// Login user

if(isset($_POST['login_user']))
{
	$username = mysqli_real_escape_string($db,$_POST['username']);
	$password = mysqli_real_escape_string($db,$_POST['password_1']);
}

if(empty($username))
{
	array_push($errors, "Username is required");
}

if(empty($password))
{
	array_push($errors, "Password is required");
}
if(count($errors) == 0)
{
	$password = md5(password);
	$query = " SELECT * FROM user WHERE username='$username' AND password = '$password' ";
	$results = mysqli_query($db, $query);
  

  if(mysqli_num_rows($results))
  {

  	$_SESSION['username'] = $username;
  	$_SESSION['success'] ="Logged in successfully";
  	header('location: index.php');
  } else {
  	array_push($errors, "Wrong username/password");
  }
}

?>
