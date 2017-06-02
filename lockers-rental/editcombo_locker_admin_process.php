<?php
require_once('./config/config.php');
$whichdb = $_POST['whichdb'];
$i = 1;

if ($dbconn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dbconn->connect_errno . ") " . $dbconn->connect_error;
}
foreach ($_POST['list'] as $key => $value) {
//foreach ($TEST['list'] as $key => $value) {
$lockernum = $_POST['list'][$i]['lockernum'];
$combo = $_POST['list'][$i]["combo"];
if (!$dbconn->query("UPDATE ".$whichdb." SET combo = '".$combo."'  WHERE locker_order = ".$lockernum."")) {
    echo "Query Failed!: (" . $dbconn->errno . ") ". $dbconn->error;
}
$i++;
}

//Checks which page was just updated for the proper back link

if ($whichdb == "combo_lockers_ll1") {
$beginpage = <<<START_HTML
<!DOCTYPE html>
<html>
  <head>
<link rel="stylesheet" type="text/css" href="../css/lockers.css">
    <meta content="text/html; charset=windows-1252" http-equiv="content-type">
    <title></title>
  </head>
  <body>
 <p>The lockers database has been updated. <a href="http://vm.library.reed.edu/circ/lockers/combo_locker_ll1_admin.php">Return to the admin page.</a> or <a href="http://vm.library.reed.edu/circ/lockers/editcombo_locker_ll1_admin.php">return to editing combinations.</a></p>
</body>
</html>
START_HTML;
} elseif ($whichdb == "combo_lockers_l2") {
$beginpage = <<<START_HTML
<!DOCTYPE html>
<html>
  <head>
<link rel="stylesheet" type="text/css" href="http://vm.library.reed.edu/css/lockers.css">
    <meta content="text/html; charset=windows-1252" http-equiv="content-type">
    <title></title>
  </head>
  <body>
 <p>The lockers database has been updated. <a href="http://vm.library.reed.edu/circ/lockers/combo_locker_l2_admin.php">Return to the admin page.</a> or <a href="http://vm.library.reed.edu/circ/lockers/editcombo_locker_l2_admin.php">return to editing combinations.</a></p>
</body>
</html>
START_HTML;
}

echo $beginpage;
?>
