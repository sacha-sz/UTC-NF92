<?php
  // Permet de centraliser tous les identifiants et en cas  de changement de n'avoir à modifier que ce fichier
  $dbhost = 'localhost';
  $dbuser = 'XXXXXXXX';
  $dbpass = 'XXXXXXXX';
  $dbname = 'dbXXXXXXXX';
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
  mysqli_set_charset($connect, 'utf8');
?>
