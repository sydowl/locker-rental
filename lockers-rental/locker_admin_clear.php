<?php
require_once('./config/config.php');
$whichdb = $_POST['whichdb'];
$go = $_SERVER['HTTP_REFERER'];

if ($dbconn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dbconn->connect_errno . ") " . $dbconn->connect_error;
}

//Keep the combinations, and the notes if the username is nokey 
//because that indicates the notes describe a problem with the locker

if (!$dbconn->query("UPDATE ".$whichdb."  SET `user_id` = 'nobody', `payment` = '', `full_name` = '', `reserve_date` = '', `notes` = '' WHERE user_id != 'nokey'")) {
  printf("Error: %s\n", $dbconn->error);
}

header("Location: ".$go."");
echo 'All clear!';
sleep(5);
?>
