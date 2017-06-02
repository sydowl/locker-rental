<?php
require_once('./config/config.php');
$loggedin =  $_SERVER['REMOTE_USER'];
$login_url ="https://weblogin.reed.edu?cosign-vm-library&https://vm.library.reed.edu/Circ/lockers/combo_locker_ll1_admin.php";
$logout_url="https://weblogin.reed.edu/cgi-bin/logout";
$next_url="http://vm.library.reed.edu/Circ/lockers/combo_locker_ll1_admin.php";
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
<form action="locker_admin_clear.php" method="post">
<input type="submit" value="Clear All" id="" style="font-size:12pt;">
<input type="hidden" name="whichdb" value="combo_lockers_ll1">
<a href="editcombo_locker_ll1_admin.php"><input type="button" value=" Edit Combinations" style="font-size:12pt" /> </a><p></p>
</form>
<table  border="1">
<tbody> 
	<tr>
          <th><h3>Locker #&nbsp; </h3></th>
	<th><h3>Combo</h3></th>
          <th><h3> Name</h3></th>
	<th><h3>Reserved Date</h3></th>
          <th><h3>Notes</h3></th>
        </tr>
<form action="locker_admin_process.php" method="post">
<input type="hidden" name="whichdb" value="combo_lockers_ll1" />
START_HTML;

$endpage = <<<END_HTML
</tbody>
</table>
<br><br>
<input type="submit" value="Save" id="save" style="font-size:12pt;">
</form>

<script type="text/javascript" src="../js/jquery-1.6.1.min.js"></script>
<script src="../js/locker_admin.js"></script>
  </body>
</html>
END_HTML;

echo $beginpage;

while($row = mysqli_fetch_assoc($query)) {
$userid = $row['user_id'];
$num = $row['locker_order'];
$combo = $row['combo'];
$name = $row['full_name'];
$note = $row['notes'];
$checked = '';
$res_date=$row['reserve_date'];
if ($res_date != '0000-00-00') {
 $res_date = date('m-d-Y', strtotime($res_date));
} else {
        $res_date = '';
}
if ($userid != 'nobody') {
 $checked = 'checked';
}
echo '<tr><td><input type="hidden" name="list['.$i.'][userid]" value="'.$userid.'"><input type="hidden" name="list['.$i.'][lockernum]" value="'.$num.'"><input type="checkbox" '. $checked.' id="'.$row['locker_order'].'" name="list['.$i.'][assigned]" value="'.$userid.'"><label for="'.$num.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$num.'</label>';
echo '</td><td>&nbsp;';
echo $combo;
echo '&nbsp;</td><td style="font-size:17px">';
echo $name;
echo '</td><td>'.$res_date;
echo '</td><td><input type="text" size="40" style="font-size:15px" name="list['.$i.'][note]" value="'.$note.'"></td></tr>';
$i++;
}
echo $endpage;
?>
