<?php
require_once 'db.php';
if (isset($_SESSION['user'])) {
    logActivity($conn, $_SESSION['user']['name'], $_SESSION['user']['role'], 'Logged out of the portal');
}
unset($_SESSION['user']);
flashMessage('You have been logged out.', 'success');
redirectTo('index.php');
