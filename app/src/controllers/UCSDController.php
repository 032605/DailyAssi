<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDO;

final class UCSDController extends BaseController
{


	public function lostpw(Request $request, Response $response, $args) {
        $this->view->render($response, 'pages-recover-pw.phtml');
        return $response;
    }



     public function forgot_sendMail(Request $request, Response $response, $args) {

		$e_mail = $_POST['email'];
		$message = NULL;




		if($e_mail == '')
		{echo 'aaaa';

		$this->view->render($response, 'pages-recover-pw.phtml',['message'=>$message]);
		}
		else{
			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$hash = md5( rand(0,1000) );
			$stmt = $this->db->prepare("UPDATE users set hash='$hash' WHERE e_mail=?");
			$stmt->execute(array($e_mail));
			$stmt = $this->db->prepare("select * from users where e_mail=?");
			$stmt->execute(array($e_mail));
			$row = $stmt->fetch();




			if($row['fname'])
			{
				echo "a";


						// $this->getApp()->contentType('text/html');
			  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			  try {
			  	  //Server settings
				//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'dailyassi19@gmail.com';                 // SMTP username
				$mail->Password = '!teamb11';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('dailyassi19@gmail.com', 'DailyAssi');
				$mail->addAddress($e_mail, $row['fname']);     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				$mail->addReplyTo('info@example.com', 'Information');
				$mail->addCC('cc@example.com');
				$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Forgot password verify';
				$mail->Body    = 'Thanks for using the Daily Assi!
					If you want to find your password, please click the link below..<br/>


				------------------------<br/>
				Username: '.$e_mail.'<br/>
				------------------------<br/>
 <br/>
				Please click this link to change your password:
				http://teamb-iot.calit2.net/da/forgotchangepw?email='.$e_mail.'&hash='.$row['hash'].'';
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();
				echo "<script>alert(\"E-mail has been sent\");</script>";
			  }

				  catch (Exception $e) {

					echo "<script>alert(\"E-mail has not been sent.\");</script>";
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				   }


				//$this->render("index/mail.phtml");
				$this->view->render($response, 'home.phtml');
				return $response;


			}
			else{
				echo "<script>alert(\"email is not exist\");</script>";
				$this->view->render($response, 'pages-recover-pw.phtml');
			}

		}

      return $response;
	 }

	 public function forgotchange_pw(Request $request, Response $response, $args) {

		$e_mail = $_REQUEST['email'];
		$stmt = $this->db->prepare("UPDATE users set hash='null' WHERE e_mail=?");
 		$stmt->execute(array($e_mail));

		var_dump($e_mail);
		$this->view->render($response, 'forgotchange-pw.phtml',['email'=> $e_mail, 'post'=>$_POST]);
        return $response;
    }

	public function forgotchange_pw2(Request $request, Response $response, $args) {

		$e_mail = $_POST['email'];
		$newpwd = $_POST['newpwd'];
		$newpwd_confirm = $_POST['newpwd_confirm'];

		var_dump($e_mail);
		var_dump($newpwd);
		var_dump($newpwd_confirm);

		if($newpwd == '' || $newpwd_confirm== ''){
			if($newpwd == ''){
				echo "<script>alert(\"Input your new password\");</script>";
				$this->view->render($response, 'forgotchange-pw.phtml');
			}
			else if($newpwd_confirm == ''){
				echo "<script>alert(\"Input your new password Confirmation\");</script>";
				$this->view->render($response, 'forgotchange-pw.phtml');
			}
		}

		else{
			if($newpwd != $newpwd_confirm){
				echo "<script>alert(\"Confirm your new Password\");</script>";
				$this->view->render($response, 'forgotchange-pw.phtml');
			}
			else{
				//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
				//$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $this->db->prepare("select * from users where e_mail=?");
				$stmt->execute(array($e_mail));
				$row = $stmt->fetch();
				var_dump($row['fname']);



				if($row['fname']){
					$new_hash_pwd = password_hash($newpwd, PASSWORD_DEFAULT);
					$stmt = $this->db->prepare("UPDATE users set hash_pwd='$new_hash_pwd' WHERE e_mail=?");
					$stmt->execute(array($e_mail));
					$this->view->render($response, 'pages-signin.phtml',['message'=>$message]);
					echo "<script>alert(\"Success password change\");</script>";
				}
				else{
						echo "<script>alert(\"Fail password change\");</script>";
						$this->view->render($response, 'pages-signin.phtml',['message'=>$message]);
				}
		}
	}

		return response;
    }


		public function forgotchangepwAndroid(Request $request, Response $response, $args) {

			$success_res = array("type" => "FPW-RES","success_or_fail" => "success");
			$json =  json_encode($success_res);
			$parsedBody = $request->getParsedBody();

			if($parsedBody['status'] == 'email_received'){
				$stmt = $this->db->prepare("select * from users where e_mail=?");
				$stmt->execute(array($parsedBody['e_mail']));
				$row = $stmt->fetch();
				if($row['hash'] == 'null'){
					$success_res = array("type" => "FPW-RES","success_or_fail" => "clicksuccess");
					// $this->view->render($response, 'forgotchangepw_check.phtml');

				}else{
					$success_res = array("type" => "FPW-RES","success_or_fail" => "clickfail");
				}
				$json =  json_encode($success_res);
				return $response->withStatus(200)
											 ->withHeader('Content-Type','application/json')
											 ->write($json);
			}


			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$hash = md5( rand(0,1000) );
			$stmt = $this->db->prepare("UPDATE users set hash='$hash' WHERE e_mail=?");
			$stmt->execute(array($parsedBody['e_mail']));

			$stmt = $this->db->prepare("select * from users where e_mail=?");
			$stmt->execute(array($parsedBody['e_mail']));
			$row = $stmt->fetch();




			if($row['fname'])
			{

										// $this->getApp()->contentType('text/html');
			  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			  try {
			  	  //Server settings
				//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'dailyassi19@gmail.com';                 // SMTP username
				$mail->Password = '!teamb11';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('dailyassi19@gmail.com', 'DailyAssi');
				$mail->addAddress($parsedBody['e_mail'], $row['fname']);     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				$mail->addReplyTo('info@example.com', 'Information');
				$mail->addCC('cc@example.com');
				$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Here is the subject';
				$mail->Body    = 'Thanks for signing up!
				Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.<br/>


				------------------------<br/>
				Username: '.$parsedBody['e_mail'].'<br/>
				------------------------<br/>
 <br/>
				Please click this link to activate your account:
				http://teamb-iot.calit2.net/da/forgotchangepwAndroid1?email='.$parsedBody['e_mail'].'&hash='.$row['hash'].'';
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();

			  }

				  catch (Exception $e) {

					echo "<script>alert(\"Message could not be sent.\");</script>";
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				   }
					 $success_res = array("type" => "FPW-RES",
					 											// "e_mail" => 'http://teamb-iot.calit2.net/da/forgotchangepwAndroid1?email='.$parsedBody['e_mail'].'&hash='.$row['hash'].'',
					  										"success_or_fail" => "emailsuccess");
		}


		else{
			 $success_res = array("type" => "FPW-RES", "success_or_fail" => "emailfail");
		}




		$json =  json_encode($success_res);
		return $response->withStatus(200)
									 ->withHeader('Content-Type','application/json')
									 ->write($json);
	}

