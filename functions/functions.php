<?php

/*******************-----helper function-----********************/

function clean($string){ 

	return htmlentities($string);
}


function redirect($location){

	return header("Location:{$location}");
}


function set_message($message) {

	if(!empty($message )){

	$_SESSION['message'] = $message;
	}else{

	$message="";

	}
}


function display_message(){

	if(isset($_SESSION['message'])){

	echo $_SESSION['message'];

	unset($_SESSION['message']);
	}
}


function token_generator(){

	$token= $_SESSION['token'] = md5 (uniqid(mt_rand(),true));

	return $token;
}


function validation_errors($error_message)
{

$error_message = <<<DELIMITER

<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	<strong>Warning!</strong> $error_message;
</div>
<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 5000);
</script>
DELIMITER;

return $error_message;
}

/***************User semail exists function*******************/

function email_exists($email) {

	$sql="SELECT id FROM users WHERE email = '$email'";

	$result = query($sql);

if(row_count($result) == 1) {

	return true;

}else{

	return false;
	}

}


function details_exits($email){

	$sql="SELECT id FROM users WHERE email = '$email' AND active = 0";

	$result = query($sql);

if(row_count($result) == 1) {

	return true;

}else{

	return false;
	}

}


function check_approve($email){

	$sql="SELECT id FROM users WHERE email = '$email' AND active = 1";

	$result = query($sql);

if(row_count($result) == 1) {

	return true;

}else{

	return false;
	}

}



function login_details_exits($email){

	$sql="SELECT id FROM users WHERE email = '$email' AND active = 1";

	$result = query($sql);

if(row_count($result) == 1) {

	return true;

}else{

	return false;
	}

}


function username_exists($username) {

	$sql="SELECT id FROM users WHERE username = '$username'";

	$result = query($sql);

	if(row_count($result)==1) {

	return true;

	}else{

	return false;
}
}




function send_email($email,$subject,$msg, $headers ){

	return mail($email, $subject, $msg, $headers);

}


function validEmail($email){
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
            return false;
        }
    }
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                return false;
            }
        }
    }

    return true;
}



/*********************----Validationfunction----***************************/

function validate_user_registration(){

	$errors=[];
	$min = 3;
	$max = 20;

if($_SERVER['REQUEST_METHOD']=="POST"){

	$first_name	     =clean($_POST['first_name']);
	$last_name	     =clean($_POST['last_name']);
	$username		 =clean($_POST['username']);
	$email	  		 =clean($_POST['email']);
	$password  		 =clean($_POST['password']);

$confirm_password=clean($_POST['confirm_password']);


if(strlen($first_name) < $min){

	$errors[] = "Your first name cannot be less than {$min} characters";

}


if(strlen($first_name) > $max){

	$errors[] = "Your first name cannot be more than {$max} characters";

}


if(strlen($last_name) < $min){

	$errors[] = "Your last name cannot be less than {$min} characters";

}


if(strlen($last_name) > $max){

$errors[] = "Your last name cannot be more than {$max} characters";

}


if(strlen($username) < $min){

	$errors[] = "Your username cannot be less than {$min} characters";

}


if(strlen($username) > $max){

	$errors[] = "Your username cannot be more than {$max} characters";

}


if(username_exists($username)){

	$errors[]="Sorry that username is already is taken";

}



if(email_exists($email)){

	$errors[]="Sorry that email already is registered";
}
	

if(strlen($email)<$min) {

	$errors[]="Your email cannot be less than {$min} characters";
}



if($password!==$confirm_password){

	$errors[]="Your password fields do not match";
}


if(!empty($errors)) {

	foreach ($errors as $error) {

	echo validation_errors($error);

	}
}else{
	if(register_user($first_name,$last_name,$username,$email,$password)){
			
		set_message("<p class='bg-success text-center'> Please check your email or spam folder for activation link </p>");

		redirect("index.php");
		}else{
		set_message("<p class='bg-danger text-center'> Sorry! We could not register the user </p>");

		redirect("index.php");

		}
	}
}
}


/******************-------Register user function-------**********************/


function register_user($first_name,$last_name,$username,$email,$password) {

	$first_name = escape($first_name);
	$last_name= escape($last_name);
	$username = escape($username);
	$email = escape($email);
	$password = escape($password);

if(email_exists($email)) {

	return false;

}else if (username_exists($username)) {

	return false;

}else{

	$password = md5($password);
	$validation_code = md5($username);

$sql = "INSERT INTO users(first_name,last_name,username,email,password,validation_code,active)";

$sql.="VALUES('$first_name','$last_name','$username','$email','$password','$validation_code',0)";

	$result=query($sql);
	confirm($result);


require 'phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'example_email@gmail.com';                 // SMTP username
$mail->Password = 'email_password';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to
$mail->setFrom('example_email@gmail.com', 'Online Discussion Forum');
$mail->addAddress($email);  
$mail->addReplyTo('no-reply@gmail.com', 'Np-reply');

$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = 'Activate Account';
$mail->Body    = "Please click <a href='localhost/login/activate.php?email=$email&code=$validation_code'>this link </a>to activate your Account";
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}


	return true;
	}
}



