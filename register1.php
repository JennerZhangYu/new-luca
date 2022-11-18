<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Luca’s Loaves</title>
  </head>
  <body>
    <nav class="nav">
      <div class="container">
        <img src="logo.png" class="lg">
        <h1 class="logo"><a href="/index.html">Luca’s Loaves</a></h1>
        <ul>
          <li><a href="index.html" >Home</a></li>
          <li><a href="aboutus.html">About Us</a></li>
          <li><a href="upload.html">Careers</a></li>
          <li><a href="orderonline.php">Order Online</a></li>
          <li><a href="contactus.html">Contact Us</a></li>
          <li><a href="register1.php"class="current">Register</a></li>
        </ul>
      </div>
    </nav>

    <div class="heroF">
      <div class="container">
       
     </div>
    </div>

  </div>
  <div>
<style>
   
   
   body{background: url(https://rs1.huanqiucdn.cn/dp/api/files/imageDir/4dc04916575259d84bb5ed97952c2efcu5.jpg);}

    </style>
    </div>
  <?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary616 = $username = $password ="";
$name_err = $address_err = $salary616_err = $username_err =$password_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary616
    $input_salary616 = trim($_POST["salary616"]);
    if(empty($input_salary616)){
        $salary616_err = "Please enter the salary616 amount.";     
    } elseif(!ctype_digit($input_salary616)){
        $salary616_err = "Please enter a positive integer value.";
    } else{
        $salary616 = $input_salary616;
    }

    // Validate username
if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "Username can only contain letters, numbers, and underscores.";
} else{
    // Prepare a select statement
    $sql = "SELECT id FROM employees WHERE username = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Validate confirm password
if(empty(trim($_POST["password"]))){
    $password_err = "Please confirm password.";     
} else{
    $password = trim($_POST["password"]);
    if(empty($password_err) && ($password != $password)){
        $password_err = "Password did not match.";
    }
}
 
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary616_err)&& empty($username_err)&& empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, address, salary616, username, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_salary616, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary616 = $salary616;
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5" style="color: #FFF;">Create Record</h2>
                    <p style="color: #FFF;">Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label style="color: #FFF;">Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label style="color: #FFF;">Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label style="color: #FFF;">salary</label>
                            <input type="text" name="salary616" class="form-control <?php echo (!empty($salary616_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary616; ?>">
                            <span class="invalid-feedback"><?php echo $salary616_err;?></span>
                        </div>
                        <div class="form-group">
                            <label style="color: #FFF;">Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label style="color: #FFF;">Password</label>
                            <input type="text" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2" style="color: #FFF;">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
      
        </div>
    
    </div>
    <div class="com-md-12"  style="">
		<div class="com-md-3"></div>
		<div class="com-md-6" style="margin-top: 80px;" >
			<div class="row">
				<div class="col-md-12">
					<a href=""><span><font size="2" color="#rgb">&nbsp;About us&nbsp;</font></span></a><span><font size="1" color=""> | </font></span>
					<a href=""><span><font size="2" color="#rgb">&nbsp;Join us&nbsp;</font></span></a><span><font size="1" color=""> | </font></span>
					<a href=""><span><font size="2" color="#rgb">&nbsp;contact us&nbsp;</font></span></a>
				</div>
				<div class="col-md-12">
					<font size="2" color="#rgb">
						<center>
					
							<p>Luca's Bread© 2020 . </p>
							<p>name:Jenner</p>
							<p>class:20ITA1</p>
							
						</center>
					</font>
				</div>
			</div>
            </div>