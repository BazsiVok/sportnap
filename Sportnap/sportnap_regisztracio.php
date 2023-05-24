<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sportnap_fooldal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sigmar&display=swap" rel="stylesheet">
    <title>Sportnap</title>

</head>
<body>
<!-- Fejléc rész, ez alap mindegyik, álltalunk készített weboldalnál -->
    <header>
        <div class="row">
            <div class="col-sm-2">
                <img src="logo.jpg" alt="Sportnap logo" class="logo"  >            
            </div>
            <div class="col-sm-7">
                <h1 class="patakycim">Pataky Sportnap</h1>
            </div>
            <div class="col-sm-3">
                <img src="pataky.png" alt="Pataky István Híradásipari és Informatikai Technikum" class="patakykep">   
            </div>

            <div class="navbar">
                <a href="sportnap_fooldal.html">Versenyről</a>
                <a href="#">Eredmények</a>
                <a href="http://www.pataky.hu/" target="_blank">Patakyról</a>
                <a href="sportnap_regisztracio.html">Regisztráció</a>
            </div>
        </div>      
    </header>
    
<!-- Közepe -->
<form action='' class='form'>
    <p class='field required'>
      <label class='label required' for='name'>Full name</label>
      <input class='text-input' id='name' name='name' required type='text' value='Use Tab'>
    </p>
    <p class='field required half'>
      <label class='label' for='email'>E-mail</label>
      <input class='text-input' id='email' name='email' required type='email'>
    </p>
    <p class='field half'>
      <label class='label' for='phone'>Phone</label>
      <input class='text-input' id='phone' name='phone' type='phone'>
    </p>
    <p class='field half required error'>
      <label class='label' for='login'>Login</label>
      <input class='text-input' id='login' name='login' required type='text' value='mican'>
    </p>
    <p class='field half required'>
      <label class='label' for='password'>Password</label>
      <input class='text-input' id='password' name='password' required type='password'>
    </p>
    <div class='field'>
      <label class='label'>Sport?</label>
      <ul class='checkboxes'>
        <li class='checkbox'>
          <input class='checkbox-input' id='choice-0' name='choice' type='checkbox' value='0'>
          <label class='checkbox-label' for='choice-0'>Football</label>
        </li>
        <li class='checkbox'>
          <input class='checkbox-input' id='choice-1' name='choice' type='checkbox' value='1'>
          <label class='checkbox-label' for='choice-1'>Basketball</label>
        </li>
        <li class='checkbox'>
          <input class='checkbox-input' id='choice-2' name='choice' type='checkbox' value='2'>
          <label class='checkbox-label' for='choice-2'>Volleyball</label>
        </li>
        <li class='checkbox'>
          <input class='checkbox-input' id='choice-3' name='choice' type='checkbox' value='3'>
          <label class='checkbox-label' for='choice-3'>Golf</label>
        </li>
        <li class='checkbox'>
          <input class='checkbox-input' id='choice-4' name='choice' type='checkbox' value='4'>
          <label class='checkbox-label' for='choice-4'>Swimming</label>
        </li>
      </ul>
    </div>
    <div class='field'>
      <label class='label'>Favourite JS framework</label>
      <ul class='options'>
        <li class='option'>
          <input class='option-input' id='option-0' name='option' type='radio' value='0'>
          <label class='option-label' for='option-0'>React</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-1' name='option' type='radio' value='1'>
          <label class='option-label' for='option-1'>Vue</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-2' name='option' type='radio' value='2'>
          <label class='option-label' for='option-2'>Angular</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-3' name='option' type='radio' value='3'>
          <label class='option-label' for='option-3'>Riot</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-4' name='option' type='radio' value='4'>
          <label class='option-label' for='option-4'>Polymer</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-5' name='option' type='radio' value='5'>
          <label class='option-label' for='option-5'>Ember</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-6' name='option' type='radio' value='6'>
          <label class='option-label' for='option-6'>Meteor</label>
        </li>
        <li class='option'>
          <input class='option-input' id='option-7' name='option' type='radio' value='7'>
          <label class='option-label' for='option-7'>Knockout</label>
        </li>
      </ul>
    </div>
    <p class='field'>
      <label class='label' for='about'>About</label>
      <textarea class='textarea' cols='50' id='about' name='about' rows='4'></textarea>
    </p>
    <p class='field half'>
      <label class='label' for='select'>Position</label>
      <select class='select' id='select'>
        <option selected value=''></option>
        <option value='ceo'>CEO</option>
        <option value='front-end'>Front-end developer</option>
        <option value='back-end'>Back-end developer</option>
      </select>
    </p>
    <p class='field half'>
      <input class='button' type='submit' value='Send'>
    </p>
  </form>
  


</body>
</html>