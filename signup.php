<!DOCTYPE html>
<html>

<head>
    <title>Signup - my Website</title>
</head>

<body>

    <?php include "header.php" ?>
    <div style="margin: auto; max-width: 600px;">
        <h2 style="text-align: center;">Signup</h2>
        <form method="post" style="margin: auto; padding:10px;">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="text" name="email" placeholder="Email"><br>
            <input type="text" name="password" placeholder="Password"><br>

            <button>Signup</button>
        </form>
    </div>

    <?php include "footer.php" ?>


</body>