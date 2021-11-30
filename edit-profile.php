<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

$uid = $_SESSION['user_id'];

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
$query = "SELECT * FROM users WHERE id=$uid";

$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    header('location: ./blog.php');
    exit();
}

$last_post_query = "SELECT * FROM posts WHERE user_id=$uid ORDER BY id DESC";
$last_post_result = mysqli_query($link, $last_post_query);

if (isset($_POST['submit'])) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if (!empty($_POST['editname'])) {
        $name = filter_input(INPUT_POST, 'editname', FILTER_SANITIZE_STRING);
        $name = trim($name);
        $name = mysqli_real_escape_string($link, $name);

        if (mb_strlen($name) < 2) {
            $is_form_valid = false;
            $errors['name'] = '* Name is required with minimum 2 characters!';
        }
    } else {
        $name = $_SESSION['user_name'];
    }

    if (!empty($_POST['editemail'])) {
        $email = filter_input(INPUT_POST, 'editemail', FILTER_SANITIZE_EMAIL);
        $email = trim($email);
        $email = mysqli_real_escape_string($link, $email);

        if ($email && mb_strlen($email) < 5) {
            $is_form_valid = false;
            $errors['editemail'] = '* A valid email is required!';
        } else {
            $query = "SELECT email FROM users WHERE email='$email'";
            $result = mysqli_query($link, $query);

            if (!$result) {
                $is_form_valid = false;
                $errors['submit'] = '* Error connecting to network, please try again later!';
            }

            if ($result && mysqli_num_rows($result) > 0) {
                $is_form_valid = false;
                $errors['editemail'] = '* Email already taken, please choose another one!';
            } else {
                $success['editemail'] = '* Email is available for use!';
            }
        }
    } else {
        $email = $user['email'];
    }

    $profile_image = $user['profile_image'] ? $user['profile_image'] : 'profile-image.png';
    
    if (!empty($_post['newimage'])) {
        $image = $_FILES['newimage'] ?? NULL;
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        define('MAX_FILE_SIZE', 1024 * 1024 * 2);

        if (
            isset($image) &&
            isset($image['name']) &&
            $image['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($image['tmp_name']) &&
            $image['size'] <= MAX_FILE_SIZE && // TODO Better to alert the user the file is too big
            in_array(pathinfo($image['name'])['extension'], $allowed) // TODO Better to alert the user the file extention dosen't match
        ) {
            $profile_image = date('Y.m.d.H.i.s') . '-' . $image['name'];
            move_uploaded_file($image['tmp_name'], "./images/profiles/$profile_image");
        } else {
            $profile_image = random_image();
        }
    }

    $is_form_valid = true;

    if ($is_form_valid) {
        $uid = $_SESSION['user_id'];
        global $name;
        global $email;

        $query = "UPDATE users SET name='$name', email='$email', profile_image='$profile_image' WHERE id=$uid";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./profile.php');
            exit();
        }
        $error['submit'] = 'Something went wrong, please try again later!';
    }
}

$page_title = 'Edit profile';
require_once './templates/header.php';
?>

<section class="container p-4 col-md-8">
    <h1 class="mt-4 display-4">Edit Profile</h1>
    <br>
    <form method="post">
        <div class="d-flex flex-column">
            <b>
                <div class="mb-3 form-floating">
                    <input style="width: 60%;" type="text" value="<?= posted_value('editname') ?>" class="form-control" id="name" name="editname">
                    <label for="name">Edit user name</label>
                    <?= field_errors('editname') ?>
                </div>

                <div class="mb-3 form-floating">
                    <input style="width: 60%;" type="email" value="<?= posted_value('editemail') ?>" class="form-control" id="email" name="editemail">
                    <label for="editemail">Edit email address</label>
                    <?= field_errors('editemail') ?>
                    <?= email_success('editemail') ?>
                </div>
            </b>
        </div>
        <br>
        <div class="d-flex flex-column">
            <label>Current image:</label>
            <img src="images/profiles/<?= $user['profile_image'] ?>" class="float-end rounded" alt="<?= $user['name'] ?>" width="40%">
            <br>
            <div style="width: 45%;" class="mb-3">
                <label for="formFile" class="form-label">Choose new profile image</label>
                <input class="form-control" type="file" name="newimage" id="formFile">
            </div>
        </div><br>
        <button type="submit" name="submit" value="submit" class="btn btn-outline-success">Confirm changes</button>

        <a href="./profile.php" class="btn btn-outline-danger">Cancel</a>
        <?= field_errors('submit') ?>
    </form>
    <?php

    include './templates/footer.php';
