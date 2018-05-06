<?php include_once "common/access.php" ?>
<nav class="navigation">
    <ul>
        <li>
            <a href="index.php">Lists</a>
        </li>
        <li>
            <a href="about.php">About</a>
        </li>
        <li>
            <?php if (loggedIn()) { ?>
            <div class="navigation-dropdown">
                <a href="#">&dArr; Profile </a>
                <div class="navigation-dropdown-content">
                    <a href="profile.php">Settings</a>
                    <a href="api/user/logout.php">Logout</a>
                </div>
            </div>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </li>
    </ul>
</nav>