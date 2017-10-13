<?php

require_once "../../../config.php";
require_once "../../openochem/libs.php";

use \Tsugi\UI\Table;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
//$LTI = LTIX::getConnection();
$p = $CFG->dbprefix;


///since angular sends json get the raw output instead
$postdata = file_get_contents("php://input");


//$request = json_decode($postdata);

//$catid = $request->catid;
//$myonly = intval($request->myonly);
//$user_id = intval($request->userid);
//echo $catid;
$qid = intval($_POST['qid']);
$qtype = $_POST['qtype'];
$usrresponse = $_POST['usrresponse'];

//var_dump($request);

//$cat_id = $_POST['params'];
//echo $cat_id;
//echo $usrresponse;

$correctness = compare_response_with_answers($qid, $usrresponse, $qtype);

//var_dump($correctness);

$json_response = json_encode($correctness);

// # Return the response
echo $json_response;
?>
