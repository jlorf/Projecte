<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="rutaImatge" id="icon" alt="Canviar ruta imatge" />
            </div>

            <!-- Login Form -->
            <form name="myForm" id="myForm" method="post" onsubmit="return localpost()">
                <input type="text" id="email" class="fadeIn second" name="email" placeholder="login">
                <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
                <input type="submit" class="fadeIn fourth" value="Login" click="return EnviarForm();" />                
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="index.html?reset=true">Has oblidat la contrasenya?</a>
            </div>

        </div>
    </div>
    <script type="text/javascript" src="js/login.js"></script>
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>