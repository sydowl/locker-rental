<?php
$lockerID = $_POST["lockerID"];
$userID = $_POST["userID"];
$userID = strtolower($userID); 
$lockerName = $_POST["numVal"];
require_once('./config/config.php');

//Create a note of which locker was assigned to post to user's account
//The library system automatically posts the date

$newnote = array(
        "note_type" => array(
                                "value" => "OTHER",
                                "desc" => "Other"
                                ),
	"note_text" => "locker LL1 # ".$lockerName,
	"user_viewable" => true,
	"segment_type" => Internal
);

//Get the user's data so we can append to existing notes.

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}';
$templateParamNames = array('{user_id}');
$templateParamValues = array(urlencode($userID));
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('view') . '=' . urlencode('full') . '&' . urlencode('expand') . '=' . urlencode('none') . '&' . urlencode('apikey') . '=' . urlencode($APIkey);
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/json", "Accept: application/json"));

$response = curl_exec($ch);
curl_close($ch);

$jresponse = json_decode($response, true);
array_push ($jresponse['user_note'], $newnote);
//var_dump($jresponse);
$final = json_encode($jresponse);

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}';
$templateParamNames = array('{user_id}');
$templateParamValues = array(urlencode($userID));
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('apikey') . '=' . urlencode($APIkey);
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, $final); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
//var_dump($response);
curl_close($ch);

if (strpos($response, 'ERROR')) { 
	echo 'Error: please check the user information.';
} else {
$fullname =  $jresponse['first_name'].' '. $jresponse['last_name'];
$reserved_date =  date("Y-m-d", time());

if ($dbconn->errno) {
  echo $dbconn->connect_error;
}

//Store the locker reservation in the database

if(!$query=$dbconn->query("UPDATE `library_circulation`.`combo_lockers_ll1` SET `full_name` = '".$fullname."', `user_id` = '".$userID."', `reserve_date` = '".$reserved_date."'  WHERE `combo_lockers_ll1`.`locker_order` = '".$lockerID."'")) {
  echo $query->error;
}

if(!$combo = $dbconn->query("SELECT combo FROM combo_lockers_ll1 WHERE locker_order = '".$lockerName."' LIMIT 1")) {
  echo $combo->error;
}

while($value = $combo->fetch_object()) {

printf("%s has been assigned locker #%s. The combo is  %s", $fullname, $lockerName,  $value->combo);
}
$dbconn->close();
}
?>
