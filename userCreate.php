<!DOCTYPE html>
<?php 
session_start(); 
ob_start();
$conn = new mysqli("kmkelm.org", "kmkelmo1", load_db_pass(), "fin_tracking"); // TODO: Update mysqli
function load_db_pass() { // TODO: Update password location
    fopen("C:\\xampp\\password.txt", "r");
    $pwd_file = fopen("C:\\xampp\\password.txt", "r");
    $pass = fread($pwd_file,filesize("C:\\xampp\\password.txt"));
    fclose($pwd_file);                            
    return $pass;
}
function is_data_valid() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false; // From is appending data to url
    }            
    if (empty($_REQUEST["username"]) || empty($_REQUEST["fname"]) || empty($_REQUEST["lname"]) || empty($_REQUEST["email"]) || empty($_REQUEST["pw1"]) || empty($_REQUEST["pw2"])) {
        return false; // Form has empty fields
    }           
    if (strlen($_REQUEST["username"]) > 25) {
        return false;   // User name is too long
    }           
    if (strlen($_REQUEST["pw1"]) < 8) {
        return false;   // Password is too short
    }            
    if ($_REQUEST["pw1"] !== $_REQUEST["pw2"]) {
        return false;   // Passowrds do not match
    }
    if (strstr($_REQUEST["email"], "@") === false) {
        return false; // email field does not contain @ symbol
    }            
    return true;
}
function is_username_taken($username) {
    global $conn;
    if (isset($_REQUEST["username"])) {
        $stmt = $conn->prepare("SELECT Username FROM users WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo "Username is already taken -- Please choose a different Username & try again";
            $stmt->close();
            return false;
        } else {
            $stmt->close();
            return true;
        }
    }
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Student Project Showcase | User Creation</title>
        <link rel="stylesheet" href="basic.css">
        <script>
            window.addEventListener("load", function() {
                let studCreate = document.forms.studCreate;
                let facCreate = document.forms.facCreate;
                studCreate.addEventListener("submit", function(event) {
                    let pw1 = studCreate.pw1.value;
                    let pw2 = studCreate.pw2.value;
                    let errorLog = document.getElementById("errLogStud");
                    if (studCreate.username.value.length > 100) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Username is too long!"
                    } 
                    if (pw1.length < 8) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Password is too short!"
                    }
                    if (pw1 !== pw2) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Passwords do not match!";
                    }
                    if (!(studCreate.email.value.search("@"))) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Invalid email address";
                    }
                });
                facCreate.addEventListener("submit", function(event) {
                    let pw1 = facCreate.pw1.value;
                    let pw2 = facCreate.pw2.value;
                    let errorLog = document.getElementbyID("errLogFac");
                    if (facCreate.username.value.length > 100) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Username is too long!"
                    } 
                    if (pw1.length < 8) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Password is too short!"
                    }
                    if (pw1 !== pw2) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Passwords do not match!";
                    }
                    if (!(facCreate.email.value.search("@"))) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "Invalid email address";
                    }
                })
            });
        </script>
    </head>
    <div class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="userCreate.php">User Creation</a></li>
            <li><a href="userLogin.php">Log In</a></li>
            <?php if(isset($_SESSION["username"]) && isset($_SESSION["hash"])) {?>
            <li><a href="dashboard.php">Dashboard</a></li><?php  }  ?>
        </ul>
    </div>
    <body>
        <h1>Create an Account</h1>
        <div class="mtext" style="max-width:fit-content;">
            <p>Create Account</p>
        </div>
        <div class="login">
            <form id="studCreate" method="post">
                <table>
                    <caption>Create Student Account</caption>
                    <tr>
                        <th><label for="fname">First Name:</label></th>
                        <th><label for="lname">Last Name:</label></th>
                        <th><label for="email">Email:</label></th>
                    </tr>
                    <tr>
                        <td><input type="text" id="fname" name="fname"></td>
                        <td><input type="text" id="lname" name="lname"></td>
                        <td><input type="email" id="email" name="email"></td>
                    </tr>
                    <tr>
                        <th><label for="username">Username:</label></th>
                        <th><label for="pw1">Password:</label></th>
                        <th><label for="pw2">Confirm Password:</label></th>
                    </tr>
                    <tr>
                        <td><input type="text" id="username" name="username"></td>
                        <td><input type="password" id="pw1" name="pw1"></td>
                        <td><input type="password" id="pw2" name="pw2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button form="studCreate" type="submit">Submit</button></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p id="errLogStud">
                        <?php
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } else if (is_data_valid()) {
                            if (isset($_SESSION["username"])) {
                                session_unset();
                            }
                            $user = htmlspecialchars($_REQUEST["username"]);
                            $fname = htmlspecialchars($_REQUEST["fname"]);
                            $lname = htmlspecialchars($_REQUEST["lname"]);
                            $email = htmlspecialchars($_REQUEST["email"]);
                            $hash = password_hash(htmlspecialchars($_REQUEST["pw1"]), PASSWORD_DEFAULT);

                            if (is_username_taken($user)) {
                                $stmt = $conn->prepare("INSERT INTO fin_tracking.users (Username, fname, lname, email, PasswordHash) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param("sssss", $user, $fname, $lname, $email, $hash);
                                $stmt->execute();
                                $stmt->close();

                                $_SESSION["username"] = $user;
                                $_SESSION["hash"] = $hash;

                                header("Location: "); // TODO: Update header
                            } 
                        } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
                            echo "Account could not be created";
                        }
                        ?>
                        </p></td>
                    </tr>
                </table>
            </form>
            <form id="facCreate" method="post">
                <table>
                    <caption>Create Faculty Account</caption>
                    <tr>
                        <th><label for="fname">First Name:</label></th>
                        <th><label for="lname">Last Name:</label></th>
                        <th><label for="email">Email:</label></th>
                    </tr>
                    <tr>
                        <td><input type="text" id="fname" name="fname"></td>
                        <td><input type="text" id="lname" name="lname"></td>
                        <td><input type="email" id="email" name="email"></td>
                    </tr>
                    <tr>
                        <th><label for="username">Username:</label></th>
                        <th><label for="pw1">Password:</label></th>
                        <th><label for="pw2">Confirm Password:</label></th>
                    </tr>
                    <tr>
                        <td><input type="text" id="username" name="username"></td>
                        <td><input type="password" id="pw1" name="pw1"></td>
                        <td><input type="password" id="pw2" name="pw2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button form="facCreate" type="submit">Submit</button></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p id="errLogFac">
                        <?php
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } else if (is_data_valid()) {
                            if (isset($_SESSION["username"])) {
                                session_unset();
                            }
                            $user = htmlspecialchars($_REQUEST["username"]);
                            $fname = htmlspecialchars($_REQUEST["fname"]);
                            $lname = htmlspecialchars($_REQUEST["lname"]);
                            $email = htmlspecialchars($_REQUEST["email"]);
                            $hash = password_hash(htmlspecialchars($_REQUEST["pw1"]), PASSWORD_DEFAULT);

                            if (is_username_taken($user)) {
                                $stmt = $conn->prepare("INSERT INTO fin_tracking.users (Username, fname, lname, email, PasswordHash) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param("sssss", $user, $fname, $lname, $email, $hash);
                                $stmt->execute();
                                $stmt->close();

                                $_SESSION["username"] = $user;
                                $_SESSION["hash"] = $hash;

                                header("Location: "); // TODO: Update header location
                            } 
                        } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
                            echo "Account could not be created";
                        }
                        ?>
                        </p></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>