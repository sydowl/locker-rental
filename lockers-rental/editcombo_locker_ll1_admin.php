<?php

//List the locker#s, and their combinations in a form text field so they can be updated
 
require_once('./config/config.php');
$loggedin =  $_SERVER['REMOTE_USER'];
$login_url ="https://weblogin.reed.edu?cosign-vm-library&https://vm.library.reed.edu/Circ/lockers/editcombo_locker_ll1_admin.php";
$logout_url="https://weblogin.reed.edu/cgi-bin/logout";
$next_url="http://vm.library.reed.edu/Circ/lockers/editcombo_locker_ll1_admin.php";
if ($loggedin == "" || $loggedin == " ")
{
header("Location: $login_url");
}

//User must be authenticated by Cosign and be on the VIP list

$authorized = array("holmesj","sydowl","mcdaniel","willingt","vanbuskb","bkelley","alwinee");
if(!in_array($loggedin, $authorized)) {
$cookie=$_SERVER[ 'COSIGN_SERVICE' ];
setcookie( $cookie, "null", time()-1, '/', "", 1 );
header("Location: $login_url");
}

$locker_row = 0;
$locker_col = 0;
$left_pos = 0;
$new_row = false;
$i = 1;

if ($dbconn->errno) {
 echo $dbconn->connect_error;
}
if (!$query = $dbconn->query("SELECT * FROM combo_lockers_ll1 ORDER BY 'locker_order' DESC")) {
	echo $query->error;
}
$dbconn->close();

$beginpage = <<<START_HTML
<!DOCTYPE html>
<html>
  <head>
<link rel="stylesheet" type="text/css" href="../css/lockers.css">
    <meta content="text/html; charset=windows-1252" http-equiv="content-type">
    <title></title>
  </head>
  <body>
<table  border="1">
<tbody> 
	<tr>
          <th><h3>Locker #&nbsp; </h3></th>
	<th><h3>Combo</h3></th>
          <th><h3>&nbsp;Name&nbsp;</h3></th>
          <th><h3>&nbsp;Notes&nbsp;</h3></th>
        </tr>
<form action="editcombo_locker_admin_process.php" method="post">
<input type="hidden" name="whichdb" value="combo_lockers_ll1">
START_HTML;

$endpage = <<<END_HTML
</tbody>
</table>
<br><br>
<input type="submit" value="Save" id="admin_submit" style="font-size:14pt;">
</form>

<script type="text/javascript" src="../../js/jquery-1.6.1.min.js"></script>
<script src="../../js/locker_admin.js"></script>
  </body>
</html>
END_HTML;

echo $beginpage;

while($row = mysqli_fetch_assoc($query)) {
$userid = $row['user_id'];
$name = $row['full_name'];
$num = $row['locker_order'];
$combo = $row['combo'];
$note = $row['notes'];
$checked = '';
//if (mysql_result($sched, $i, "user_id") != 'nobody') {
// $checked = 'checked';
//}
echo '<tr><td><input type="hidden" name="list['.$i.'][lockernum]" value="'.$num.'"><input type="hidden" name="list['.$i.'][userid]" value="'.$userid.'"><input type="checkbox" '. $checked.' id="'.$row['locker_order'].'" name="list['.$i.'][assigned]" value=".$userid."><label for="'.$num.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$num.'</label>';
echo '</td><td><input  type="text" size="8" style="font-size:18px" name="list['.$i.'][combo]" value="' .$combo.'"></td>';
echo '<td style="font-size:17px">';
echo $name;
echo '</td><td>'.$note.'</td></tr>';
$i++;
}
echo $endpage;
?>
