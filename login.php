<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Showcase Login</title>
    </head>
    <body>
        <div>
            <form action="login.php" method="post" id="login" onSubmit="return validate();">
                <div class="login-form">
                    <div class="form-head">Login</div>
                    <?php
                    if (isset($_SESSION["errorMessage"])) {
                    ?>
                    <div class="error-message"><?php  echo $_SESSION["errorMessage"]; ?></div>
                    <?php
                    unset($_SESSION["errorMessage"]);
                    }
                    ?>
                    <div class="field">
                        <div>
                            <label for="username">Username</label><span id="user_info"
                                class="error-info"></span>
                        </div>
                        <div>
                            <input name="username" id="username" type="text" placeholder="Enter Username">
                        </div>
                    </div>
                    <div class="field">
                        <div>
                            <label for="password">Password</label><span id="password_info"
                                class="error-info"></span>
                        </div>
                        <div>
                            <input name="password" id="password" type="password" placeholder="Enter Password">
                        </div>
                    </div>
                    <div class=field>
                        <div>
                            <input type="submit" name="login" value="Login" class="loginBtn"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>