	public function forgotchangepwAndroid1(Request $request, Response $response, $args) {


		$success_res = array("type" => "FPW-RES","success_or_fail" => "success");
		$json =  json_encode($success_res);
		$parsedBody = $request->getParsedBody();

		$stmt = $this->db->prepare("UPDATE users set hash='null' WHERE e_mail=?");
		$stmt->execute(array($_GET['email']));

		return $response->withStatus(200)
									 ->withHeader('Content-Type','application/json')
									 ->write($json);
	}

	public function forgotchangepwAndroid2(Request $request, Response $response, $args) {


		$success_res = array("type" => "HPW-RES","success_or_fail" => "success");
		$json =  json_encode($success_res);
		$parsedBody = $request->getParsedBody();

		$stmt = $this->db->prepare("select * from users where e_mail=?");
		$stmt->execute(array($parsedBody['e_mail']));
		$row = $stmt->fetch();


		if($row['fname']){
			$new_hash_pwd = password_hash($parsedBody['new_pwd'], PASSWORD_DEFAULT);
			$stmt = $this->db->prepare("UPDATE users set hash_pwd='$new_hash_pwd' WHERE e_mail=?");
			$stmt->execute(array($parsedBody['e_mail']));
			$success_res = array("type" => "HPW-RES","success_or_fail" => "changesuccess");
		}
		else{
				$success_res = array("type" => "HPW-RES","success_or_fail" => "changefail");
		}

		$json =  json_encode($success_res);

		return $response->withStatus(200)
									 ->withHeader('Content-Type','application/json')
									 ->write($json);
	}



    public function signup(Request $request, Response $response, $args) {

		$this->view->render($response, 'pages-signup.phtml');
        return $response;

    }

		public function signupAndroid(Request $request, Response $response, $args) {

			header('Content-Type: application/json');
			$success_res = array("type" => "SUE-RES","success_or_fail" => "success");
			$json =  json_encode($success_res);
			$hash = md5( rand(0,1000) );


			$parsedBody = $request->getParsedBody();

				 try {
					 	if($parsedBody != null){
							$stmt = $this->db->prepare("SELECT * from users where e_mail= ?");
							$stmt->execute(array($parsedBody['e_mail']));
							$count = $stmt->rowCount();
							$row = $stmt->fetch();

							if($count>0)
							{
								$success_res = array("type" => "SUE-RES", "success_or_fail" => "fail");
							}
							else{
								$hashed_pwd = password_hash( $parsedBody['hash_pwd'], PASSWORD_DEFAULT);
								$stmt = $this->db->prepare("INSERT INTO users (e_mail, fname, lname, hash_pwd,sex,admin,hash,active) values (?, ?, ?, ?, ?, ?, ?, ?)");
								$stmt->execute(array($parsedBody['e_mail'], $parsedBody['fname'], $parsedBody['lname'], $hashed_pwd, $parsedBody['sex'], 0 , $hash , 0));


										//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
										$stmt = $this->db->prepare("select * from users where e_mail=?");
										$stmt->execute(array($parsedBody['e_mail']));
										$row = $stmt->fetch();

											if($row['fname'])
											{



														// $this->getApp()->contentType('text/html');
											  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
													  try {
													  	  //Server settings
														//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
														$mail->isSMTP();                                      // Set mailer to use SMTP
														$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
														$mail->SMTPAuth = true;                               // Enable SMTP authentication
														$mail->Username = 'dailyassi19@gmail.com';                 // SMTP username
														$mail->Password = '!teamb11';                           // SMTP password
														$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
														$mail->Port = 587;                                    // TCP port to connect to

														//Recipients
														$mail->setFrom('dailyassi19@gmail.com', 'DailyAssi');
														$mail->addAddress($parsedBody['e_mail'], $row['fname']);     // Add a recipient
														//$mail->addAddress('ellen@example.com');               // Name is optional
														$mail->addReplyTo('info@example.com', 'Information');
														$mail->addCC('cc@example.com');
														$mail->addBCC('bcc@example.com');

														//Attachments
														//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
														//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

														//Content
														$mail->isHTML(true);                                  // Set email format to HTML
														$mail->Subject = 'Here is the subject';
														$mail->Body    = 'Thanks for signing up!
														Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.<br/>


														------------------------<br/>
														Username: '.$parsedBody['e_mail'].'<br/>
														------------------------<br/>
										 <br/>
														Please click this link to activate your account:
														http://teamb-iot.calit2.net/da/active?email='.$parsedBody['e_mail'].'&hash='.$hash.'';
														$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

														$mail->send();

													  }

													  catch (Exception $e) {


														echo 'Mailer Error: ' . $mail->ErrorInfo;
													   }





											}


								$stmt = $this->db->prepare("SELECT * from users where e_mail= ?");
								$stmt->execute(array($parsedBody['e_mail']));
								$row = $stmt->fetch();

								$success_res = array("type" => "SUE-RES", "success_or_fail" => "success"/*, "user_seq_num" => ""+$row['user_seq_num']*/);

							}

							$json =  json_encode($success_res);


						}
			 	} catch(PDOException $e) {
			 			echo '{"error":{"text":'. $e->getMessage() .'}}';
			 	}

			//$this->view->render($response, 'signupAndroid.phtml');
					 return $response->withStatus(200)
                  				->withHeader('Content-Type','application/json')
                          ->write($json);

		}
	public function emailcheck(Request $request, Response $response, $args) {
		$e_mail = $_POST['email'];

		if($e_mail == ''){
			echo "<script>alert(\"Input your E_mail Address\");</script>";
			$this->view->render($response, 'pages-signup.phtml');
		}
		else{
			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $this->db->prepare("select * from users where e_mail=?");
			$stmt->execute(array($e_mail));
			$count = $stmt->rowCount();

			if($count>0)
			{
				echo "<script>alert(\"Input email is already exist\");</script>";
				$this->view->render($response,'pages-signup.phtml',['message'=>$message]);
			}
			else{
				echo "<script>alert(\"Input email is usable\");</script>";
				$this->view->render($response,'pages-signup.phtml',['message'=>$message]);

			}
		}

    }



