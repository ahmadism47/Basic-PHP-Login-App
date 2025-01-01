<style>
    * {
        padding: 0px;
        margin: 0px;
        box-sizing: border-box;
    }

    a {
        text-decoration: none;
    }

    body {
        background-color: #f8f8e7;
        font-family: tahoma;
    }

    header div {
        padding: 20px;
    }

    header a {
        color: white;
    }

    header {
        background-color: #8b8bf2;
        display: flex;
        color: white;
        justify-content: center;
        align-items: center;
    }

    footer {
        padding: 20px;
        text-align: center;
        background-color: #eee;
    }

    input {
        margin: 4px;
        padding: 10px;
        width: 100%;
    }

    textarea {
        margin: 4px;
        padding: 10px;
        width: 100%;
    }

    button {
        padding: 10px;
    }
</style>

<header>
    <div><a href="index.php">Home</a></div>
    <div><a href="profile.php">Profile</a></div>

    <?php if (empty($_SESSION['logged'])): ?>
        <div><a href="login.php">Login</a></div>
        <div><a href="signup.php">Signup</a></div>
    <?php else: ?>

        <div><a href="logout.php">Logout</a></div>
    <?php endif; ?>

</header>