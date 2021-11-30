<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(true, 'blog.php');

if (validate_csrf() && isset($_POST['submit'])) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $name = trim($name);
    $name = mysqli_real_escape_string($link, $name);

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = trim($email);
    $email = mysqli_real_escape_string($link, $email);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = trim($password);

    $repeat_password = filter_input(INPUT_POST, 'repeat_password', FILTER_SANITIZE_STRING);
    $repeat_password = trim($repeat_password);
    $repeat_password = mysqli_real_escape_string($link, $repeat_password);

    $profile_image = 'profile-image.png';
    $image = $_FILES['image'] ?? NULL;

    $is_form_valid = true;

    if (!$name || mb_strlen($name) < 6 || !preg_match("/^[A-Za-z][A-Za-z0-9' -_]{5,31}$/", $name)) {
        $is_form_valid = false;
        $errors['name'] = '* A valid name is required with 6-32 characters using only letters and numbers!';
    }

    if (!$email || mb_strlen($email) < 5) {
        $is_form_valid = false;
        $errors['email'] = '* A valid email is required!';
    } else {
        $query = "SELECT email FROM users WHERE email='$email'";
        $result = mysqli_query($link, $query);

        if (!$result) {
            $is_form_valid = false;
            $errors['submit'] = '* Error connecting to network, please try again later!';
        }

        if ($result && mysqli_num_rows($result) > 0) {
            $is_form_valid = false;
            $errors['email'] = '* Email already taken, please choose another one!';
        } else {
            $success['email'] = '* Email is valid and can be used!';
        }
    }

    $pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,32}$/';

    if (!$password || !preg_match($pattern, $password)) {
        $is_form_valid = false;
        $errors['password'] = "<br>* Password must be 8-32 long and must contain:<br>
        -At least 1 lowercase letter,<br>
        -At least 1 uppercase letter,<br>
        -At least 1 number,<br>
        -At least 1 special character!";
    } else if (!$repeat_password || $password !== $repeat_password) {
        $is_form_valid = false;
        $errors['repeat_password'] = 'Passwords do not match, please try again!';
    }

    if ($is_form_valid) {

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


        if ($is_form_valid) {
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users VALUES(NULL, '$email', '$password', '$name', '$profile_image', now())";
            $result = mysqli_query($link, $query);

            if ($result && mysqli_affected_rows($link) === 1) {

                $uid = (string) mysqli_insert_id($link);
                $_SESSION['user_id'] = $uid;
                $_SESSION['user_name'] = $name;

                header('location: ./blog.php');
                exit();
            } else {
                $error['submit'] = 'Something went wrong, please try again later!';
            }
        }
    }
}

$page_title = 'Sign Up';
require_once './templates/header.php';
?>

<section class="container p-4">
    <h1 class="mt-4 display-4">Sign Up, only to become part of something bigger!</h1>
    <p>Wanna be part of the community? Fill in your info and start right up! Dont worry, we won't share things we shouldn't.</p>
    <form action="" class="my-5" method="POST" enctype="multipart/form-data" novalidate="novalidate">

        <input type="hidden" name="token" value="<?= csrf() ?>">
        <div style="width: 65%;" class="mb-3 form-floating">
            <input type="text" value="<?= posted_value('name') ?>" class="form-control" id="name" aria-describedby="nameHelp" name="name">
            <label for="name">Tell us your name</label>
            <?= field_errors('name') ?>
            <div id="nameHelp" class="form-text">Others will see you by this name.</div>
        </div>
        <div style="width: 65%;" class="mb-3 form-floating">
            <input type="email" value="<?= posted_value('email') ?>" class="form-control" id="email" aria-describedby="emailHelp" name="email">
            <label for="email">Email address</label>
            <?= field_errors('email') ?>
            <?= email_success('email') ?>
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div style="width: 65%;" class="mb-3 form-floating">
            <input type="password" class="form-control" id="password" name="password" value="<?= posted_value('password') ?>">
            <label for="password">Choose Password</label>
            <a class="btn btn-outline-secondary" onclick="password_show_hide();">
                <i class="bi bi-eye d-none" id="hide_eye"></i>
                <i class="bi bi-eye-slash" id="show_eye"></i>
            </a>
            <span id="passwordHelp" aria-describedby="passwordHelp" class="form-text">Remember to keep your password to yourself.</span>
            <?= field_errors('password') ?>
            <?= field_errors('repeat_password') ?>
        </div>
        <div style="width: 65%;" class="mb-3 form-floating">
            <input type="password" class="form-control" id="repeat_password" name="repeat_password" value="<?= posted_value('repeat_password') ?>">
            <label for="repeat_password">Re-enter password</label>
            <i class="bi bi-eye-slash btn btn-outline-secondary" id="togglePassword"></i>

            <?= field_errors('repeat_password') ?>
        </div>
        <div style="width: 65%;" class="mb-3">
            <label for="formFile" class="form-label">Choose profile image</label>
            <input class="form-control" type="file" name="image" id="formFile">
        </div>
        <button type="submit" name="submit" value="submit" class="btn btn-primary">Sign Up</button>
        <?= field_errors('submit') ?>
    </form>
</section>

<script>
    function password_show_hide() {
        var x = document.getElementById("password");
        var show_eye = document.getElementById("show_eye");
        var hide_eye = document.getElementById("hide_eye");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }
</script>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#repeat_password');
    togglePassword.addEventListener('click', function(e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye');
    });
</script>

<?php
include_once './templates/footer.php';
