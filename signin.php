<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(true, './');

if (validate_csrf() && isset($_POST['submit'])) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = trim($email);
    $email = mysqli_real_escape_string($link, $email);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = trim($password);

    $is_form_valid = true;
    if (!$email) {
        $is_form_valid = false;
        $errors['email'] = 'Please enter email!';
    }

    if (!$password) {
        $is_form_valid = false;
        $errors['password'] = 'Please enter password!';
    }

    if ($is_form_valid) {
        $query = "SELECT * FROM users WHERE email='$email'";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                header('Location: ./');
                die;
            } else {
                $errors['password'] = '* Wrong email or password!';
            }
        } else {
            $errors['submit'] = '* Wrong user or password!';
        }
    }
}

$page_title = 'Sign In';
require_once './templates/header.php';
?>

<section class="container p-4">
    <h1 class="mt-4 display-4">Sign In, if you want to interact with other users</h1>
    <p>In order to post stuff and like or reply to other people you need to sign in</p>
    <form class="my-5" method="POST">
        <input type="hidden" name="token" value="<?= csrf() ?>">
        <div style="width: 65%;" class="mb-3 form-floating">
            <input type="email" value="<?= posted_value('email') ?>" class="form-control" id="email" aria-describedby="emailHelp" name="email">
            <label for="email">Email address</label>
            <?= field_errors('email') ?>
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div style="width: 65%;" class="mb-3 form-floating">
            <input type="password" class="form-control" id="password" name="password">
            <label for="password">Password</label>
            <?= field_errors('password') ?>
            <?= field_errors('submit') ?>
        </div>
        <button type="submit" name="submit" value="submit" class="btn btn-primary">Sign In</button>
    </form>
</section>

<?php
include_once './templates/footer.php';
