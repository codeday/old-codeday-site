<?php

$content = file_get_contents("http://clear.codeday.org/api/event/6v3KtjQGPDal");
$event = json_decode($content);
?>

<?php include 'header.php'; ?>

<?php include 'splash.php'; ?>

<?php include 'faq.php'; ?>

<?php include 'schedule.php'; ?>

<?php include 'pitch.php'; ?>

<?php include 'sponsors-list.php'; ?>

<?php include 'footer.php'; ?>

