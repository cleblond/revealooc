<?php

require_once "../../../config.php";

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

//var_dump($request);

//$cat_id = $_POST['params'];
//echo $cat_id;

$qrow = $PDOX->rowDie("SELECT * FROM {$p}eo_questions WHERE question_id=$qid");


//generate html for this question

if ($qrow['question_type'] == 'structure' || $qrow['question_type'] == 'mechanism') {

$data['question_html'] = '<canvas id="initializeMeLater" width="150" height="150" style="border:1px solid black;">This is a message that shows if the browser doesn\'t support HTML5 canvas, which all modern browsers should support now.</canvas>';

$data = $data + $qrow;

}





$json_response = json_encode($data);

// # Return the response
echo $json_response;
?>
