<?php
session_start();
$ftp_server = "ftp.kmkelm.org";
$ftp_user_name = "kmkelmoftp@kmkelm.org";
function load_db_pass() {
    $filename = "/home/kmkelmo1/kmkelm.org/kmkelmoftp/kmk.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    
    return $contents;
}

echo $ftp_user_pass;
$ftp_user_pass = load_db_pass();
$ftp_port = 21;
$dir = '/home/kmkelmo1/public_html';

echo "userID " . htmlspecialchars($_SESSION["UserID"]) . "<br>";

$conn_sql = new mysqli("localhost", "kmkelmo1", load_db_pass(), "kmkelmo1_student_showcase");
$id = $conn_sql->prepare("SELECT UserID FROM Student WHERE UserID = ?");
$id->bind_param("i",htmlspecialchars($_SESSION["UserID"]));
$id->execute();
$result_user = $id->get_result();
$id->close();

$row = $result_user->fetch_assoc();
$user = $row["UserID"];
//returns correct value
//projects not implemented yet, will cause issues

//$projid = $conn_sql->prepare("SELECT StudentProj.ProjID FROM StudentProj JOIN Student ON StudentProj.userID = Student.userID WHERE StudentProj.ProjID = ?");
//$projid->bind_param("s",$_SESSION["username"]);
//$projid->execute();
//$result_proj = $projid->get_result();
//$projid->close();

//$row = $result_proj->fetch_assoc();
//$proj = $row["projID"];

//i hate php
//no clue if this will work, prob wont



// set up basic connection
//working
//$destination_file = "/" . $_FILES['file']['name'];
//$destination_file = "/home/kmkelmo1/kmkelm.org/kmkelmoftp/" . "$source_file";

$add = $_FILES['file']['name'];
$destination_file = $add[0];
//final dest file statement below, temp above until projects implemented
//$destination_file = "/$user/$proj" . $_FILES['file']['name'];
//too many slashes
echo $destination_file . "<br>";
$conn_id = ftp_connect($ftp_server,$ftp_port);
// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
// ftp passive cmd
ftp_pasv($conn_id, true);
// check connection
if ((!$conn_id) || (!$login_result)) { 
    echo "FTP connection has failed!";
    echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
    exit; 
} else {
    echo "Connected to $ftp_server, for user $ftp_user_name";
}
$dir = mkdir("/home/kmkelmo1/kmkelm.org/kmkelmoftp/$user/", 0777);
$brug = ftp_chdir($conn_id, $user);
echo "$user";
if($brug == false){
    echo "<br>" . "AEUGH" . "<br>";
}
if(isset($_POST['submit'])){
//not sure if this is the right place to put this
 // Count total files
$countfiles = count($_FILES['file']['tmp_name']);
 
 // Looping all files
 //echo $countfiles;
 //multiselect is not working
 //I have been working on this for 2 weeks
 //I am going to commit a crime (in minecraft)
 //this will be commented out to make it work
 //global $upload;
// if(count($_FILES['file']['tmp_name']) > 1){
//     foreach ($_FILES['file']['tmp_name'] as $file){
//         $upload = ftp_put($conn_id, "/$destination_file", $file, FTP_BINARY);
 //    }
// }
//this is all you really need to
$test_file = $_FILES['file']['name'];
$source_file = $_FILES['file']['tmp_name'];
echo $source_file[0] . "<br>";

//for($i=0;$i<$countfiles;$i++){
   //$source_file = $_FILES['file']['name'] . "." . pathinfo($_FILES['file']['name'])['extension'];
   // do not need extension
    
 //}
}

// upload the file
//$upload = ftp_put($conn_id, '$ftp_pwd($conn_id)', $source_file, FTP_BINARY);
$upload = ftp_put($conn_id, $test_file[0], $source_file[0], FTP_BINARY);

// check upload status
if (!$upload) {
    echo "<br>";
print_r(error_get_last());
echo "FTP upload has failed!";
} else {
echo "Uploaded $source_file to $ftp_server as $destination_file";
}

// close the FTP stream 
ftp_close($conn_id);
?>
