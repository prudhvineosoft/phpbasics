<?php
error_reporting(0);
include("connection.php");

session_start();
$User_Email = $_SESSION['User_Email'];
$sel = mysqli_query($conn, "select * from users WHERE Email like '$User_Email' or User_name like '$User_Email' ");
$arr = mysqli_fetch_assoc($sel);
$userId = $arr['Id'];
$userName = $arr['User_name'];
$r1 = range(0, 1000);
$r1rand = array_rand($r1);
$message = $error =  "";


if (isset($_POST['sub'])) {
    $title = input_field($_POST['title']);
    $description = input_field($_POST['description']);

    $tmp = $_FILES["att"]["tmp_name"];
    $fname = $_FILES["att"]["name"];
    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    $img_name  = $userName . $r1rand . "." . $ext;
    $img_path = "uploads/" . $img_name;
    $dest = "uploads/";

    if (mysqli_query($conn, "insert into post(User_id,Title	,Description,Image_path) 
    values('$userId','$title','$description','$img_path')")) {
        $message = 'Post Created Successfully';
        header('Location:dashboard.php');
        if (move_uploaded_file($tmp, $dest . $img_name)) {
            $error = "Uploaded succesfully";
        } else {
            $error = "Upload error";
        }
    } else {
        $message = "Error";
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

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("skeliton/header.php"); ?>
    <link rel="stylesheet" href="css/post.css?<?php echo time(); ?>">
</head>

<body>
    <div class="bg-container">


        <div class="login-card">

            <form method="post" enctype="multipart/form-data">
                <div class="">
                    <label for="image">Image</label><br>
                    <input type="file" name="att" class="" id="image" required>
                </div>
                <h4 class=""><?php echo $error ?></h4>
                <div class="">
                    <label for="title">Title</label>
                    <input type="text" id="title" onchange="cook()" name="title" placeholder="Add Title" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="validationTextarea">Textarea</label>
                    <textarea class="form-control " id="validationTextarea" name="description" placeholder=" textarea" required></textarea>
                    <div class="">
                        enter a message in the textarea.
                    </div>
                </div>
                <h4 class=""><?php echo $message ?></h4>
                <input type="submit" value="Add Post" name="sub" />
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