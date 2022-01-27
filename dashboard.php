<?php

session_start();
include("connection.php");
$User_Email = $_SESSION['User_Email'];
if (empty($User_Email)) {
    header('location:index.php');
} else {
    $sel = mysqli_query($conn, "select * from users WHERE Email like '$User_Email' or User_name like '$User_Email' ");
    $arr = mysqli_fetch_assoc($sel);
    $userId = $arr['Id'];
    $userName = $arr['User_name'];
    $Email = $arr['Email'];
    $password = $arr['Password'];
    $age = $arr['Age'];
    $Gender = $arr['Gender'];
}

if (isset($_POST['sub-comment'])) {
    $comment = input_field($_POST['comment']);
    $postId = $_POST['hidden'];
    if (mysqli_query($conn, "insert into comment (User_id,User_name,Post_id,Comment) 
    values ('$userId','$userName','$postId','$comment')")) {
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
    <link rel="stylesheet" href="css/dashboard.css?<?php echo time(); ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light my-nav">
        <a class="navbar-brand" href="#"><img src="css/pm logo.png" class='logo' alt="img"></a><span class="ml-0 l-name">PostMe</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon pt-0"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
                <a class="nav-link text-danger" href="#">
                    <span class="text-danger font-weight-bold"><?php echo $userName ?></span><span class="sr-only ">(current)</span>
                </a>
                <a href="dashboard.php" class="nav-link text-light">Posts</a>
                <a href="myposts.php" class="nav-link text-light">My Posts</a>
                <a href="post.php" class="nav-link text-light">New Post</a>
                <a href="logout.php" class="nav-link text-light">Logout</a>
            </div>
        </div>
    </nav>
    <div class="content">
        <div class="bg-container">
            <?php $sel_post = mysqli_query($conn, "select * from post WHERE User_id != $userId order by Created_at desc");
            if (mysqli_num_rows($sel_post) > 0) {

                while ($arr_post = mysqli_fetch_assoc($sel_post)) {
            ?>
                    <div class="post">
                        <img src="<?php echo $arr_post['Image_path'] ?>" class="post-img" alt="image">
                        <div class="mb-3">
                            <?php
                            $us_Id = $arr_post['User_id'];
                            $accessing_name = mysqli_query($conn, "select * from users WHERE Id = $us_Id ; ");
                            $arr_u_name = mysqli_fetch_assoc($accessing_name)
                            ?>
                            <p class="pl-5 font-weight-bold text-success">@<?php echo $arr_u_name['User_name'] ?></p>

                            <div class="post-body">
                                <h5 class="card-title text-info font-weight-bold "><?php echo $arr_post['Title'] ?></h5>
                                <p class="mb-1 card-text text-light"><?php echo $arr_post['Description'] ?></p>
                                <p class="pt-0 card-text"><small class="text-muted">Created at <?php echo $arr_post['Created_at'] ?></small></p>
                                <!-- messsage input -->
                                <div class="row d-flex flex-row justify-content-start">
                                    <form method="post">
                                        <p>
                                            <a class="pl-3 d-flex flex-row justify-content-start change-button" data-toggle="collapse" href="#collapseExamplez<?php echo $arr_post['Id'] ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="far fa-comments"></i>&nbsp Comment
                                            </a>

                                        </p>
                                        <div class="collapse" id="collapseExamplez<?php echo $arr_post['Id'] ?>">
                                            <div class="pl-3 d-flex flex-row justify-content-center">
                                                <input type="text" name="comment" id="comment" class="bg-success" />
                                                <input type="hidden" name="hidden" id="hidden" value="<?php echo $arr_post['Id'] ?>" />
                                                <input type="submit" name="sub-comment" id="comment-submit" value="Submit" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- Message showing area -->
                                <?php
                                $pId = $arr_post['Id'];
                                $qC = mysqli_query($conn, "select * from comment WHERE Post_id = $pId ");
                                if (mysqli_num_rows($qC) > 0) {
                                    while ($arr_c = mysqli_fetch_assoc($qC)) { ?>
                                        <p class="text-light"><?php echo $arr_c['Comment'] ?> - <span class="text-white-50"><?php echo $arr_c['User_name'] ?></p></span>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <div class="profile">
            <p class="mb-5">Name :<span class=""> &nbsp <?php echo $userName ?></span></p>
            <h6 class="mb-5">Suggestions For You <span class="ml-5 text-primary">See all</span></h6>
            <?php $friends_q = mysqli_query($conn, "select * from users WHERE User_name not like '$userName' order by Created_at desc");
            if (mysqli_num_rows($friends_q) > 0) {

                while ($friends = mysqli_fetch_assoc($friends_q)) {
            ?>
                    <p class=""><?php echo $friends['User_name'] ?>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<span class="ml-5 text-warning">Add Friend</span></p>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php include("skeliton/footer.php"); ?>
    <script type="text/javascript" src="vanilla-tilt.js"></script>
    <script type="text/javascript">
        VanillaTilt.init(document.querySelector(".profile"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.4,
        });
    </script>
</body>

</html>