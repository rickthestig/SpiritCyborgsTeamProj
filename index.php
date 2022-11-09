<!DOCTYPE html>
<?php 
session_start();
ob_start();
$conn = new mysqli("localhost", "xampp", load_db_pass(), "fin_tracking"); // TODO: Update all mysqli
function load_db_pass() {
    fopen("C:\\xampp\\password.txt", "r");
    $pwd_file = fopen("C:\\xampp\\password.txt", "r");
    $pass = fread($pwd_file,filesize("C:\\xampp\\password.txt"));
    fclose($pwd_file);
    return $pass;
}
function is_data_valid_login() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false;
    }
    if (empty($_REQUEST["userLog"]) || empty($_REQUEST["pw"])) {
        return false;
    }
    return true;
} 
?>
<html lang="en">
    <head>
        <title>Student Project Showcase</title>
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
                let errLog = document.getElementById("errLog");
                loginFormStud.addEventListener("submit", function(event) {
                    if (loginFormStud.userLog.value === "") {
                        event.preventDefault();
                        errLog.style.display = "initial";   
                        errLog.innerHTML = "Please enter a Username";                    
                    } else if (loginFormStud.pw.value === "") {
                        event.preventDefault();
                        errLog.style.display = "initial";
                        errLog.innerHTML = "Please enter a Password";
                    };
                });
                loginFormFac.addEventListener("submit", function(event) {
                    if (loginFormFac.userLog.value === "") {
                        event.preventDefault();
                        errLog.style.display = "initial";   
                        errLog.innerHTML = "Please enter a Username";                    
                    } else if (loginFormFac.pw.value === "") {
                        event.preventDefault();
                        errLog.style.display = "initial";
                        errLog.innerHTML = "Please enter a Password";
                    };
                });
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
        <h1>Welcome!</h1>
        <div class="mtext"><p>Start by <a href="userLogin.php">logging in</a> or <a href="userCreate.php">creating an account</a></p></div>
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
                            <td><input type="text" id="userLogStud" name="userLog"></td>
                            <td><input type="password" id="pwStud" name="pw"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;"><button type="submit">Log In</button> Or <a href="userCreate.php"><button type="button" script="">Create Account</button></a></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p id="errLogStud">
                            <?php 
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            } else if (is_data_valid_login()) {
                                $user = htmlspecialchars($_REQUEST["userLog"]);

                                $stmt = $conn->prepare("SELECT * FROM students WHERE userID = ?"); // TODO: Update query
                                $stmt->bind_param("s", $user);
                                $stmt->execute();

                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();

                                if ($row) {
                                    $hash = $row["PasswordHash"];

                                    if (password_verify(htmlspecialchars($_REQUEST["pw"]), $hash)) {
                                        $_SESSION["username"] = $row["Username"];
                                        $_SESSION["hash"] = $hash;
                                        header("Location: "); // TODO: find path for dashboard
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
                <form id="loginFormFac" method="post">
                    <table>
                        <caption>Faculty Log In</caption>
                        <tr>
                            <th><label for="userLogFac">Username:</label></th>
                            <th><label for="pwFac">Password:</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="userLogFac" name="userLog"></td>
                            <td><input type="password" id="pwFac" name="pw"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;"><button type="submit">Log In</button> Or <a href="userCreate.php"><button type="button" script="">Create Account</button></a></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p id="errLogFac">
                            <?php 
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            } else if (is_data_valid_login()) {
                                $user = htmlspecialchars($_REQUEST["userLog"]);

                                $stmt = $conn->prepare("SELECT Username, PasswordHash FROM users WHERE Username = ?"); // TODO: Update query
                                $stmt->bind_param("s", $user);
                                $stmt->execute();

                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();

                                if ($row) {
                                    $hash = $row["PasswordHash"];

                                    if (password_verify(htmlspecialchars($_REQUEST["pw"]), $hash)) {
                                        $_SESSION["username"] = $row["Username"];
                                        $_SESSION["hash"] = $hash;
                                        header("Location: "); // TODO: find path for dashboard
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