<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

if (validate_csrf() && isset($_GET['rid']) && is_numeric($_GET['rid'])) {
    $rid = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);

    if ($rid) {
        $uid = $_SESSION['user_id'];
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

        $query = "DELETE FROM replies WHERE id=$rid AND user_id=$uid";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./blog.php');
            exit;
        } else {
            exit('Could not delete reply, please try again later!');
        }
    }
}
header('location: ./blog.php');
exit;