	public function signup_success(Request $request, Response $response, $args) {
		$e_mail = $_POST['email'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$hash_pwd = $_POST['pwd'];
		$pwd_confirm = $_POST['pwd_confirm'];
		$sex = $_POST['Gender'];
		$hash = md5( rand(0,1000) );
		$admin = 0;
		$active = 0;



		if($e_mail == '' || $fname == '' || $lname == '' || $hash_pwd == '' || $pwd_confirm == '' ){

			if($e_mail == ''){
				echo "<script>alert(\"Input your E-mail Address\");</script>";
				$this->view->render($response, 'pages-signup.phtml');
			}
			else if($fname == ''){
				echo "<script>alert(\"Input your First Name\");</script>";
				$this->view->render($response, 'pages-signup.phtml');
			}
			else if($lname == ''){
				echo "<script>alert(\"Input your Last Name\");</script>";
				$this->view->render($response, 'pages-signup.phtml');
			}
			else if($hash_pwd == ''){
				echo "<script>alert(\"Input your Password \");</script>";
				$this->view->render($response, 'pages-signup.phtml');
			}
			else if($pwd_confirm == ''){
				echo "<script>alert(\"Input your Password Confirmation\");</script>";
				$this->view->render($response, 'pages-signup.phtml');
			}


		}
		else{
			if($hash_pwd != $pwd_confirm){
				echo "<script>alert(\"Confirm your Password\");</script>";
				$this->view->render($response, 'pages-signup.phtml');
				return $response;
			}



			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$hashed_pwd = password_hash($hash_pwd, PASSWORD_DEFAULT);
			$stmt =  $this->db->prepare("select * from users where e_mail=?");
			$stmt->execute(array($e_mail));
			$count = $stmt->rowCount();
			if($count>0)
			{
				echo "<script>alert(\"Input email is already exist\");</script>";
				$this->view->render($response,'pages-signup.phtml',['message'=>$message]);
			}
			else{
				$stmt =$this->db->prepare("insert into users(e_mail,fname,lname,hash_pwd,sex,hash,admin,active)values(?,?,?,?,?,?,?,?);");
				$stmt->execute(array($e_mail,$fname,$lname,$hashed_pwd,$sex,$hash,$admin,$active));

			if($e_mail == '')
			{echo 'aaaa';

			$this->view->render($response, 'pages-signup.phtml',['message'=>$message]);
			}
			else{
			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$stmt = $this->db->prepare("select * from users where e_mail=?");
			$stmt->execute(array($e_mail));
			$row = $stmt->fetch();



			if($row['fname'])
			{
				echo "a";


						// $this->getApp()->contentType('text/html');
			  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			  try {
			  	  //Server settings
				//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'dailyassi19@gmail.com';                 // SMTP username
				$mail->Password = '!teamb11';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('dailyassi19@gmail.com', 'DailyAssi');
				$mail->addAddress($e_mail, $row['fname']);     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				$mail->addReplyTo('info@example.com', 'Information');
				$mail->addCC('cc@example.com');
				$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Here is the subject';
				$mail->Body    = 'Thanks for signing up!
				Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.<br/>


				------------------------<br/>
				Username: '.$e_mail.'<br/>
				------------------------<br/>
 <br/>
				Please click this link to activate your account:
				http://teamb-iot.calit2.net/da/active?email='.$e_mail.'&hash='.$hash.'';
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();
				echo "<script>alert(\"Send Authentication E-mail\");</script>";
			  }

				  catch (Exception $e) {

					echo "<script>alert(\"Message could not be sent.\");</script>";
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				   }


				//$this->render("index/mail.phtml");
				$this->view->render($response, 'home.phtml');
				return $response;


			}
			else{
				echo "<script>alert(\"email is not exist\");</script>";
				$this->view->render($response, 'pages-signup.phtml');
			}

		}



			$this->view->render($response,'pages-signin.phtml',['message'=> $message]);
			var_dump($hashed_pwd);


		}
		return $response;
		}
    }

	public function deregistration(Request $request, Response $response, $args) {
		$this->view->render($response, 'deregistration.phtml');
        return $response;
    }
	public function deregistrationAndroid(Request $request, Response $response, $args) {
		$success_res = array("type" => "USD-ACK","success_or_fail" => "success");
		$json =  json_encode($success_res);
		$parsedBody = $request->getParsedBody();

		$stmt = $this->db->prepare("select * from users where user_seq_num=?");
		$stmt->execute(array($parsedBody['user_seq_num']));
		$row = $stmt->fetch();

		if($row['fname']){
			$hashed_pwd= $row['hash_pwd'];
			if(password_verify($parsedBody['now_pwd'],$hashed_pwd))
			{
				$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
				$stmt->execute(array($parsedBody['user_seq_num']));
				$row = $stmt->fetch();
				$stmt = $this->db->prepare("DELETE FROM air_sensor WHERE sensor_dev_id=?");
				$stmt->execute(array($row['dev_id']));
				$stmt = $this->db->prepare("DELETE FROM sensor WHERE users_user_seq_num=?");
				$stmt->execute(array($parsedBody['user_seq_num']));
				$stmt = $this->db->prepare("DELETE FROM heart_sensor WHERE users_user_seq_num=?");
				$stmt->execute(array($parsedBody['user_seq_num']));
				$stmt = $this->db->prepare("DELETE FROM users WHERE user_seq_num=?");
				$stmt->execute(array($parsedBody['user_seq_num']));
				$success_res = array("type" => "USD-ACK","success_or_fail" => "deregistrationsuccess");

			}
			else{
			$success_res = array("type" => "USD-ACK","success_or_fail" => "deregistrationfail");
			}

		}
		$json =  json_encode($success_res);
		return $response->withStatus(200)
									 ->withHeader('Content-Type','application/json')
									 ->write($json);

		}

	public function activationcheck(Request $request, Response $response, $args) {


		//$connect = new PDO('mysql:host=127.0.0.1;dbname=teamb-2019winter', 'teamb-iot','bpo3dlwffre93');
		//$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



		$e_mail = $_GET['email'];
		$hash = $_GET['hash'];

		$stmt = $this->db->prepare("SELECT * from users where e_mail=?");
		$stmt->execute(array($e_mail));
		$row = $stmt->fetch();

		if($hash == $row['hash']){

		$stmt = $this->db->prepare("UPDATE users set active=1,hash='null' WHERE e_mail=?");
		$stmt->execute(array($e_mail));

	}
	else{
		echo "<script>alert(\"Activation fail \");</script>";

	}




		$this->view->render($response, 'activation_check.phtml');
        return $response;

    }

	public function deregistration_check(Request $request, Response $response, $args) {
		$now_pwd = $_POST['nowpwd'];
		$now_pwdconfirm = $_POST['nowpwd_confirm'];

		$message = NULL;
		$user_seq_num = $_SESSION['seq_num'];




		if($now_pwd === '' && $now_pwdconfirm == '')
		{
			if($now_pwd == ''){
				echo "<script>alert(\"Input your Now Password \");</script>";
				$this->view->render($response, 'deregistration.phtml',['message'=>$message]);
				}
			else if($now_pwdconfirm == ''){
				echo "<script>alert(\"Input your Now Password Confirm \");</script>";
				$this->view->render($response, 'deregistration.phtml',['message'=>$message]);
			}
		}
		else{
			if($now_pwd != $now_pwdconfirm){
				echo "<script>alert(\"Confirm your now password\");</script>";
				$this->view->render($response, 'deregistration.phtml',['message'=>$message]);
			}
			else{
			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$stmt = $this->db->prepare("select * from users where user_seq_num=?");
			$stmt->execute(array($user_seq_num));
			$row = $stmt->fetch();

			if($row['fname']){
				$hashed_pwd= $row['hash_pwd'];
				if(password_verify($now_pwd,$hashed_pwd))
				{
					$stmt = $this->db->prepare("DELETE FROM users WHERE user_seq_num=?");
					$stmt->execute(array($user_seq_num));
					$this->view->render($response,'home.phtml',['message'=>$message]);
					echo "<script>alert(\"Deregistration is success\");</script>";
				}
				else{
				echo "<script>alert(\"Confirm your password\");</script>";
				$this->view->render($response,'deregistration.phtml',['message'=>$message]);
				}

			}
			}

		}

    }

    public function login(Request $request, Response $response, $args) {


		$this->view->render($response, 'pages-signin.phtml');
        return $response;

    }

	public function logincheck(Request $request, Response $response, $args) {
		var_dump($request->getParams());


	    $e_mail = $_POST['E-mail'];
		$hash_pwd = $_POST['pwd'];
		$message = NULL;



		if($e_mail == ''||$hash_pwd == '')
		{
			$this->view->render($response, 'pages-signin.phtml',['message'=>$message]);
		}

		else{


			//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
			$stmt = $this->db->prepare("select * from users where e_mail=?");
			$stmt->execute(array($e_mail));
			$row = $stmt->fetch();



			//���� Ȱ��ȭ
			if($row['active']==0){
				echo "<script>alert(\"Activation need to verify\");</script>";
				$this->view->render($response, 'pages-signin.phtml',['message'=>$message]);
			}
			else{


				if($row['fname'])
				{
					$hashed_pwd = $row['hash_pwd'];
					if(password_verify($hash_pwd, $hashed_pwd))
					{
						$_SESSION['userName'] = $row["fname"];
						$_SESSION['seq_num'] = $row["user_seq_num"];
						$user_seq_num = $_SESSION['seq_num'];
						$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
						$stmt->execute(array($user_seq_num));
						$row = $stmt->fetch();
						$dev_id= $row['dev_id'];
						$stmt = $this->db->prepare("SELECT * from air_sensor where sensor_dev_id=? ORDER BY air_date DESC LIMIT 100");
						$stmt->execute(array($dev_id));
						$row2 = $stmt->fetch();
						$stmt = $this->db->prepare("SELECT * from heart_sensor where users_user_seq_num=? ORDER BY heart_date DESC LIMIT 100");
						$stmt->execute(array($user_seq_num));
						$row3 = $stmt->fetch();



						$temperature = explode(".",$row2['temperature']);
						$heart_rate = $row3['heart_rate'];




						$this->view->render($response, 'index.phtml',['temperature'=> $temperature, 'row2'=>$row2,'heart_rate'=>$heart_rate] );
					}
					else{
						echo "<script>alert(\"Incorrect login information\");</script>";
						$this->view->render($response, 'pages-signin.phtml',['message'=>$message]);
					}
				}
				else{
					echo "<script>alert(\"email is not exist2\");</script>";
					$this->view->render($response, 'pages-signin.phtml',['message'=>$message]);
				}
			}
		}
		//return $response;
    }

		public function signinAndroid(Request $request, Response $response, $args) {

			$success_res = array("type" => "SGI-RES","success_or_fail" => "success");
			$json =  json_encode($success_res);
			$parsedBody = $request->getParsedBody();

			if($parsedBody != null){
							$stmt = $this->db->prepare("SELECT * from users where e_mail= ?");
							$stmt->execute(array($parsedBody['e_mail']));
							$row = $stmt->fetch();

							if($row['active']==0){
								$success_res = array("type" => "SGI-RES","success_or_fail" => "activefail");
							}
							else{
								if($row['fname'])
								{
									$hashed_pwd = $row['hash_pwd'];
									if(password_verify($parsedBody['hash_pwd'], $hashed_pwd))
									{
										$success_res = array("type" => "SGI-RES",
																					"user_seq_num" => $row['user_seq_num'],
																					"success_or_fail" => "verifysuccess"

																				);
									}
									else{
											$success_res = array("type" => "SGI-RES","success_or_fail" => "verifyfail");
									}
								}
							}
						}


						$json =  json_encode($success_res);
						return $response->withStatus(200)
													 ->withHeader('Content-Type','application/json')
													 ->write($json);



    }



	public function changepw(Request $request, Response $response, $args) {
        $this->view->render($response, 'change-pw.phtml');
        return $response;
    }

	public function changepw2(Request $request, Response $response, $args) {

		$nowpwd = $_POST['nowpassword'];
		$newpwd = $_POST['newpwd'];
		$newpwd_confirm = $_POST['newpwd_confirm'];
		$user_seq_num = $_SESSION['seq_num'];

		if($nowpwd == '' || $newpwd == '' || $newpwd_confirm == ''){
			if($nowpwd == '') {
				echo "<script>alert(\"Input your now password\");</script>";
					$this->view->render($response, 'change-pw.phtml');
			}

			else if($newpwd == '') {
				echo "<script>alert(\"Input your new password\");</script>";
					$this->view->render($response, 'change-pw.phtml');
			}

			else if($newpwd_confirm == '') {
				echo "<script>alert(\"Input your new password Confirmation\");</script>";
					$this->view->render($response, 'change-pw.phtml');
			}
		}


		else{
			if($newpwd != $newpwd_confirm){
				echo "<script>alert(\"Confirm your new password\");</script>";
				$this->view->render($response, 'change-pw.phtml');
			}
			else{
				//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
				//$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $this->db->prepare("select * from users where user_seq_num=?");
				$stmt->execute(array($user_seq_num));
				$row = $stmt->fetch();


				if($row['fname'])
					{
						$hashed_pwd = $row['hash_pwd'];
						if(password_verify($nowpwd, $hashed_pwd))
						{
							$new_hash_pwd=password_hash($newpwd, PASSWORD_DEFAULT);
							$stmt = $this->db->prepare("UPDATE users set hash_pwd='$new_hash_pwd' WHERE user_seq_num=?");
							$stmt->execute(array($user_seq_num));

							$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
							$stmt->execute(array($user_seq_num));
							$row = $stmt->fetch();
							$dev_id= $row['dev_id'];
					    $stmt = $this->db->prepare("SELECT * from air_sensor where sensor_dev_id=? ORDER BY air_date DESC LIMIT 100");
					    $stmt->execute(array($dev_id));
					    $row2 = $stmt->fetch();
							$stmt = $this->db->prepare("SELECT * from heart_sensor where users_user_seq_num=? ORDER BY heart_date DESC LIMIT 100");
					    $stmt->execute(array($user_seq_num));
					    $row3 = $stmt->fetch();

					    $temperature = explode(".",$row2['temperature']);
							$heart_rate = $row3['heart_rate'];


							echo "<script>alert(\"password change successfully\");</script>";
							$this->view->render($response, 'index.phtml',['temperature'=>$temperature, 'row2'=>$row2, 'heart_rate'=>$heart_rate]);
						}
						else{
							echo "<script>alert(\"Incorrect login information\");</script>";
							$this->view->render($response, 'change-pw.phtml',['message'=>$message]);
						}
					}
			}
		}
		return $response;
	}

	public function changepwAndroid(Request $request, Response $response, $args) {

		$success_res = array("type" => "PWC-RES","success_or_fail" => "success");
		$json =  json_encode($success_res);
		$parsedBody = $request->getParsedBody();

		$stmt = $this->db->prepare("select * from users where user_seq_num=?");
		$stmt->execute(array($parsedBody['user_seq_num']));
		$row = $stmt->fetch();


		if($row['fname'])
			{
				$hashed_pwd = $row['hash_pwd'];
				if(password_verify($parsedBody['now_pwd'], $hashed_pwd))
				{
					$new_hash_pwd=password_hash($parsedBody['new_pwd'], PASSWORD_DEFAULT);
					$stmt = $this->db->prepare("UPDATE users set hash_pwd='$new_hash_pwd' WHERE user_seq_num=?");
					$stmt->execute(array($parsedBody['user_seq_num']));
					$success_res = array("type" => "PWC-RES","success_or_fail" => "changesuccess");

				}
				else{
					$success_res = array("type" => "PWC-RES","success_or_fail" => "changefail");
				}
			}
			$json =  json_encode($success_res);
			return $response->withStatus(200)
										 ->withHeader('Content-Type','application/json')
										 ->write($json);


		}


	public function profile(Request $request, Response $response, $args) {

		$user_seq_num = $_SESSION['seq_num'];

		//$connect = new PDO('mysql:host=127.0.0.1; dbname=teamb-2019winter','teamb-iot','bpo3dlwffre93');
		$stmt = $this->db->prepare("select * from users where user_seq_num=?");
		$stmt->execute(array($user_seq_num));
		$row = $stmt->fetch();

		$e_mail =$row['e_mail'];
		$fname =$row['fname'];
		$lname =$row['lname'];
		$sex =$row['sex'];

		$stmt = $this->db->prepare("select * from sensor where users_user_seq_num=?");
		$stmt->execute(array($user_seq_num));
		$count = $stmt->rowCount();
		$row2 = $stmt->fetchall(PDO::FETCH_ASSOC);



        $this->view->render($response, 'pages-user-profile.phtml',['e_mail'=> $e_mail,'fname'=> $fname,'lname'=>$lname,'sex'=>$sex,'count'=>$count, 'row2'=>$row2]);
        return $response;
    }


		public function heartrate(Request $request, Response $response, $args) {

		      $user_seq_num = $_SESSION['seq_num'];
		      $stmt = $this->db->prepare("select * from sensor where users_user_seq_num=?");
		      $stmt->execute(array($user_seq_num));
		      $row = $stmt->fetch();
		      $dev_id = $row['dev_id'];
		      $stmt = $this->db->prepare("select * from air_sensor where sensor_dev_id=?");
		      $stmt->execute(array($dev_id));
		      $row2 = $stmt->fetch();
		      $stmt = $this->db->prepare("SELECT * from heart_sensor where users_user_seq_num=? ORDER BY heart_date DESC LIMIT 100");
		      $stmt->execute(array($user_seq_num));
		      $row3 = $stmt->fetch();

		      $lat=$row2['lat'];
		      $lon=$row2['lon'];
		      $heart_rate = $row3['heart_rate'];

		      $stmt = $this->db->prepare("SELECT * FROM heart_sensor where users_user_seq_num = ? ORDER BY heart_date DESC LIMIT 100");
		      $stmt->execute(array($user_seq_num));
		      $row3 = $stmt->fetchall(PDO::FETCH_ASSOC);


		      $this->view->render($response, 'heart-rate.phtml',['lat'=> $lat,'lon'=> $lon, 'heart_rate'=>$heart_rate, 'row3'=>$row3]);
		        return $response;
		    }

		public function hrjson(Request $request, Response $response, $args) {

			try {
			$user_seq_num = $_SESSION['seq_num'];
			$stmt = $this->db->prepare("SELECT * from heart_sensor where users_user_seq_num=?");
			$stmt->execute(array($user_seq_num));
			$result = $stmt->fetchAll();



		   // $result = $stmt->fetchAll();


            if ($result) {

					// build array for Column labels
                    $json_array['cols'] = array(
                            array('heart_id'=>'', 'label'=>'date/time', 'type'=>'string'),
							array('heart_id'=>'', 'label'=>'heart_rate', 'type'=>'number'));

                    // loop thru the sensor data and build sensor_array
                    foreach ($result as $row) {
                        $sensor_array = array();
                        $sensor_array[] = array('v'=>$row['heart_date']);
                        $sensor_array[] = array('v'=>$row['heart_rate']);


                        // add current sensor_array line to $rows
                        $rows[] = array('c'=>$sensor_array);
                    }

                    // add $rows to $json_array
                    $json_array['rows'] = $rows;


              return $response->withHeader('Content-type', 'application/json')
              ->write(json_encode($json_array, JSON_NUMERIC_CHECK))
              ->withStatus(200);

            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
		}

		public function temp(Request $request, Response $response, $args) {

	      $user_seq_num = $_SESSION['seq_num'];
	      $stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
	      $stmt->execute(array($user_seq_num));
	      $row = $stmt->fetch();
	      $dev_id= $row['dev_id'];
	      $stmt = $this->db->prepare("SELECT * from air_sensor where sensor_dev_id=? ORDER BY air_date DESC LIMIT 100");
	    $stmt->execute(array($dev_id));
	    $row2 = $stmt->fetch();

	    $temperature = explode(".",$row2['temperature']);
	      $stmt = $this->db->prepare("SELECT * FROM air_sensor where sensor_dev_id = ? ORDER BY air_date DESC LIMIT 100");
	      $stmt->execute(array($dev_id));
	      $row3 = $stmt->fetchall(PDO::FETCH_ASSOC);




	      $this->view->render($response, 'temp.phtml',['temperature'=> $temperature,'row3'=>$row3]);
	        return $response;
	    }


		public function tempjson(Request $request, Response $response, $args) {

			try {
			$user_seq_num = $_SESSION['seq_num'];
			$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
			$stmt->execute(array($user_seq_num));
			$row = $stmt->fetch();
			$dev_id= $row['dev_id'];
			$stmt = $this->db->prepare("SELECT * from air_sensor where sensor_dev_id=?");
			$stmt->execute(array($dev_id));
			$result = $stmt->fetchAll();



		   // $result = $stmt->fetchAll();


            if ($result) {

					// build array for Column labels
                    $json_array['cols'] = array(
                            array('air_id'=>'', 'label'=>'date/time', 'type'=>'string'),
							array('air_id'=>'', 'label'=>'temperature', 'type'=>'number'));

                    // loop thru the sensor data and build sensor_array
                    foreach ($result as $row) {
                        $sensor_array = array();
                        $sensor_array[] = array('v'=>$row['air_date']);
                        $sensor_array[] = array('v'=>$row['temperature']);


                        // add current sensor_array line to $rows
                        $rows[] = array('c'=>$sensor_array);
                    }

                    // add $rows to $json_array
                    $json_array['rows'] = $rows;


              return $response->withHeader('Content-type', 'application/json')
              ->write(json_encode($json_array, JSON_NUMERIC_CHECK))
              ->withStatus(200);

            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
		}


		public function air(Request $request, Response $response, $args) {

		$user_seq_num = $_SESSION['seq_num'];
		$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
		$stmt->execute(array($user_seq_num));
		$row = $stmt->fetch();
		$dev_id= $row['dev_id'];
		$stmt = $this->db->prepare("SELECT * FROM air_sensor where sensor_dev_id = ? ORDER BY air_date DESC LIMIT 100");
		$stmt->execute(array($dev_id));
		$row2 = $stmt->fetch();
		$stmt = $this->db->prepare("SELECT * FROM air_sensor where sensor_dev_id = ? ORDER BY air_date DESC LIMIT 100");
		$stmt->execute(array($dev_id));
		$row3 = $stmt->fetchall(PDO::FETCH_ASSOC);



		$this->view->render($response, 'air.phtml',['row2'=>$row2,'row3'=>$row3]);
        return $response;
		}

		public function airjson(Request $request, Response $response, $args) {


        try {
			$user_seq_num = $_SESSION['seq_num'];
			$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
			$stmt->execute(array($user_seq_num));
			$row = $stmt->fetch();
			$dev_id= $row['dev_id'];
			$stmt = $this->db->prepare("SELECT * from air_sensor where sensor_dev_id=?");
			$stmt->execute(array($dev_id));
			$result = $stmt->fetchAll();



		   // $result = $stmt->fetchAll();


            if ($result) {
               // foreach (array("s1"=>'O2', "s2"=>'N', "s3"=>'PM', "s4"=>'Temperature', "s5"=>'SO2', "s6"=>'XYZ') as $sensor=>$sensor_label) {

					// build array for Column labels
                    $json_array['cols'] = array(
                            array('air_id'=>'', 'label'=>'date/time', 'type'=>'string'),
                            array('air_id'=>'', 'label'=>'AQI_PM', 'type'=>'number'),
                            array('air_id'=>'', 'label'=>'AQI_CO', 'type'=>'number'),
														array('air_id'=>'', 'label'=>'AQI_NO2', 'type'=>'number'),
														array('air_id'=>'', 'label'=>'AQI_SO2', 'type'=>'number'),
														array('air_id'=>'', 'label'=>'AQI_O3', 'type'=>'number'));

                    // loop thru the sensor data and build sensor_array
                    foreach ($result as $row) {
                        $sensor_array = array();
                        $sensor_array[] = array('v'=>$row['air_date']);
                        $sensor_array[] = array('v'=>$row['AQI_PM']);
                        $sensor_array[] = array('v'=>$row['AQI_CO']);
												$sensor_array[] = array('v'=>$row['AQI_NO2']);
												$sensor_array[] = array('v'=>$row['AQI_SO2']);
												$sensor_array[] = array('v'=>$row['AQI_O3']);

                        // add current sensor_array line to $rows
                        $rows[] = array('c'=>$sensor_array);
                    }

                    // add $rows to $json_array
                    $json_array['rows'] = $rows;


              return $response->withHeader('Content-type', 'application/json')
              ->write(json_encode($json_array, JSON_NUMERIC_CHECK))
              ->withStatus(200);

            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
		}



	public function signout(Request $request, Response $response, $args) {
		session_destroy();
		$this->view->render($response, 'pages-signin.phtml');
        return $response;
    }

	public function receiveJSON(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        var_dump($parsedBody);
        echo "I see the name: " . $parsedBody['type'] ;
		$this->view->render($response, 'home.phtml');
        return $response;
    }

		public function receiveSensorData(Request $request, Response $response, $args) {

			$success_res = array("type" => "AQDS-RES","success_or_fail" => "success");
			$json =  json_encode($success_res);
			$parsedBody = $request->getParsedBody();

			$stmt = $this->db->prepare("select * from sensor where users_user_seq_num=?");
			$stmt->execute(array($parsedBody['user_seq_num']));
			$row = $stmt->fetch();



			if($parsedBody['PM25']){
				$stmt = $this->db->prepare("INSERT INTO air_sensor (air_date, lat, lon, temperature, RAW_PM,RAW_CO,RAW_SO2,RAW_NO2,RAW_O3,AQI_PM,AQI_CO,AQI_NO2,AQI_SO2,AQI_O3,sensor_dev_id) values (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?)");
				$stmt->execute(
					array($parsedBody['epoch_time'], $parsedBody['lat'],$parsedBody['lon'],
				  $parsedBody['temp'] , $parsedBody['raw_PM25'],$parsedBody['raw_CO'],
					$parsedBody['raw_NO2'],$parsedBody['raw_SO2'],$parsedBody['raw_O3'],
					$parsedBody['PM25'] , $parsedBody['CO'], $parsedBody['NO2'] ,
					 $parsedBody['SO2'] , $parsedBody['O3'] ,$row['dev_id'])
				 );
				$success_res = array("type" => "AQDS-RES","success_or_fail" => "receivesuccess");
			}
			else{
				$success_res = array("type" => "AQDS-RES","success_or_fail" => "receivefail");
			}




			$json =  json_encode($success_res);
			return $response->withStatus(200)
										 ->withHeader('Content-Type','application/json')
										 ->write($json);

			}
			public function sendAQIHistory(Request $request, Response $response, $args){
				$success_res = array("type" => "AQDS-RES","success_or_fail" => "fail");
				$json =  json_encode($success_res);
				$parsedBody = $request->getParsedBody();

				$stmt = $this->db->prepare("select * from sensor where users_user_seq_num=?");
				$stmt->execute(array($parsedBody['user_seq_num']));
				$row = $stmt->fetch();

				$dev_id=$row['dev_id'];

				$stmt = $this->db->prepare("SELECT * FROM air_sensor WHERE sensor_dev_id =? AND (air_date between ? AND ?)");
				$stmt->execute(array($dev_id,$parsedBody['start_date'],$parsedBody['end_date']));


				$total_AQI_arr = array();

				while ($row = $stmt->fetch()) {

				    $temp_arr = array(
							"air_date" => $row['air_date'],
							"AQI_PM" => $row['AQI_PM'],
							"AQI_CO" => $row['AQI_CO'],
							"AQI_NO2" => $row['AQI_NO2'],
							"AQI_SO2" => $row['AQI_SO2'],
							"AQI_O3" => $row['AQI_O3']
				                // "PM" => $aqi_row['AQI_PM'],
				                // "CO" => $aqi_row['AQI_CO'],
				                // "NO2" => $aqi_row['AQI_NO2'],
				                // "SO2" => $aqi_row['AQI_SO2'],
				                // "O3" => $aqi_row['AQI_O3'],
											);
				    $total_AQI_arr[] = $temp_arr;
				}

				$total_AQI_arr = array(
					"success_or_fail"=>  "aqiselectsuccess",
					"aqi_data"=>  $total_AQI_arr

					);

				$json =  json_encode($total_AQI_arr);
				return $response->withStatus(200)
											 ->withHeader('Content-Type','application/json')
											 ->write($json);
		}

		public function receiveHRData(Request $request, Response $response, $args){
			$success_res = array("type" => "HRDA-RES","success_or_fail" => "HR_receivefail");
			$json =  json_encode($success_res);
			$parsedBody = $request->getParsedBody();

			if($parsedBody['user_seq_num']){
			$stmt = $this->db->prepare("INSERT INTO heart_sensor (heart_date, heart_lat, heart_lon,heart_rate,users_user_seq_num) values (?, ?, ?, ?, ?)");
			$stmt->execute(array($parsedBody['epoch_time'],$parsedBody['lat'],$parsedBody['lon'],$parsedBody['heart_rate'],$parsedBody['user_seq_num']));

			$success_res = array("type" => "HRDA-RES","success_or_fail" => "HR_receivesuccess");
			}
			else{
				$success_res = array("type" => "HRDA-RES","success_or_fail" => "HR_receivefail");
			}

			$json =  json_encode($success_res);
			return $response->withStatus(200)
										 ->withHeader('Content-Type','application/json')
										 ->write($json);
	}

	public function sendHRHistory(Request $request, Response $response, $args){
		$success_res = array("type" => "HHR-RES","success_or_fail" => "HR_receivefail");
		$json =  json_encode($success_res);
		$parsedBody = $request->getParsedBody();

		$stmt = $this->db->prepare("SELECT * FROM heart_sensor WHERE users_user_seq_num =? AND (heart_date between ? AND ?)");
		$stmt->execute(array($parsedBody['user_seq_num'],$parsedBody['start_date'],$parsedBody['end_date']));


		$total_HR_arr = array();

		while ($row = $stmt->fetch()) {

				$temp_arr = array(
					"heart_date" => $row['heart_date'],
					"heart_rate" => $row['heart_rate']

										// "PM" => $aqi_row['AQI_PM'],
										// "CO" => $aqi_row['AQI_CO'],
										// "NO2" => $aqi_row['AQI_NO2'],
										// "SO2" => $aqi_row['AQI_SO2'],
										// "O3" => $aqi_row['AQI_O3'],
									);
				$total_HR_arr[] = $temp_arr;
		}

		$total_HR_arr = array(
			"success_or_fail"=>  "hrselectsuccess",
			"HR_data"=>  $total_HR_arr

			);

		$json =  json_encode($total_HR_arr);
		return $response->withStatus(200)
									 ->withHeader('Content-Type','application/json')
									 ->write($json);
}

public function receivesensor_list(Request $request, Response $response, $args){
	$success_res = array("type" => "SRP-RES","success_or_fail" => "insertfail");
	$json =  json_encode($success_res);
	$parsedBody = $request->getParsedBody();


	$stmt = $this->db->prepare("SELECT * from sensor where users_user_seq_num=?");
	$stmt->execute(array($parsedBody['user_seq_num']));
	$row = $stmt->fetch();
	if(!$row['mac_addr']){
			if($parsedBody['user_seq_num']!=-1){
				if($parsedBody['mac_address']){
					$stmt = $this->db->prepare("INSERT INTO sensor (mac_addr,dev_name,users_user_seq_num) values (?, ?, ?)");
					$stmt->execute(array($parsedBody['mac_address'],$parsedBody['dev_name'],$parsedBody['user_seq_num']));
					$success_res = array("type" => "SRP-RES","success_or_fail" => "insertsuccess");
				}
			}
		}
	else{
		$success_res = array("type" => "SRP-RES","success_or_fail" => "insertfail");
	}
	$json =  json_encode($success_res);
	return $response->withStatus(200)
								 ->withHeader('Content-Type','application/json')
								 ->write($json);



}

public function sensor_DeregistAndroid(Request $request, Response $response, $args){

	$success_res = array("type" => "SDP-RES","success_or_fail" => "deletefail");
	$json =  json_encode($success_res);
	$parsedBody = $request->getParsedBody();

	$stmt = $this->db->prepare("SELECT * from sensor where mac_addr=?");
	$stmt->execute(array($parsedBody['mac_address']));
	$row = $stmt->fetch();

	if($parsedBody['mac_address']){
	$stmt = $this->db->prepare("SELECT * from sensor where mac_addr=?");
	$stmt->execute(array($parsedBody['mac_address']));
	$row = $stmt->fetch();
	$stmt = $this->db->prepare("DELETE from air_sensor where sensor_dev_id=?");
	$stmt->execute(array($row['dev_id']));
	$stmt = $this->db->prepare("DELETE from sensor where users_user_seq_num=?");
	$stmt->execute(array($parsedBody['user_seq_num']));
	$success_res = array("type" => "SDP-RES","success_or_fail" => "deletesuccess");
	}

	else{
		$success_res = array("type" => "SDP-RES","success_or_fail" => "deletefail");

	}

	$json =  json_encode($success_res);
	return $response->withStatus(200)
								 ->withHeader('Content-Type','application/json')
								 ->write($json);



}

public function sendalldevlist(Request $request, Response $response, $args){
	$success_res = array("type" => "SDP-RES","success_or_fail" => "devlistfail");
	$json =  json_encode($success_res);
	$parsedBody = $request->getParsedBody();

	$stmt = $this->db->prepare("SELECT * FROM sensor WHERE users_user_seq_num =?");
	$stmt->execute(array($parsedBody['user_seq_num']));


	$total_Sensor_arr = array();

	while ($row = $stmt->fetch()) {

			$Sensor_arr = array(
				"mac_addr" => $row['mac_addr'],
				"dev_name" => $row['dev_name']

									// "PM" => $aqi_row['AQI_PM'],
									// "CO" => $aqi_row['AQI_CO'],
									// "NO2" => $aqi_row['AQI_NO2'],
									// "SO2" => $aqi_row['AQI_SO2'],
									// "O3" => $aqi_row['AQI_O3'],
								);
			$total_Sensor_arr[] = $Sensor_arr;
	}

	$total_Sensor_arr = array(
		"success_or_fail"=>  "devlistsuccess",
		"dev_list"=>  $total_Sensor_arr

		);

	$json =  json_encode($total_Sensor_arr);
	return $response->withStatus(200)
								 ->withHeader('Content-Type','application/json')
								 ->write($json);
}




public function senduserinfo(Request $request, Response $response, $args){
	$success_res = array("type" => "SDP-RES","success_or_fail" => "profilefail");
	$json =  json_encode($success_res);
	$parsedBody = $request->getParsedBody();

	$stmt = $this->db->prepare("SELECT * FROM users WHERE user_seq_num =?");
	$stmt->execute(array($parsedBody['user_seq_num']));
	$row = $stmt->fetch();


	$total_info_arr = array(
		"success_or_fail" => "profilesuccess",
		"e_mail" => $row['e_mail'],
		"fname" => $row['fname'],
		"lname" => $row['lname'],
		"sex" => $row['sex']

							// "PM" => $aqi_row['AQI_PM'],
							// "CO" => $aqi_row['AQI_CO'],
							// "NO2" => $aqi_row['AQI_NO2'],
							// "SO2" => $aqi_row['AQI_SO2'],
							// "O3" => $aqi_row['AQI_O3'],
						);


	$json =  json_encode($total_info_arr);
	return $response->withStatus(200)
								 ->withHeader('Content-Type','application/json')
								 ->write($json);
}








 }
