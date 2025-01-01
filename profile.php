<?php
require "functions.php";

check_login();
///////////////////////////////// delete post ////////////////////

if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'post_delete') {
    $id = $_GET['id'] ?? 0;
    $user_id = $_SESSION['logged']['id'];

    $query = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (file_exists($row['image'])) {
            unlink($row['image']);
        }
    }

    $query = "delete from posts where id = '$id' && user_id = '$user_id' limit 1";
    $result = mysqli_query($con, $query);


    header("Location: profile.php");
    die;


//////////////////////////////////  edit post  ///////////////////////

} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'post_edit') {

    $image_added = false;
      $id = $_GET['id'] ?? 0;
    $user_id = $_SESSION['logged']['id'];

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {

        $folder = 'uploads/';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $image = $folder . $_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'], $image);

        $query = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
    
            if (file_exists($row['image'])) {
                unlink($row['image']);
            }
        }
        $image_added = true;
    }

    $post = addslashes($_POST['post']);

    if ($image_added == true) {
        $query = "update posts set post = '$post', image = '$image' where id = '$id' && user_id = '$user_id' limit 1";
    } else {
        $query = "update posts set post = '$post' where id = '$id' && user_id = '$user_id' limit 1";
    }

    $result = mysqli_query($con, $query);

   
        header("Location: profile.php");
        die;
    
}
///////////////////////////////// delete profile ////////////////////

elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'delete') {

    $id = $_SESSION['logged']['id'];
    $query = "delete from users where id = '$id' limit 1";
    $result = mysqli_query($con, $query);

    if (file_exists($_SESSION['logged']['image'])) {
        unlink($_SESSION['logged']['image']);
    }

    $query = "delete from posts where user_id = '$id'";
    $result = mysqli_query($con, $query);


    header("Location: logout.php");
    die;


//////////////////////////////////  edit profile  ///////////////////////

} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username'])) {

    $image_added = false;
    // echo "<pre>";
    // print_r($_FILES);
    // die();
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {

        $folder = 'uploads/';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $image = $folder . $_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'], $image);

        if (file_exists($_SESSION['logged']['image'])) {
            unlink($_SESSION['logged']['image']);
        }
        $image_added = true;
    }

    $username = addslashes($_POST['username']);
    $password = addslashes($_POST['password']);
    $email = addslashes($_POST['email']);
    $id = $_SESSION['logged']['id'];

    if ($image_added == true) {
        $query = "update users set username = '$username', password = '$password', email = '$email', image = '$image' where id = '$id' limit 1";
    } else {
        $query = "update users set username = '$username', password = '$password', email = '$email' where id = '$id' limit 1";
    }

    $result = mysqli_query($con, $query);

    $query = "select * from users where id = '$id' limit 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);
        $_SESSION['logged'] = $row;

        header("Location: profile.php");
        die;
    }

////////////////////////////// add a profile ///////////////////////////////
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['post'])) {

    $image = '';
    // echo "<pre>";
    // print_r($_FILES);
    // die();
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {

        $folder = 'uploads/';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $image = $folder . $_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'], $image);

    }

    $post = addslashes($_POST['post']);
    $date = addslashes(date('Y-m-d H:i:s'));
    $id = $_SESSION['logged']['id'];


    $query = "insert into posts (user_id,post,date,image) values ('$id','$post','$date','$image')";


    $result = mysqli_query($con, $query);

    header("Location: profile.php");
    die;

}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Profile - my Website</title>
</head>

