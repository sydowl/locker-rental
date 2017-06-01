<?php
require_once('./config/config.php');
$left_pos = 10;
$locker_row = 60;
$i = 240;

$beginpage = <<<START_HTML
<!DOCTYPE html>
<html>
  <head>
<link rel="stylesheet" type="text/css" href="http://vm.library.reed.edu/css/lockers.css">
    <meta content="text/html; charset=windows-1252" http-equiv="content-type">
    <title>Red Lockers LL1</title>
  </head>
  <body>
<h3>Lower Level Combination Lockers</h3>
    <ul>
START_HTML;

$endpage = <<<END_HTML
    </ul>
    <p></p>
    <script type="text/javascript" src="../../js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="../../js/lockers.js" async></script>

<form style="top: 400px; left:10px; position: relative;"  accept-charset="UTF-8" class="form_style" style="top:830px; left: 175px;" action="#" onsubmit ="AssignLocker();">
<input type="hidden" id="assignscript" value=" combo_locker_ll1_assign.php" />
<p id="sidenote"></p>
User ID: <input  type="text" id="user"><br><br>
<input type="button" id="assignlocker" class="button" value="Assign Locker" />
&nbsp; Refund $
<input id="refund" type="text" size="4" > <input type="button" id="returnlocker" class="" value="Return Locker" />
<input type="hidden" id="returnscript" value="combo_locker_ll1_return.php" />
<p></p><p>
<a href="combo_locker_ll1_admin.php" target="_blank">Locker administration page</a>
<br>
</form>
  </body>
</html>
END_HTML;

if ($dbconn->errno) {
  echo $dbconn->connect_error;
}

if(!$query=$dbconn->query("SELECT * FROM combo_lockers_ll1 ORDER BY 'locker_order' DESC")) {
  echo $query->error;
}

$dbconn->close();

echo $beginpage;

//Draw the lockers. Unassigned lockers are a color close to the physical color
//Assigned lockers are gray and x-ed out

while($row = mysqli_fetch_assoc($query)) {
$userid = $row['user_id'];
$num = $row['locker_order'];

if ($userid == 'nobody') {
echo '<li id="'. $num  . '" style="background:
url(\'../../images/locker_red.png\'); left:'. $left_pos .'px; top:'. $locker_row .'px;  height: 88px; width: 60px;">&nbsp;&nbsp;&nbsp;&nbsp;'.
$num.'</li>';
}  else {
$used_id = $num + 500;
echo '<li id="'. $used_id . '" style="background:
url(\'../../images/locker_taken.png\'); left:'. $left_pos .'px; top:'. $locker_row .'px;  height: 88px; width: 60px; color: gray;">&nbsp;&nbsp;&nbsp;&nbsp;'.
$num. '</li>';
}

//Draw the lockers according to their real layout using some math to determine start of new columne

$i++;
$locker_row = $locker_row + 89;
if($num % 4 == 0) {
  $left_pos = $left_pos + 60;
 if($num == 224) { $left_pos = $left_pos + 15; }
  $locker_row = 60;
}
}


echo $endpage;
?>
