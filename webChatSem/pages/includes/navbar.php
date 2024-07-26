<!-- navigation bar -->
<?php

$loggedIn = false;
if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
}

?>

<nav class="navbar navbar-expand-sm bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Brand</a>

        <?php if ($loggedIn) : ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=messages">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger d-flex" href="?page=logout">
                            <span>Log Out</span>
                            <i class="bi bi-box-arrow-right ms-2"></i>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>
