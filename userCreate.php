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
function is_data_valid() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false; // From is appending data to url
    }            
    if (empty($_REQUEST["userID"]) || empty($_REQUEST["fname"]) || empty($_REQUEST["lname"]) || empty($_REQUEST["currentStud"]) || empty($_REQUEST["pw1"]) || empty($_REQUEST["pw2"])) {
        return false; // Form has empty fields
    }           
    if (strlen($_REQUEST["userID"]) > 11) {
        return false;   // User ID is too long
    }           
    if (strlen($_REQUEST["pw1"]) < 8) {
        return false;   // Password is too short
    }            
    if ($_REQUEST["pw1"] !== $_REQUEST["pw2"]) {
        return false;   // Passowrds do not match
    }
    return true;
}
function is_data_valid_fac() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false; // From is appending data to url
    }            
    if (empty($_REQUEST["facID"]) || empty($_REQUEST["facfname"]) || empty($_REQUEST["faclname"]) || empty($_REQUEST["currentFac"]) || empty($_REQUEST["facpw1"]) || empty($_REQUEST["facpw2"])) {
        return false; // Form has empty fields
    }           
    if (strlen($_REQUEST["facID"]) > 11) {
        return false;   // User ID is too long
    }           
    if (strlen($_REQUEST["facpw1"]) < 8) {
        return false;   // Password is too short
    }            
    if ($_REQUEST["facpw1"] !== $_REQUEST["facpw2"]) {
        return false;   // Passowrds do not match
    }
    return true;
}
function is_userID_taken_student($user) {
    global $conn;
    if (isset($_REQUEST["userID"])) {
        $stmt = $conn->prepare("SELECT UserID FROM Student WHERE UserID = ?");
        $stmt->bind_param("i", $user);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo "User ID is already taken -- Please try to log in";
            $stmt->close();
            return false;
        } else {
            $stmt->close();
            return true;
        }
    }
}
function is_userID_taken_faculty($facuser) {
    global $conn;
    if (isset($_REQUEST["facID"])) {
        $stmt = $conn->prepare("SELECT InsID FROM Instructor WHERE InsID = ?");
        $stmt->bind_param("i", $facuser);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo "User ID is already taken -- Please try to log in";
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
        <title>Student Showcase | User Creation</title>
        <link rel="stylesheet" href="basic.css">
        <script>
            window.addEventListener("load", function() {
                let studCreate = document.forms.studCreate;
                let facCreate = document.forms.facCreate;
                studCreate.addEventListener("submit", function(event) {
                    let pw1 = studCreate.pw1.value;
                    let pw2 = studCreate.pw2.value;
                    let errorLog = document.getElementById("errLogStud");
                    if (studCreate.userID.value.length > 11) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "User ID is too long!"
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
                });
                facCreate.addEventListener("submit", function(event) {
                    let pw1 = facCreate.facpw1.value;
                    let pw2 = facCreate.facpw2.value;
                    let errorLog = document.getElementbyID("errLogFac");
                    if (facCreate.userID.value.length > 11) {
                        event.preventDefault();
                        errorLog.style.display = "initial";
                        errorLog.innerHTML = "User ID is too long!"
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
                })
            });
        </script>
    </head>
    <div class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="userCreate.php">User Creation</a></li>
            <!--<li><a href="userLogin.php">Log In</a></li>
            <?php if(isset($_SESSION["userID"]) && isset($_SESSION["hash"])) {?>
            <li><a href="dashboard.php">Dashboard</a></li><?php  }  ?>-->
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
                        <th><label for="currentStud">Current Student?</label></th>
                    </tr>
                    <tr>
                        <td><input type="text" id="fname" name="fname"></td>
                        <td><input type="text" id="lname" name="lname"></td>
                        <td><select id="currentStud" name="currentStud">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="userID">UserID:</label></th>
                        <th><label for="pw1">Password:</label></th>
                        <th><label for="pw2">Confirm Password:</label></th>
                    </tr>
                    <tr>
                        <td><input type="number" id="userID" name="userID"></td>
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
                            if (isset($_SESSION["userID"])) {
                                session_unset();
                            }
                            $user = htmlspecialchars($_REQUEST["userID"]);
                            $fname = htmlspecialchars($_REQUEST["fname"]);
                            $lname = htmlspecialchars($_REQUEST["lname"]);
                            $currentStud = htmlspecialchars($_REQUEST["currentStud"]);
                            if ($currentStud == "Yes") {
                                $currentStud = 1;
                            } else if ($currentStud == "No") {
                                $currentStud = 0;
                            }
                            $hash = password_hash(htmlspecialchars($_REQUEST["pw1"]), PASSWORD_DEFAULT);
                            
                            if (is_userID_taken_student($user)) {
                                $stmt = $conn->prepare("INSERT INTO Student (UserID, fname, lname, CurrentStudent, HashPW) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param("issis", $user, $fname, $lname, $currentStud, $hash);
                                $stmt->execute();
                                $stmt->close();

                                $_SESSION["userID"] = $user;
                                $_SESSION["hash"] = $hash;

                                header("Location: "); // TODO: Update header
                            } 
                        } else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_REQUEST["userID"])) {
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
                        <th><label for="facfname">First Name:</label></th>
                        <th><label for="faclname">Last Name:</label></th>
                        <th><label for="currentFac">Current Faculty?</label></th>
                    </tr>
                    <tr>
                        <td><input type="text" id="facfname" name="facfname"></td>
                        <td><input type="text" id="faclname" name="faclname"></td>
                        <td><select id="currentFac" name="currentFac">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="facID">User ID:</label></th>
                        <th><label for="facpw1">Password:</label></th>
                        <th><label for="facpw2">Confirm Password:</label></th>
                    </tr>
                    <tr>
                        <td><input type="number" id="facID" name="facID"></td>
                        <td><input type="password" id="facpw1" name="facpw1"></td>
                        <td><input type="password" id="facpw2" name="facpw2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button form="facCreate" type="submit">Submit</button></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p id="errLogFac">
                        <?php
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        } else if (is_data_valid_fac()) {
                            if (isset($_SESSION["userID"])) {
                                session_unset();
                            }
                            $facuser = htmlspecialchars($_REQUEST["facID"]);
                            $facfname = htmlspecialchars($_REQUEST["facfname"]);
                            $faclname = htmlspecialchars($_REQUEST["faclname"]);
                            $currentFac = htmlspecialchars($_REQUEST["currentFac"]);
                            if ($currentFac == "Yes") {
                                $currentFac = 1;
                            } else if ($currentFac == "No") {
                                $currentFac = 0;
                            }
                            $fachash = password_hash(htmlspecialchars($_REQUEST["facpw1"]), PASSWORD_DEFAULT);
                            
                            if (is_userID_taken_faculty($facuser)) {
                                $stmt = $conn->prepare("INSERT INTO Instructor (InsID, fname, lname, CurrentFac, HashPW) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param("issis", $facuser, $facfname, $faclname, $currentFac, $fachash);
                                $stmt->execute();
                                $stmt->close();

                                $_SESSION["userID"] = $facuser;
                                $_SESSION["hash"] = $fachash;

                                header("Location: "); // TODO: Update header location
                            } 
                        } else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_REQUEST["facID"])) {
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