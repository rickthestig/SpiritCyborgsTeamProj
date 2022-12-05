<?php

   //connect to the db schema
   $servername = "localhost:3306";
   $username = "kmkelmo1_kmkelmo1";
   $password = load_db_pass();
   $dbname = "kmkelmo1_student_showcase";

   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);

   // Check connection
   if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
   }
   
   function load_db_pass() {
    $filename = "/home/kmkelmo1/kmkelm.org/kmkelmoftp/kmk.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);

    return $contents;
}

?>