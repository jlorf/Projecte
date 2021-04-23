<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inici</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div w3-include-html="navbar.html" id="navbar"></div>
    <div id="login" show="false">
        <?php include("login.php"); ?> 
    </div>
    <div w3-include-html="resetform.html" id="reset" show="false"></div>
    <div w3-include-html="footer.html" id="footer"></div>
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/include.js"></script>
    <script src="js/index.js"></script>
</body>

</html>