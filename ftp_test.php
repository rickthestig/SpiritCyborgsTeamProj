<?php
$ftp_server = "your server or host";
$ftp_user_name = "username";
$filename = "/home/kmkelmo1/kmkelm.org/kmkelmoftp/kmk.txt";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
echo $ftp_user_pass
$ftp_user_pass = "password";
$ftp_port = "port";
$dir = '/public_html';

$id =  "SELECT userID FROM Student WHERE userID LIKE sessionUser";
$projid = "SELECT ProjID FROM StudentProj WHERE ProjID LIKE sessionProject";
//i hate php
$destination_file = "/public_html/$id/$projid/";
//no clue if this will work, prob wont
if(isset($_POST['submit'])){
//not sure if this is the right place to put this
 // Count total files
 $countfiles = count($_FILES['file']['tmp_name']);
 
 // Looping all files
 for($i=0;$i<$countfiles;$i++){
   $source_file = $_FILES['file']['tmp_name'][$i];
 }
}
// set up basic connection
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

// upload the file
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

// check upload status
if (!$upload) { 
echo "FTP upload has failed!";
} else {
echo "Uploaded $source_file to $ftp_server as $destination_file";
}

// close the FTP stream 
ftp_close($conn_id);
?>
