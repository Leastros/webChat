<?php
session_start();

if (isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
}

// Redirect to the login page or another appropriate page
header('Location: ?page=login');
exit;
