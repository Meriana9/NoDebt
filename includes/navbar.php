<header>
    <nav id="navbar">
        <div class="logo"><a href="index.php"><img src="./img/logo.png" width="100" height="100" alt="website logo"></a></div>
        <ul class="links">
            <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="create-group.php"><i class="fa fa-plus"></i> Create Group</a></li>
            <li><a href="invite-user.php"><i class="fa fa-envelope"></i> Invite User</a></li>
            <li><a href="contact.php"><i class="fa fa-phone"></i> Contact Us</a></li>
            <li class="user-info">
                <?php
                
                if (!isset($_SESSION["email"])){
                    ?>
                        <div><a href="register.php"><i class="fa fa-user-plus"></i> Register</a></div>
                        <div><a href="login.php"><i class="fa fa-lock"></i> Login</a></div>
                    <?php
                }else{
                    ?>
                        <div><a href="manage-profile.php"><i class="fa fa-user"></i> <?php echo $_SESSION["email"]; ?></a></div>
                        <div><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></div>
                    <?php
                }

                ?>
            </li>
        </ul>
    </nav>
</header>