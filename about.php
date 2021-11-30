<?php
require_once 'app/helpers.php';
session_start();

$page_title = 'About';
require_once './templates/header.php';
?>

<section class="container p-4">
    <h1 class="mt-4 display-4">About, what's this all really about?</h1>
    <p>This website was made as a school PHP project, feel free to make it a thing :D</p>
    <p>Go ahead and download the source files from my drive or take a look at my LinkdIn/Github (links below), and see all my other projects if you are really interested by the source code.</p>

    <a target="_blank" href="https://www.linkedin.com/in/ghaleb-abu-ghosh-a3645655/"><i class="bi bi-linkedin fs-4 mb-3"></i></a><br>
    <a target="_blank" href="https://github.com/Kratos5212FS"><i class="bi bi-github fs-4 mb-3"></i></a><br>
    <a target="_blank" href="https://drive.google.com/drive/folders/1ntMSuDNCOLkV_M3ElgGW5Hv3mg3lxw01?usp=sharing"><i class="bi bi-google fs-4 mb-3"></i></a><br>
    <a target="_blank" href="https://www.instagram.com/magicgav5212/"><i class="bi bi-instagram fs-4 mb-3"></i></a>

</section>

<?php
include_once './templates/footer.php';