<body>

    <?php include "header.php" ?>

    <div style="margin: auto; max-width: 600px;">

        <?php if (!empty($_GET['action']) && $_GET['action'] == 'post_edit' && !empty($_GET['id'])): ?>
            <?php
            $id = (int)$_GET['id'];
            $query = "select * from posts where id = '$id' limit 1";
            $result = mysqli_query($con, $query);
            ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $row = mysqli_fetch_assoc($result); ?>

                <h5>Edit a post</h5>
                <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">

                    <img src="<?= $row['image'] ?>" style="width:100%; height:200px; object-fit: cover;">
                    image: <input type="file" name="image">

                    <textarea name="post" rows="9"><?= $row['post'] ?></textarea><br>
                    <input type="hidden" name="action" value="post_edit">


                    <button>Save</button>

                    <a href="profile.php">
                        <button type="button">Cancel</button>
                    </a>
                </form>
            <?php endif; ?>

        <?php elseif (!empty($_GET['action']) && $_GET['action'] == 'post_delete' && !empty($_GET['id'])): ?>
            <?php
            $id = (int)$_GET['id'];
            $query = "select * from posts where id = '$id' limit 1";
            $result = mysqli_query($con, $query);
            ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $row = mysqli_fetch_assoc($result); ?>

                <h3>Are you sure you want to delete this post?</h3>
                <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">

                    <img src="<?= $row['image'] ?>" style="width:100%; height:200px; object-fit: cover;">

                    <div><?= $row['post'] ?></div><br>
                    <input type="hidden" name="action" value="post_delete">


                    <button>Delete</button>

                    <a href="profile.php">
                        <button type="button">Cancel</button>
                    </a>
                </form>
            <?php endif; ?>

        <?php elseif (!empty($_GET['action']) && $_GET['action'] == 'edit'): ?>

            <h2 style="text-align: center;">Edit Profile</h2>
            <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">
                <img src="<?php echo $_SESSION['logged']['image'] ?>"
                    style="width: 150px; height: 150px; object-fit: cover;  margin: auto; display:block;">
                <input type="file" name="image"><br>
                <input value="<?php echo $_SESSION['logged']['username'] ?>" type="text" name="username"
                    placeholder="Username" required><br>
                <input value="<?php echo $_SESSION['logged']['email'] ?>" type="email" name="email" placeholder="Email"
                    required><br>
                <input value="<?php echo $_SESSION['logged']['password'] ?>" type="password" name="password"
                    placeholder="Password" required><br>

                <button>Confirm</button>
                <a href="profile.php">
                    <button type="button">Cancel</button>
                </a>
            </form>


        <?php elseif (!empty($_GET['action']) && $_GET['action'] == 'delete'): ?>

            <div style="text-align: center;">

                <h2 style="text-align: center;">Are you sure you want to delete your profile?</h2>
                <form method="post" style="margin: auto; padding:10px;">
                    <img src="<?php echo $_SESSION['logged']['image'] ?>"
                        style="width: 150px; height: 150px; object-fit: cover;  margin: auto; display:block;">
                    <div> <?php echo $_SESSION['logged']['username'] ?> </div>
                    <div> <?php echo $_SESSION['logged']['email'] ?> </div>
                    <input type="hidden" name="action" value="delete">
                   
                    <button>Delete</button>
                    <a href="profile.php">
                        <button type="button">Cancel</button>
                    </a>
                </form>
            </div>

        <?php else: ?>

            <h2 style="text-align: center;">User Profile</h2>
            <br>
            <div style="text-align: center;">
                <div>
                    <img src="<?php echo $_SESSION['logged']['image'] ?>"
                        style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <br>
                <div>
                    <?php echo $_SESSION['logged']['username'] ?>
                </div>
                <div>
                    <?php echo $_SESSION['logged']['email'] ?>
                </div>

                <a href="profile.php?action=edit">
                    <button>Edit</button>
                </a>

                <a href="profile.php?action=delete">
                    <button>Delete</button>
                </a>
            </div>

            <hr>
            <h5>Create a post</h5>
            <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">

                image: <input type="file" name="image">
                <textarea name="post" rows="9"></textarea><br>

                <a href="profile.php?action=post">
                    <button>Post</button>
                </a>
            </form>

            <hr>
            <div>
                <?php $id = $_SESSION['logged']['id'];
                $query = "select * from posts where user_id = '$id' order by id desc";
                $result = mysqli_query($con, $query);

                ?>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $user_id = $row['user_id'];
                        $query = "select username, image from users where id = '$user_id' limit 1";
                        $result_user = mysqli_query($con, $query);

                        $user_row = mysqli_fetch_assoc($result_user);
                        ?>
                        <div
                            style="background-color: white; display:flex; border:solid thin #aaa; border-radius: 10px; margin-bottom: 10px; margin-top: 10px;">
                            <div style="flex:1; text-align:center;">
                                <?= $user_row['username'] ?>
                                <br>
                                <img src="<?= $user_row['image'] ?>"
                                    style="border-radius:50%; width:100px; height:100px; object-fit: cover; margin:10px;">
                                <?= $row['date'] ?>;
                            </div>

                            <div style="flex:8">
                                <?php if (file_exists($row['image'])): ?>
                                    <div>
                                        <img src="<?= $row['image'] ?>" style="width:100%; height:200px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <?php echo $row['post'] ?>
                                </div>
                                <br>

                                <a href="profile.php?action=post_edit&id=<?= $row['id'] ?>">
                                    <button>Edit</button>
                                </a>

                                <a href="profile.php?action=post_delete&id=<?= $row['id'] ?>">
                                    <button>Delete</button>
                                </a>
                                <br>
                                <br>


                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>


    </div>

    <?php include "footer.php" ?>


</body>