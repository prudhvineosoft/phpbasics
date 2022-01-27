<?php
session_start();
include("connection.php");
$emailErr = $passErr =  $result = "";
if (isset($_POST['sub'])) {
    $em = input_field($_POST['email']);
    $pass = input_field($_POST['password']);

    if (empty($em)) {
        $emailErr = "<i class='far fa-lightbulb'></i>";
    } else {
        $sel = mysqli_query($conn, "select * from users WHERE Email like '$em' or User_name like '$em' ");

        $arr = mysqli_fetch_assoc($sel);
        if ($sel) {
            $emailErr =  "<i class='far fa-check-circle green'></i>";
            if (empty($pass)) {
                $passErr = "<i class='far fa-lightbulb'></i>";
            } else {
                if ($arr['Password'] == $pass) {
                    $result = 'success';

                    session_start();

                    $_SESSION["User_Email"] = $em;
                    header("Location: dashboard.php");

                    if (!empty($_POST["remember"])) {
                        setcookie("daEmail", $em, time() + 36000 * 24 * 30 * 12);
                        setcookie("daPass", $pass, time() + 36000 * 24 * 30 * 12);
                    }
                } else {
                    $passErr = "<i class='far fa-times-circle'></i>";
                }
            }
        } else {
            $emailErr =  "<i class='far fa-times-circle'></i>";
        }
    }
}

function input_field($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!doctype html>
<html lang="en">

<head>
    <?php include("skeliton/header.php"); ?>
    <script>
        function cook() {
            if ("<?php echo $_COOKIE["daEmail"] ?>" != undefined) {
                if (document.getElementById("Emailinput").value == "<?php echo $_COOKIE["daEmail"] ?>") {
                    if (document.getElementById("passwordInput").value = "<?php echo $_COOKIE["daPass"] ?>") {

                    } else {
                        document.getElementById("passwordInput").value = ""
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="css/index.css?<?php echo time(); ?>">
</head>

<body>
    <div class="bg-container">


        <div class="login-card">
            <form method="post">
                <div class="">
                    <label for="Emailinput">Email address</label><br>
                    <input type="text" id="Emailinput" onchange="cook()" name="email" placeholder="User name Or Email" class="" /><span class="error"><?php echo $emailErr; ?></span><br />
                </div>
                <div class="">
                    <label for="passwordInput">Password</label><br>
                    <input type="password" id="passwordInput" class="passeord_css" name="password" placeholder="......" /><span class="error"><?php echo $passErr; ?></span><br />
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="exampleCheck1">
                    <label class="form-check-label " for="dropdownCheck">
                        Remember me
                    </label>
                </div>
                <h2 class="green"><?php echo $result ?></h2>
                <input type="submit" value="login" name="sub" />
            </form>
        </div>
    </div>
    <?php include("skeliton/footer.php"); ?>
    <script type="text/javascript" src="vanilla-tilt.js"></script>
    <script type="text/javascript">
        VanillaTilt.init(document.querySelector(".login-card"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.4,
        });
    </script>
</body>

</html>