/*8888888888888888888888888888*/


/*****************-----Activate user function-----********************/

function activate_user(){
 
	if($_SERVER['REQUEST_METHOD'] == "GET") {

		if(isset($_GET['email'])) {
 
			$email = clean ($_GET['email']);

			$validation_code = clean($_GET['code']);

			$sql = "SELECT * FROM users WHERE email = '".escape($_GET['email'])."' AND validation_code = '".escape($_GET['code'])."' AND active = 0";

			$result = query($sql);
			confirm($result);

	if(row_count($result)==1) {

		$sql2 = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '".escape($email)."' AND validation_code = '".escape($validation_code)."' ";

		$result2 = query($sql2);
		confirm($result2);

		
		set_message("<div class='alert alert-success'>Your account has been activated please 
     	<strong>login</strong></div> 
		<script type='text/javascript'>
   		window.setTimeout(function() {
    	$('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    	});}, 2500);
		</script>");

		redirect("login.php");

	}else{
	 	set_message("<div class='alert alert-danger'>
    	<strong>Sorry!</strong> Your account could not be activated</div> 
		<script type='text/javascript'>
    	window.setTimeout(function() {
    	$('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    	});}, 2500);
		</script>
    	");

		redirect("login.php");

	}

		}

	}
}   //end of function


// *****************************************************************
// ******************* Active account 2 **********************//
// &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&


function active_ac() {

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

			$email = clean($_POST['email']);

		if(email_exists($email)) {

			if(details_exits($email)){

			$validation_code = md5($email . microtime());

		setcookie('temp_access_code', $validation_code, time() + 60);

$sql = "UPDATE users SET validation_code ='".escape($validation_code)."'WHERE email ='".escape($email)."' ";

	$result = query($sql);
	confirm($result);




require 'phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'example_email@gmail.com';                 // SMTP username
$mail->Password = 'email_password';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to


$mail->setFrom('example_email@gmail.com', 'User authentication');
$mail->addAddress($email);  
$mail->addReplyTo('no-reply@gmail.com', 'Np-reply');

$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = 'Activate Account';
$mail->Body    = "Please click <a href='localhost/ODF/activate.php?email=$email&code=$validation_code'>this link </a>to activate your Account";
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}




set_message("<div class='alert alert-success'>
     Welcome  Please check your <strong>email</strong> or <strong>spam folder</strong> for a password reset code </div> 
	<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2500);
	</script>
    ");


				redirect("index.php");

			}else{
				echo validation_errors("This email is already active");
			}

	}else{

	echo validation_errors("This emails does not exits");

	}
	}else{

		redirect("index.php");

	}


	if(isset($_POST['cancel_submit'])) {

		redirect("login.php");

	}

	}			// post function


 	}			 // end of function


/*8888888888888888888888888888*/






/*****************-----Validate user login function-----********************/

 
function validate_user_login() {

	$errors=[];
	$min = 3;
	$max = 20;

	if($_SERVER['REQUEST_METHOD']=="POST") {


	$email	  		 =clean($_POST['email']);
	$password  		 =clean($_POST['password']);
	$remember 		 = isset($_POST['remember']);

	if(empty($email)) {

	$errors[]="Email field cannot be empty";

	}

	if(empty($password)) {

	$errors[]="Password field cannot be empty";

	}



	if(!empty($errors)) {

	foreach ($errors as $error) {

	echo validation_errors($error);

	}
}else{


if(email_exists($email)){
	if(login_details_exits($email)){
		if(check_approve($email)){

	if(login_user($email, $password, $remember)) {
		
set_message("<div class='alert alert-success'>
     Welcome <strong>$_SESSION[email]</strong> you have successfully logged in!</div> 
	<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2000);
	</script>
    ");

		redirect("index.php");

	} else {

	echo validation_errors("Password does not match");
}

	}else{
		
	echo validation_errors("Your account active is under processing. Please wait some time");
}
}else{

	 echo validation_errors("Please active your account");
}
}else{

    echo validation_errors("Email provided is not on recognised");
}
}

	}
} // function


/*****************-----User login function-----********************/

function login_user($email,$password,$remember) {

 $sql = "SELECT password, id FROM users WHERE email = '".escape($email)."'AND active=1";
$result = query($sql);
if(row_count($result) == 1) {

$row = fetch_array($result);
$db_password = $row['password'];

	if(md5($password) === $db_password){

		if($remember == "on") {
		setcookie('email',$email,time() + 86400);
	}

		$_SESSION['email'] = $email;

		return true;
	}else{
		return false;
	}
return true;
}else{
return false;
}
}	// end of function

/*****************-----logged in function-----********************/

function logged_in() {
	if(isset($_SESSION['email']) || isset ($_COOKIE['email'])) {

		return true;

	}else{

		return false;
	}
}	/// end of function 




//************************Random Validation code generate***************************/
//**********************************************************************************/

function randomCode() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}




/*****************-----Recover password function-----********************/

function recover_password() {

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

			$email = clean($_POST['email']);

		if(email_exists($email)) {

			$validation_code =	randomCode();
		/*	$validation_code = md5($email . microtime());*/

		setcookie('temp_access_code', $validation_code, time() + 900);

$sql = "UPDATE users SET validation_code ='".escape($validation_code)."'WHERE email ='".escape($email)."' ";

	$result = query($sql);
	confirm($result);






require 'phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'example_email@gmail.com';                 // SMTP username
$mail->Password = 'email_password';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('example_email@gmail.com', 'User authentication');
$mail->addAddress($email);  
$mail->addReplyTo('no-reply@gmail.com', 'Np-reply');

$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = 'Please reset your password';

$mail->Body = "Here is your password reset code<br> <h1>{$validation_code}</h1>	<br> <a href='localhost/PhpLoginSystem/code.php?email=$email&code=$validation_code'>Click here</a>to reset your password";

$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}


		
	set_message("<div class='alert alert-success'>
    Welcome  Please check your <strong>email</strong> or <strong>spam folder</strong> for a password reset code </div> 
	<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2500);
	</script>
    ");
				redirect("index.php");
		
	}else{
	
	echo validation_errors("This emails does not exits");
	
	}
	}else{

		redirect("index.php");

	}


	if(isset($_POST['cancel_submit'])) {

		redirect("login.php");

	}
	
	}			// post function

	
 	}			 // end of function 



/*****************-----Code validation-----********************/

function validate_code() {
		
	if(isset($_COOKIE['temp_access_code'])) {

		if(!isset($_GET['email']) && !isset($_GET['code'])){

			redirect("index.php");

				}else if (empty($_GET['email']) || empty($_GET['code'])) {
		
					redirect("index.php");
				}else{
					if (isset($_POST['code'])) {
						# code...
						$email = clean($_GET['email']);
						$validation_code = clean($_POST['code']);
						$sql = "SELECT id FROM users WHERE validation_code = '".escape($validation_code)."' AND email = '".escape($email)."'";

						$result=query($sql);


						if(row_count($result)==1) {

							setcookie('temp_access_code', $validation_code, time() + 300);

							redirect("reset.php?email=$email&code=$validation_code");
						
						} else {

							echo validation_errors("Sorry worng validation code");
						}
					}
				}
			}else{
						set_message("<div class='alert alert-danger'>
    <strong>Warning!</strong> Sorry your validation cookie was expired </div> 
	<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2500);
	</script>
    ");
			redirect("recover.php");
		}
	} //end of function


/*****************-----Password Reset Function-----********************/


function password_reset() {

if(isset($_COOKIE['temp_access_code'])) {

	if(isset($_GET['email']) && isset($_GET['code'])) {

		if(isset($_SESSION['token']) && (isset($_POST['token']))) {

			if ($_POST['token'] === $_SESSION['token']) {

				if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
    				echo validation_errors("Please enter password");
    				}elseif (preg_match("/([%\$#\*]+)/", $_POST["password"])){
    					echo validation_errors("Speacial chrecter are not allowed");
    					}elseif (strlen($_POST["password"]) < '6') {
        					echo validation_errors("Invalid password. Password must be at least 6 numbers");
    						}elseif (strlen($_POST["password"]) > '8') {
        						echo validation_errors("Invalid password. Password cannot be greater than 8 numbers");
    							}else{
				
				if($_POST['password']===$_POST['confirm_password']) {

					$updated_password = md5($_POST['password']);

					$sql = "UPDATE users SET password ='".escape($updated_password)."',validation_code = 0 WHERE email = '".escape($_GET['email'])."'";

					query($sql);


				     set_message("<div class='alert alert-success'>
    Your passwords has been sucessfully updated, please <strong>login!</strong></div> 
	<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2500);
	</script>
    ");


require 'phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
                        
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'example_email@gmail.com';                 // SMTP username
$mail->Password = 'email_password';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('example_email@gmail.com', 'User authentication');
$mail->addAddress($email);  
$mail->addReplyTo('no-reply@gmail.com', 'Np-reply');

$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = 'Please reset your password';

$mail->Body = "Your passwords has been sucessfully updated, please <strong>login!</strong>";

$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

					redirect("login.php");
				}else{
					set_message("<p class='bg-danger text-center'> Your passwords does not match. </p>");
				}

			}

}
}
} else {

	set_message("<p class='bg-danger text-center'>Sorry your time has expired </p>");

		redirect("recover.php");
set_message("<div class='alert alert-danger'>
    <strong>Warning!</strong> Sorry your time has expired </div> 
	<script type='text/javascript'>
    window.setTimeout(function() {
    $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2500);
	</script>
    ");
		redirect("recover.php");

}
}
} // end of function






?>
