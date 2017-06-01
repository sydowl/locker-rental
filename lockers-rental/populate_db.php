<?php

require_once('./config/config.php');

if ($dbconn->errno) {
echo "Failed to connect to MySQL". $dbconn->connect_error;
}

for ($i = 1; $i < 82; $i++) {

if (!$dbconn->query("INSERT INTO combo_lockers_l2(user_id, locker_order) VALUES ('nobody', ".$i.")")) {
echo $dbconn->error;
}
}

?>

