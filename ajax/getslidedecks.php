<?php

require_once "../../../config.php";

use \Tsugi\UI\Table;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;

// Retrieve the launch data if present
//$LTI = LTIX::requireData();
$LTI = LTIX::getConnection();
$p = $CFG->dbprefix;


///since angular sends json get the raw output instead
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//$catid = $request->catid;
$myonly = intval($request->myonly);
$user_id = intval($request->userid);
//echo $catid;
//var_dump($request);

//$cat_id = $_POST['params'];
//echo $cat_id;

//$rows = $PDOX->allRowsDie("SELECT * FROM {$p}eo_activities WHERE share=1");

//echo "my only=".$myonly;
if ($myonly == '1') {
//$query="select question_id, question_text, tags, question_type, created_at, updated_at from {$p}eo_questions where user_id = $user_id AND category_id = $catid";
$query = "SELECT * FROM {$p}eo_slidedecks WHERE user_id = $user_id";
} elseif ($myonly == '0') {



//$query = "SELECT {$p}eo_activities.user_id, activity_id, activity_title, mode, created_at, updated_at, questions, share FROM {$p}eo_activities INNER JOIN {$p}eo_permissions on {$p}eo_activities.user_id = {$p}eo_permissions.user_id WHERE ((permission & 2) > 0)";

//$query="select question_id, question_text, tags, question_type, created_at, updated_at from {$p}eo_questions where (share = 1 or user_id = $user_id) and category_id = $catid";

$query = "SELECT * FROM {$p}eo_activities WHERE share = 1";

//$query = "SELECT user_id, activity_id, activity_title, mode, created_at, updated_at, questions FROM {$p}eo_activities WHERE share=1";
//$query="select question_id, question_text, question_type, created_at, updated_at from {$p}eo_questions where (share=1 and category_id = $catid) or user_id = $user_id";
//$query="select question_id, question_text, tags, question_type, created_at, updated_at from {$p}eo_questions where (share = 1 or user_id = $user_id) and category_id = $catid";
} elseif ($myonly == 2) {


//$query = "SELECT user_id, activity_id, activity_title, mode, created_at, updated_at, questions, share FROM {$p}eo_activities WHERE share=1 and user_id = $user_id";

$query = "SELECT {$p}eo_activities.user_id, activity_id, activity_title, mode, created_at, updated_at, questions, share FROM {$p}eo_activities INNER JOIN {$p}eo_permissions on {$p}eo_activities.user_id = {$p}eo_permissions.user_id WHERE ((permission & 2) > 0) AND eo_permissions.user_id = $user_id";

}

//echo $query;
//echo $query;
//$query="select question_id, question_text, question_type, category_id from {$p}eo_questions where share=1";




$result = $PDOX->allRowsDie($query);

//$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

$arr = array();

$num_rows = count($result);

if($num_rows > 0) {

	//while($row = $result->fetch_assoc()) {
        foreach($result as $row){
        //$result = substr_count( $_SESSION['sarray'], ",") +1;
        //$addcount['sd_count'] = substr_count( $row['questions'], ",") +1;
		$arr[] = $row;	
	}

}
# JSON-encode the response
$json_response = json_encode($arr);

// # Return the response
echo $json_response;
?>
