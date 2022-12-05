<!DOCTYPE html>
<?php 
session_start();
ob_start();
$conn = new mysqli("localhost", "kmkelmo1", load_db_pass(), "kmkelmo1_student_showcase"); // TODO: Update all mysqli
function load_db_pass() {
    $filename = "/home/kmkelmo1/kmkelm.org/kmkelmoftp/kmk.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);

    return $contents;
}
function is_data_valid_login_student() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false;
    }
    if (empty($_REQUEST["userLogStud"]) || empty($_REQUEST["pwStud"])) {
        return false;
    }
    return true;
}
function is_data_valid_login_faculty() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo "false method";
        return false;
    }
    if (empty($_REQUEST["userLogFac"]) || empty($_REQUEST["pwFac"])) {
        echo "false empty";
        return false;
    }
    return true;
} 
?>
<html lang="en">
    <head>
        <title>Student Showcase</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="basic.css">
        <style>
        div.windows {
            float:right;
        }
        </style>
        <script>
            window.addEventListener("load", function() {
                let loginFormStud = document.forms.loginFormStud;
                let loginFormFac = document.forms.loginFormFac;
                let errLogStud = document.getElementById("errLogStud");
                let errLogFac = document.getElementById("errLogFac");
                loginFormStud.addEventListener("submit", function(event) {
                    if (loginFormStud.userLogStud.value === "") {
                        event.preventDefault();
                        errLogStud.style.display = "initial";   
                        errLogStud.innerHTML = "Please enter a Username";                    
                    } else if (loginFormStud.pw.value === "") {
                        event.preventDefault();
                        errLogStud.style.display = "initial";
                        errLogStud.innerHTML = "Please enter a Password";
                    };
                });
                loginFormFac.addEventListener("submit", function(event) {
                    if (loginFormFac.userLogFac.value === "") {
                        event.preventDefault();
                        errLogFac.style.display = "initial";   
                        errLogFac.innerHTML = "Please enter a Username";                    
                    } else if (loginFormFac.pw.value === "") {
                        event.preventDefault();
                        errLogFac.style.display = "initial";
                        errLogFac.innerHTML = "Please enter a Password";
                    };
                });
            });
            
        </script>
    </head>
    <div class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="userCreate.php">User Creation</a></li>
            <!--<li><a href="userLogin.php">Log In</a></li>
            <?php if(isset($_SESSION["UserID"]) && isset($_SESSION["hash"])) {?>
            <li><a href="dashboard.php">Dashboard</a></li><?php  }  ?>-->
        </ul>
    </div>
    <body>
        <h1>Welcome!</h1>
        <div class="mtext"><p>Start by <a href="">logging in</a> or <a href="userCreate.php">creating an account</a></p></div>
        <div class="windows">
            <div class="login">
                <form id="loginFormStud" method="post">
                    <table>
                        <caption>Student Log In</caption>
                        <tr>
                            <th><label for="userLogStud">Username:</label></th>
                            <th><label for="pwStud">Password:</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="userLogStud" name="userLogStud"></td>
                            <td><input type="password" id="pwStud" name="pwStud"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;"><button type="submit">Log In</button> Or <a href="userCreate.php"><button type="button" script="">Create Account</button></a></td>
                        </tr>

                        <tr>
                            <td colspan="2"><p id="errLogStud">
                            <?php 
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            } else if (is_data_valid_login_student()) {
                                echo "pass data valid";
                                $user = htmlspecialchars($_REQUEST["userLogStud"]);
                                //$myusername = mysqli_real_escape_string($conn,$_POST['userLogStud']);
                                $stmt = $conn->prepare("SELECT UserID, HashPW FROM Student WHERE UserID = ?"); // TODO: Update query
                                echo $user;
                                $stmt->bind_param("s", $user);
                                $stmt->execute();

                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                
                                echo $row["UserID"];
                                
                                if ($row) {
                                    echo "row exists";
                                    $hash = $row["HashPW"];

                                    if (password_verify(htmlspecialchars($_REQUEST["pwStud"]), $hash)) {
                                        $_SESSION["UserID"] = $row["UserID"];
                                        $_SESSION["hash"] = $hash;
                                        //$_SESSION['login_user'] = $myusername;
                                        echo "Success";
                                        header("Location: ./imageupload.html"); // TODO: find path for dashboard
                                    } else {
                                        echo "Login Failed -- Bad Username or Password";
                                    }
                                } else {
                                    echo "Login Failed -- Account does not exist";
                                }
                                $stmt->close();
                            } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                echo "Bad form";
                            }                        
                            ?>
                            </p></td>
                        </tr>
                    </table>
                </form>
                <br>
                <form  id="loginFormFac" method="post">
                    <table>
                        <caption>Faculty Log In</caption>
                        <tr>
                            <th><label for="userLogFac">Username:</label></th>
                            <th><label for="pwFac">Password:</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="userLogFac" name="userLogFac"></td>
                            <td><input type="password" id="pwFac" name="pwFac"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;"><button type="submit">Log In</button> Or <a href="userCreate.php"><button type="button" script="">Create Account</button></a></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p id="errLogFac">
                            <?php 
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            } else if (is_data_valid_login_faculty()) {
                                $user = htmlspecialchars($_REQUEST["userLogFac"]);
                                //$myusername = mysqli_real_escape_string($conn,$_POST['userLogFac']);
                                $stmt = $conn->prepare("SELECT InsID, HashPW FROM Instructor WHERE InsID = ?"); // TODO: Update query
                                $stmt->bind_param("s", $user);
                                $stmt->execute();

                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                
                                if ($row) {
                                    $hash = $row["HashPW"];

                                    if (password_verify(htmlspecialchars($_REQUEST["pwFac"]), $hash)) {
                                        $_SESSION["InsID"] = $row["InsID"];
                                        $_SESSION["hash"] = $hash;
                                        header("Location: ./dashboard.php"); // TODO: find path for dashboard
                                    } else {
                                        echo "Login Failed -- Bad Username or Password";
                                    }
                                } else {
                                    echo "Login Failed -- Account does not exist";
                                }
                                $stmt->close();
                            } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                echo "Bad form";
                            }                        
                            ?>
                            </p></td>
                        </tr>
                    </table>
                </form>
            </div>
            <br>
        </div>
    </body>
</html>