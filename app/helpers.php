<?php
require_once('db_config.php');

if (!function_exists('is_logged_in')) {

    function is_logged_in()
    {
        return !empty($_SESSION['user_id']);
    }
}

if (!function_exists('redirect_auth')) {

    function redirect_auth($redirect_if_loggedin = true, $location = './')
    {
        if (
            $redirect_if_loggedin && is_logged_in() ||
            !$redirect_if_loggedin && !is_logged_in()
        ) {
            header("location: $location");
            exit();
        }
    }
}

if (!function_exists('field_errors')) {

    function field_errors($field_name)
    {
        global $errors;

        if (isset($errors) && !empty($errors[$field_name])) {
            return "<span class='text-danger form-text'>$errors[$field_name]</span>";
        }
    }
}

if (!function_exists('email_success')) {

    function email_success($field_name)
    {
        global $success;

        if (isset($success) && !empty($success[$field_name])) {
            return "<span class='text-success form-text'>$success[$field_name]</span>";
        }
    }
}

if (!function_exists('posted_value')) {

    function posted_value($field_name)
    {
        return isset($_REQUEST) && !empty($_REQUEST[$field_name]) ? $_REQUEST[$field_name] : '';
    }
}

if (!function_exists('ago')) {

    function ago($time)
    {
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();
        $time = strtotime($time);

        $difference = $now - $time;

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        if ($periods[$j] === "seconds") {
            return "<span class='text-warning'>[NEW]</span> A few $periods[$j] ago...";
        } else {
            return "$difference $periods[$j] ago ";
        }
    }
}


if (!function_exists('active_nav_item')) {

    function active_nav_item($item_title)
    {
        global $page_title;
        $class_str = '';

        if (isset($page_title) && $page_title === $item_title) {
            return ' active';
        }
        return $class_str;
    }
}

if (!function_exists('csrf')) {
    function csrf()
    {
        $token = sha1(time());
        $_SESSION['token'] = $token;

        return $token;
    }
}

if (!function_exists('validate_csrf')) {
    function validate_csrf()
    {
        if (isset($_REQUEST['token']) && isset($_SESSION['token'])){
            return $_REQUEST['token'] === $_SESSION['token'];
        }
        return false;
    }
}
if (!function_exists('random_image')) {

    function random_image()
    {
        $rand =   time() . '_' . rand(1, 100);
        $save_to = "images/profiles/$rand.svg";
        $curl = curl_init("https://avatars.dicebear.com/api/croodles/$rand.svg");

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        if (file_exists($save_to)) {
            unlink($save_to);
        }

        $f = fopen($save_to, 'x');
        fwrite($f, $response);
        fclose($f);

        return "$rand.svg";
    }
}