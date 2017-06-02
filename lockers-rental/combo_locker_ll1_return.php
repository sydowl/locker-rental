<?php
require_once('./config/config.php');
$userID = $_POST['userID'];
$postrefund = $_POST['refund'];
$reserved_date =  date("Y-m-d", time());
$lockerID = $_POST['lockerID'];
$quotedlockerID = $lockerID - 500;
$lockerName = $_POST['numVal'];

//Create the note that will be posted to the user's record.
//The locker info and amount refunded

$newnote = array(
        "note_type" => array(
                                "value" => "OTHER",
                                "desc" => "Other"
                                ),
        "note_text" => "Returned locker LL1 # ".$lockerName." Refunded: $".$postrefund,
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
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('apikey') . '=' . urlencode('l7xx6992563f68b346768fe8dd50f7b7b3aa');
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
echo 'Locker # '.$lockerName.' has been unassigned from '. $fullname;


if ($dbconn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dbconn->connect_errno . ") " . $mysqli->connect_error;
} 

//Return locker based on userID or lockerId

if (!$_POST['lockerID']) {
 $dbconn->query("UPDATE combo_lockers_ll1 SET user_id = 'nobody', notes='Refunded $".$postrefund ." on ". $reserved_date."' WHERE user_id='".$userID."';");
} else {
$postlockerID = $_POST['lockerID'] - 500;
 $dbconn->query("UPDATE combo_lockers_ll1 SET user_id = 'nobody', notes='Refunded $".$postrefund." on ".$reserved_date."' WHERE locker_order = '".$postlockerID."';");
}
}
?>
