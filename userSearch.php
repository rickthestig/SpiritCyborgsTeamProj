<!DOCTYPE html>
<?php 
   include('domainConfig.php');
   session_start();
   ob_start();
   function is_data_valid_search() {
      if ($_SERVER["REQUEST_METHOD"] !== "POST") {
          return false;
      }
      if (empty($_REQUEST["search"])) {
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
          div.login {
              margin-left: 15%;
              margin-right: 15%;
              width: auto;
          }
          table {
              text-align: center;
              border-spacing: 10px;
              border-style: solid;
          }
          </style>
          <script>
              window.addEventListener("load", function() {
                  let searchForm = document.forms.searchForm;
                  let errLog = document.getElementById("errLog");
                  searchForm.addEventListener("submit", function(event) {
                      if (searchForm.search.value === "") {
                          event.preventDefault();
                          errLog.style.display = "initial";   
                          errLog.innerHTML = "Please enter a User ID";                    
                      }
                  });
              });
              
          </script>
      </head>
      <div class="navbar">
          <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="userCreate.php">User Creation</a></li>
              <li><a href="userLogin.php">Log In</a></li>
              <li><a href="userSearch.php">Search</a></li>
              <?php if(isset($_SESSION["UserID"]) && isset($_SESSION["hash"])) {?>
              <li><a href="dashboard.php">Dashboard</a></li><?php  }  ?>
          </ul>
      </div>
      <body>
          <h1>Search for Student</h1>
          <div class="windows">
              <div class="login" style="width: 1000px;">
                  <form id="searchForm" method="post">
                      <table>
                          <caption>Search for a student</caption>
                          <tr>
                              <th><label for="searchForm">Student ID:</label></th>
                          </tr>
                          <tr>
                              <td><input type="text" style="display:block; margin-left:auto; margin-right:auto;" id="searchForm" name="search"></td>
                          </tr>
                          <tr>
                              <td colspan="2" style="text-align:center;"><button type="submit">Search
                          </tr>
                          <tr>
                              <td colspan="2"><p id="errLog">
                              <?php 
                              if ($conn->connect_error) {
                                  die("Connection failed: " . $conn->connect_error);
                              } else if (is_data_valid_search()) {
                                  $userid = intval(htmlspecialchars($_REQUEST["search"]));
                                  $result = mysqli_query($conn, "SELECT * from Student WHERE UserID = $userid");
                                  $projects = mysqli_query($conn, "SELECT * from StudentProj WHERE UserID = $userid");
                                  if (empty($result)) {
                                      echo "No user found.";
                                  } 
                                  else {
                                      while($srow = mysqli_fetch_array($result)) {
                                          $status = $srow["CurrentStudent"];
                                          if ($status == 1) {
                                              $status = "Yes";
                                          } else {
                                              $status = "No";
                                          }
                                          echo "
                                          <h1>Student Information for ".$srow["fname"]." ".$srow["lname"]."</h1>
                                          <table>
                                                  <tr>
                                                      <th>User ID</th>
                                                      <th>First Name</th>
                                                      <th>Last Name</th>
                                                      <th>Current Student</th>
                                                  </tr>
                                                  <tr>
                                                      <td>". $srow["UserID"]."</td>
                                                      <td>". $srow["fname"]."</td>
                                                      <td>". $srow["lname"]."</td>
                                                      <td>". $status."</td>
                                                  </tr>
                                          </table>
                                          <p></p>";
                                      }
                                      while($prow = mysqli_fetch_array($projects)) {
                                          echo "
                                          <h2>Project List</h2>
                                          <table>
                                                  <tr>
                                                      <th>Project ID</th>
                                                      <th>Status when Completed</th>
                                                  </tr>
                                                  <tr>
                                                      <td>". $prow["ProjID"]."</td>
                                                      <td>". $prow["StudStatus"]."</td>
                                                  </tr>
                                          </table>";
                                      }
                                  }
  
                              } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                  echo "Bad form";
                              }
                              $conn->close();                     
                              ?>
                              </p></td>
                          </tr>
                      </table>
                  </form>
                  <h2><a href = "logout.php">Sign Out</a></h2>
              </div>
              <br>
          </div>
      </body>
  </html>
      

   
