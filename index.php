<?php
require "functions.php";

check_login();
?>
<!DOCTYPE html>
<html>

<head>
    <title>my Website</title>
</head>

<body>

    <?php include "header.php"; ?>


    <div style="max-width:600px; margin: auto; ">
        <h3 style="text-align: center;">Timeline</h3>

        <?php $id = $_SESSION['logged']['id'];
        $query = "select * from posts order by id desc";
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
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <?php include "footer.php"; ?>


</body>