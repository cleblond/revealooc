<?php
require_once "config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;


$sesspieces = explode('=', addSession(""));
$phpsessid = $sesspieces[1];


include("groups_class.php");
$grp = new Groups();


// Handle your POST data here...
if (isset($_GET['activity_id']) and $_GET['activity_id'] != '') {
    //Must be editing activity
        $activity_id=  intval($_GET['activity_id']);
	//Retrieve a activity
	$row = $PDOX->rowDie("SELECT * FROM {$p}eo_activities
	    WHERE activity_id = " . $activity_id
	);

        ///Check to see if allowed to edit
        if ($row['user_id'] != $USER->id) {
                    $_SESSION['error'] = "Sorry you don't have permissions to edit this activity.";
		    header('Location: '.addSession('index.php'));
		    return;
        }

	//$oldguess = $row ? $row['guess'] : '';
	//update fields with current activity data.
	if ($row){
        $activity_id = $row['activity_id'];
	$activity_info =  $row['activity_info'];
    $activity_title =  $row['activity_title'];
	$max_tries =  $row['max_tries'];
	$grading = $row['grading'];
	$user_id =  $row['user_id'];
	$link_id =  $row['link_id'];
	$allow_rating = $row['allow_rating'];
	//$live = $row['live'];
	
	if ($row['limit_secs'] !== '-1') {
	$limit_secs = $row['limit_secs']/60;
	} else {
	$limit_secs = $row['limit_secs'];
	}

            if ($row['show_correct'] == 1){
                $show_correct = "checked";
            } else {
                $show_correct = "";
            }

            if ($row['builds_on_last'] == 1){
                $builds_on_last = 1;
            } else {
                $builds_on_last = 0;
            }


            if ($row['share'] == 1){
                $share = "checked";
            } else {
                $share = "";
            }
            
            
            if ($row['allow_rating'] == 1){
                $allow_rating = "checked";
            } else {
                $allow_rating = "";
            }


            if ($row['complete_by_flag'] == 1){
                $complete_by_flag = "checked";
                $complete_input = '';
            } else {
                $complete_by_flag = "";
                $complete_input = 'disabled';
            }



	$complete_by =  $row['complete_by'];
	$mode =  $row['mode'];
        $questions = $row['questions'];
        $q_points = $row['points'];
        $point = explode(',',$row['points']);
        $title = "Edit Activity";
	}

 
 //prepare questions input html initially loaded activity

        //check to see if all proposed questions are still in DB if not update db

        if ($questions != '') { 
        
		    $questionsprev = $questions;
		    $question = explode(',',$questions);
		    //var_dump($question);
		    $i = 0;

		    foreach ($question as $q) {
		       //echo  $q[0];
		        //check for random category question and ignore for now...maybe do quick search to make sure questiopns exist.
		        if ($q[0] != 'r') {
			        $query =  "SELECT 1 from {$p}eo_questions where question_id = " . $q;
			        $row = $PDOX->rowDie($query);
			        if ($row == false) {
			            unset($question[$i]);
			        }
			    } else {  //must be rand question - check to see if atleast the category exists and has question
			       $randqsetting = process_rand_cat_string($q);

			        $query =  "SELECT 1 from {$p}eo_questions where category_id = " . $randqsetting['cid'];
			        //$query = $randqsetting['sql'];
			        
			        $row = $PDOX->rowDie($query);
			        if ($row == false) {
			            unset($question[$i]);
			        }
			       
			
			
			    }    
		            $i++;   
		    }


		
		
		
		
		
		
		    $questions=implode(',', $question);

		    //update activities db with correct
		        if ($questionsprev != $questions) {
		            $updateactivity = "UPDATE {$p}eo_activities SET questions = '". $questions."' WHERE activity_id = ".$activity_id;
			    $PDOX->queryDie($updateactivity); 
                            $_SESSION['error'] = "This activity had questions that were not in the database!  These question references were removed from this activity.";
		        }  
		            //echo "here";
		    //echo "HERE";
		    
		    
		    //$query = "SELECT * FROM {$p}eo_questions WHERE question_id IN (".$questions.") ORDER BY FIELD (question_id,".$questions.")";

		    //$rows = $PDOX->allRowsDie($query);
		            //var_dump($rows);
		    $initial = '
		    <div class="container-fluid">
		    <h3>Questions in this Activity</h3>
		    <span>Construct your activity by dragging "Question Bank" questions onto table below.</span>
		    <span>Drag and drop to reorder questions.</span>
		    <a href="#" id="add_random_question">Add Random Question</a>
		    <table id="tab1" class="table table-hover table-condensed table-responsive table-bordered">
		     
		    <thead><th>#</th><th style="display:none;" >Question ID</th><th>Question Title</th><th>Question Type</th><th>Points</th></thead>
		    <tbody class="connectedSortable">';
		    $i = 1;
		    
		  
		    
		    foreach ($question as $qid) {
		    //foreach ( $rows as $row ) {
		    
		        if ($qid[0] == 'r') {   //must be random question
		        
                        $selected = '';		        
		                $randqsetting = process_rand_cat_string($qid);
		                
		                //var_dump($randqsetting);
		                
		                //generate html for category select

                        
                        $group_id_csv = $grp->user_groups($USER->id);
                        if ($group_id_csv != "") {
                            $sql = "SELECT category_id, parent_id, category_name FROM eo_categories WHERE user_id = ".$USER->id .  " OR group_id IN (".$group_id_csv.")";
                        } else {
                            $sql = "SELECT category_id, parent_id, category_name FROM eo_categories WHERE user_id = ".$USER->id ;
                        }
                        
                        
                        //$cats = $PDOX->allRowsDie("SELECT category_id, category_name FROM eo_categories");
                        echo $sql;
                        $cats = $PDOX->allRowsDie($sql);
                        $catselect = "<select id='rand_category' name='rand_category'>";
                        $catselect .= "<option selected disabled>Choose Category</option>";
                        
                        
                        
	                    foreach ($cats as $cat) {
                                //echo $cat['category_id'];
                                //echo $parent_id; 
                           if ($cat['category_id'] == $randqsetting['cid']) {$selected = 'selected';}
	                        $catselect .= "<option value='" . $cat['category_id'] . "' $selected>" . $cat['category_name'] . "</option>";
                            $selected = '';
	                    }
	                    $catselect .=  "</select>";
	                    
	                    //echo $catselect;	                    
	                    
	                    $filters = array(
                                     "none"=>"No Restrictions",
                                     "de1"=>"Difficulty = 1",
                                     "de2"=>"Difficulty = 2",
                                     "de3"=>"Difficulty = 3",
                                     "de4"=>"Difficulty = 4", 
                                     "de5"=>"Difficulty = 5",                           
                                     "dge2"=>"Difficulty >= 2",
                                     "dge3"=>"Difficulty >= 3",
                                     "dge4"=>"Difficulty >= 4",
                                     "dle2"=>"Difficulty <= 2 ",
                                     "dle3"=>"Difficulty <= 3 ",
                                     "dle4"=>"Difficulty <= 4",
                                     "t1"=>"Taxonomy = 1",
                                     "t2"=>"Taxonomy = 2",
                                     "t3"=>"Taxonomy = 3",
                                     "t4"=>"Taxonomy = 4",     
                                     "t5"=>"Taxonomy = 5");
                                     
                        $filselect = "<select id='rand_filter' name='rand_category'>";
                        $filselect .= "<option selected disabled>Choose Filter</option>";
                        $selected = '';    
                        foreach ($filters as $key => $value) {
                            //echo "key=$key";
                            //echo $randqsetting['filter'];
                            if ($key == $randqsetting['filter'] . $randqsetting['filterlimit']) {$selected = 'selected';}
                            
	                        $filselect .= "<option value='" . $key . "' $selected>" . $value . "</option>";
                            $selected = '';
                        }
                        $filselect .=  "</select>";
                        
                        //echo $filselect;
	                    
	                    
	                    $initial .= '<tr class="question_row"><td class="qnum">'.$i.'</td><td style="display:none;" class="question_id">'.$qid.'</td><td>Random Question</td><td>'.$catselect.$filselect.'</td><td>NA</td><td class="points"><input class="points" type="number" size="3" min="0" max="100"  value="'.$point[$i-1].'"></td><td></td><td><a class="remove_question" href="#" title="Remove"><i class="fa fa-times" aria-hidden="true"></i></a></td></tr>';
		        
		        
		        
		        
		        
		        
		        } else {
		            $query = "SELECT * FROM {$p}eo_questions WHERE question_id = $qid";
		            $row = $PDOX->rowDie($query);
		            $initial .= "<tr class='question_row'><td class='qnum'>".$i."</td><td style='display:none;' class='question_id'>";
		            $initial .= $row['question_id'];
		            $initial .= "</td><td>";
		            $initial .= $row['question_title'];
		            $initial .= "</td><td>";
		            $initial .= $row['question_type'];
		            $initial .= "</td>";
	                   // $initial .= $row['created_at'];
	                   // $initial .= "</td><td>";
		            //$initial .= $row['updated_at'];
		            
		            $initial .= '<td class="points"><input class="points" type="number" size="3" min="0" max="100"  value="'.$point[$i-1].'"></td>';
		            
		            
		            $initial .= "<td>";
		            $initial .= '<a href="previewquestion.php?question_id='.$row['question_id'].'" title="Preview Question" target="_blank"><i class="fa fa-search" aria-hidden="true"></a>';
		            $initial .= "</td><td>";
		
		            if ($row['question_type']=='mcq') {
		            $edit = 'question_type/mcq/editquestion.php';
		            } elseif ($row['question_type']=='newman') {
		            $edit = 'question_type/newman/editquestion.php';
		            } else {
		            $edit = 'question_type/chemdoodle/editquestion.php';
		            }
		            
		            
		            $initial .= '<a href="'.$edit.'?question_id='.$row['question_id'].'" title="Edit Question" target="_blank"><i class="fa fa-cog" aria-hidden="true"></i></a>';
		            $initial .= "</td><td>";
		            $initial .= '<a class="remove_question" href="#" title="Remove"><i class="fa fa-times" aria-hidden="true"></i></a>';
		            $initial .= "</td></tr>";
		        }
		        
		        $i++;
		    }
		    
		    
		    
		    
		    $initial .= '</tbody><tfoot><tr><td colspan="4">Drop here</td></tr></tfoot>';
		    
	        $initial .= "</table></div>";
        } else {
		$initial = '
		<div class="container-fluid">
		<h3>Questions in this Activity</h3>
		<span>Construct your activity by dragging "Question Bank" questions onto table below.</span>
		<span>Drag and drop to reorder questions.</span>
		<a href="#" id="add_random_question">Add Random Question</a>
			<table id="tab1" class="table table-hover table-condensed table-responsive table-bordered">
			<thead><th>#</th><th style="display:none;" >Question Id</th><th>Question Title</th><th>Question Type</th><th>Points</th></thead>
			<tbody class="connectedSortable"> 
			
			
			</tbody>
			<tfoot><tr><td colspan="4">Drop here</td></tr></tfoot>
			
			</table>
		</div>';
        }

       //echo $initial;



} else {
    //Must be new activity
        $activity_id = '';
        $activity_info =  '';
        $activity_title =  '';
	$max_tries =  '';
	$user_id =  $USER->id;
	$link_id =  $LINK->id;
	$limit_secs =  -1;
	$mode =  'freenav';
	//$live = 0;
	$grading = 0;   // i.e. average the attempt
        $questions = '';
        $title = "Create Activity";
        $complete_by_flag = '';
        $show_correct = '';
        //$builds_on_last = '';
        $builds_on_last = 1;
        $share = '';
        $complete_by = date("Y-m-d H:i:s");
        $complete_input = '';
        
        $allow_rating = '';

        $initial = '
        <div class="container-fluid">
        <h3>Questions in this Activity</h3>
		<span>Construct your activity by dragging "Question Bank" questions onto table below.</span>
		<span>Drag and drop to reorder questions.</span>
		<a href="#" id="add_random_question">Add Random Question</a>
		<table id="tab1" class="table table-hover table-condensed table-responsive table-bordered">
		 
		<thead><th>#</th><th style="display:none;" >Question Id</th><th>Question Title</th><th>Question Type</th><th>Points</th></thead>
		<tbody class="connectedSortable">
		</tbody>
		<tfoot><tr><td colspan="4">Drop here</td></tr></tfoot>
		</table>
        </div>
        ';
        


}


// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart();


//echo('<span style="float: right; margin-bottom: 10px;">');
//    echo('<a href="myactivities.php" class="btn btn-info">Cancel</a> ');
    /*
if ( $USER->instructor ) {
    //echo('<a href="questionsnew.php"><button class="btn btn-info">Test</button></a> '."\n");
    //echo('<a href="editactivity.php?activity_id='.$activity_id.'"><button class="btn btn-info">Reload (DEV)</button></a> '."\n");
    echo('<a href="myquestions.php"><button class="btn btn-primary">My Questions</button></a> '."\n");
    echo('<a href="myactivities.php"><button class="btn btn-primary">My Activities</button></a> '."\n");
}
    echo('<a href="index.php"><button class="btn btn-primary active">Dashboard</button></a> '."\n");
//$OUTPUT->exitButton();
*/
//echo('</span>');



$OUTPUT->flashMessages();
echo("<h1>".$title."</h1>");
//$OUTPUT->welcomeUserCourse();

?>

<link rel="stylesheet" href="css/flatpickr.min.css">
<script src="js/flatpickr.js"></script>
<!-- <script type="text/javascript" src="jquery-3.1.1.min.js"></script>  -->

<form id="activity-form" action="myactivities.php" method="post" >

<div class="pull-right"><input type="submit" class="save_activity btn btn-success" name="save" value="Save">
<a href="myactivities.php" class="btn btn-danger">Cancel</a>
</div>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#settings">Settings</a></li>
  <li><a data-toggle="tab" href="#builder">Questions</a></li>
</ul>

<div class="tab-content">
<div id="settings" class="tab-pane fade in active">
<div class="row">
<div class="col-sm-6">


<div class="form-group">
<legend>Activity Title</legend>
<small>Provide a title for this activity that students will see.</small>
<textarea class="input-small form-control" rows="1" cols="72" name="activity_title" id="activity_title" required><?= $activity_title ?></textarea>
</div>

<div class="form-group">
<legend>Activity Info</legend>
<small>Provide a description of this activity that students will see on the activity info page.</small>
<Textarea class="form-control" cols="72" name="activity_info" id="activity_info"><?= $activity_info ?></textarea>
</div>


<div class="form-group">
<legend>Sharing</legend>
<input class="form-check-input" type="checkbox" name="share" value="true" <?=$share ?>>&nbsp;<label>Share with others</label><br/>
<small>Would you like this activity to be available in the "Activity Bank" for other intructors to copy and use?</small><br/>
</div>




<input type="hidden" name="activity_id" value="<?= $activity_id ?>" readonly>
<input type="hidden" name="link_id" value="<?= $link_id ?>" readonly>

<div class="form-group">
<legend>Complete By</legend>
<small>Would you like to set a complete by date for this activity?</small><br/>
<input class="form-check-input" type="checkbox" id="complete_by_flag" name="complete_by_flag" value="true" <?=$complete_by_flag ?>>
<input  type="text" class="flatpickr" id="complete_by" name="complete_by" value="<?= $complete_by ?>"  <?= $complete_input?>>
</div>

<div class="form-group">
<legend>Show Correct Answers</legend>
<input class="form-check-input" type="checkbox" id="show_correct" name="show_correct" value="true" <?=$show_correct ?>>
<label>&nbsp;Show Correct Answer</label>
</div>



<div class="form-group">
<legend>Difficulty Rating</legend>
<p><small>Would you like students to be able to rate the difficulty of the questions?</small></p>
<input class="form-check-input" type="checkbox" name="allow_rating" value="true" <?=$allow_rating ?>>&nbsp;<label>Allow rating</label>
</div>





<!-- <input type="submit" name="savecopy" value="Save Copy (NOT IMPLEMENTED YET)"> -->

</div>
<div class="col-sm-6">

 <fieldset class="form-group">
    <legend>Navigation Mode</legend>
    <p>How do you want students to navigate the activity?</p>

        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" id="mode-freenav" name="mode" value="freenav" <?php  if ($mode == "freenav") echo "checked"; ?>>
                Free Navigation
            </label><br/>
           <p><small>In each attempt students can freely navigate through out the questions in any order.  All questions are available at once.</small></p>
        </div>
        
        
        
        <div class="form-check">
            <label class="form-check-label">
                <input title="One question at a time in order" class="form-check-input" id="mode-sequential" type="radio" name="mode" value="sequential" <?php  if ($mode == "sequential") echo "checked"; ?>>             
            Sequential
            </label><br/>
            <p><small>Must start at first question (or last question from unfinished attempt) and continue to next question until complete.</small></p>
        </div>
        
        
         <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" id="mode-live" name="mode" value="live" <?php  if ($mode == "live") echo "checked"; ?>>
                Live Realtime (Instructor Navigation)
            </label>
           <p><small>The instructor (You!) control which questions are Displayed/Attempted in realtime.</small></p>
        </div>
        
        
        <div class='pull-left'>
            <input class="form-check-input" type="checkbox" id="enable_limit_secs" name="enable_limit_secs" <?php if($limit_secs != -1) echo "checked"?>>
            <label>&nbsp;&nbsp;Time limit / Attempt (Min) </label>
        </div>





        
        
<div class="row">
<div class="col-xs-3">
<input  class="form-control" type="number" id="limit_secs" name="limit_secs" value="<?= $limit_secs ?>">

</div>
</div> <!-- close row --> 
        <small>For Free Navigation this is the time limit in minutes until the activity will auto submit.  In Sequential mode this is the time limit for each individual question.</small>
        
        
        
</fieldset>

<div class="form-group">
    <legend>Multiple Attempt Settings</legend>
            <input class="form-check-input" type="checkbox" id="builds_on_last" name="builds_on_last" value="true" <?php if($builds_on_last == 1)  echo "checked"; ?>>
    <label>&nbsp;Builds On Last Attempt</label><p><small>Every attempt builds on the last.  Students see there previous responses and can build on them.  Only one attempt in database.</small></p>

    <input class="form-check-input" type="checkbox" id="enable_max_tries" name="enable_max_tries" value="true" <?php if($builds_on_last !== 1)  echo "checked"; ?>><label>&nbsp;&nbsp;Allow Multiple Attempts</label>
    <p><small>Indicate the maximum number of attempts allowed.  A '0' indicates unlimited attempts.  Each attempt is recorded separately, so you must specify how to grade the attempts.</small></p>
    <div class="container-fluid">
    <div class="col-md-2">
    <input size="8" class="form-control" type="number" id="max_tries" name="max_tries" value="<?= $max_tries ?>">
    <input type="hidden" id="qcatajaxurl" name="qcatajaxurl" value="<?php echo(addSession('ajax/qcategories.php'))?>">
    </div>
    <div class="col-md-11">

    </div>
    </div>
</div><br/>
<div class="form-group">
    <label>Grading&nbsp;&nbsp;</label>
      <input class="form-check-input" type="radio" id="grade_average" name="grading" value="0" <?php  if ($grading == 0) echo "checked"; ?>> Average
      <input class="form-check-input" type="radio" id="grade_highest" name="grading" value="1" <?php  if ($grading == 1) echo "checked"; ?>> Highest<br/>
</div>

<!-- <input type="text" name="show_correct" value="<?= $show_correct ?>" ><br/> -->



<!--
<div class='pull-left'>
<input class="form-check-input" type="checkbox" id="enable_limit_secs" name="enable_limit_secs" value="true" <?php if($limit_secs != -1) echo "checked"?>>
<label>&nbsp;&nbsp;Time limit / Attempt (Min) </label>
</div>
<div class="col-xs-3">
<input  class="form-control" type="number" id="limit_secs" name="limit_secs" value="<?= $limit_secs ?>">
</div>
-->





<!--Questions; -->
<input class="required" type="hidden" id="questions" name="questions" value="<?= $questions ?>" ><br/>
<input class="required" type="hidden" id="q_points" name="q_points" value="<?= $q_points ?>" ><br/>


</form>
</div>
</div>
</div>


<?php 

echo '<div id="builder" class="tab-pane fade">';
echo '<div class="row">';
echo '<div class="col-sm-6">';
echo $initial;
echo '</div>';
//$catid = '1';


    $catsql = "SELECT category_id FROM {$p}eo_categories WHERE user_id = '".$USER->id."'";     
    $catrow = $PDOX->rowDie($catsql);
    $catid = $catrow['category_id'];

//echo "CATTIIDDD".$catid;


$myonly = '0';
//echo '<div class="container-fluid">';
echo  '<div class="col-sm-6">';
//echo '<a href="." class="test_me">link</a>';
require_once "qbangular_activity_edit.php";
echo  '</div>';
echo '</div>';  ///row
echo '</div>';  ///builder
echo '</div>';  ///tab-content




?>

<html>
<head>



<?php

$OUTPUT->footerStart();
?>
<script>
flatpickr(".flatpickr",{
    enableTime: true
});


/*
$(document).ready(function(){
if ($("#enable_max_tries").is(":checked")) {
        $("#max_tries").prop('disabled', false);
        $('#grade_average').prop('disabled', false);
        $('#grade_highest').prop('disabled', false);
    }
    else {
        $("#max_tries").prop('disabled', true);
        //$("#max_tries").val('0');
        $('#grade_average').prop('disabled', true);
        $('#grade_highest').prop('disabled', true);
        //$('#pizza_kind').prop('disabled', 'disabled');
    }
});

*/



//remove question from table in editacctivity.php
$("div").on("click", '.remove_question', function(){
      //console.log(this);
      $(this).parent("td").parent("tr:first").remove();
});

  var update_limit_secs = function () {
    if ($("#enable_limit_secs").is(":checked")) {
        $("#limit_secs").prop('disabled', false);
    }
    else {
        //console.log("HERE");
        $("#limit_secs").prop('disabled', true);
        $("#limit_secs").val(-1);
        //$('#pizza_kind').prop('disabled', 'disabled');
    }
  };
  //$(update_limit_secs);
  $("#enable_limit_secs").change(update_limit_secs);
  
  
  
  

   var update_live = function () {
   
     //console.log('HERE WE ARE');
    if ($("#mode-live").is(":checked")) {
        $('#builds_on_last').prop('disabled', false);
        $("#max_tries").prop('disabled', false);
        $("#enable_max_tries").prop('disabled', false);
        $('#grade_average').prop('disabled', false);
        $('#grade_highest').prop('disabled', false);
        $("#limit_secs").prop('disabled', true);
        $("#enable_limit_secs").prop('disabled', true);
        $("#mode-freenav").prop('disabled', false);
        $("#mode-sequential").prop('disabled', false);


        
        //$('#enable_limit_secs').prop('disabled', false);
    }
    else {

        $('#builds_on_last').prop('disabled', false);
        $("#max_tries").prop('disabled', false);
        $("#enable_max_tries").prop('disabled', false);
        $('#grade_average').prop('disabled', false);
        $('#grade_highest').prop('disabled', false);
        $("#limit_secs").prop('disabled', false);
        $("#enable_limit_secs").prop('disabled', false);
        $("#mode-freenav").prop('disabled', false);
        $("#mode-sequential").prop('disabled', false);
    }
    
    
  };
  
  $("#live").change(update_live);
  
  
  

  var update_max_tries = function () {
    if ($("#enable_max_tries").is(":checked")) {
        $('#builds_on_last').prop('checked', false);
        $("#max_tries").prop('disabled', false);
         $("#max_tries").val('1');
        $('#grade_average').prop('disabled', false);
        $('#grade_highest').prop('disabled', false);
        //$('#enable_limit_secs').prop('disabled', false);
    }
    else {
        $("#max_tries").prop('disabled', true);
        $("#max_tries").val('0');
        $('#grade_average').prop('disabled', true);
        $('#grade_highest').prop('disabled', true);
        //$('#pizza_kind').prop('disabled', 'disabled');
    }
  };
  //$(update_max_tries);
  $("#enable_max_tries").change(update_max_tries);



  var update_builds_on_last = function () {
    if ($("#builds_on_last").is(":checked")) {
        $("#max_tries").val('-1');
        $("#max_tries").prop('disabled', true);
        $('#enable_max_tries').prop('checked', false);
        //$('#enable_limit_secs').prop('checked', false);
        //$('#enable_limit_secs').prop('disabled', true);
        //$('#enable_max_tries').prop('disabled', true);
        $('#grade_average').prop('disabled', true);
        $('#grade_highest').prop('disabled', true);
        //$('#show_correct').prop('checked', false);
        //$('#show_correct').prop('disabled', true);
        //$('#limit_secs').prop('disabled', true);
        //$("#max_tries").val('-1');
        //$("#limit_secs").val('-1');
    }
    else {
        //$("#max_tries").prop('disabled', false);
        $('#enable_limit_secs').prop('disabled', false);
        $('#enable_max_tries').prop('checked', true);
        $('#enable_max_tries').prop('disabled', false);
        //$('#show_correct').prop('disabled', false);
    }
  };
  //$(update_builds_on_last);
  $("#builds_on_last").change(update_builds_on_last);


$("#activity-form").submit(function(){
            //alert('here');
            if($("input.required").val() == '') {
                alert("You must add at least one question to this activity!");
                //$(".required").addClass('highlight');
                // $('input[type=submit]', this).attr('disabled', 'disabled');
                return false;
            }
});


  $(update_live);



</script>
<?php
$OUTPUT->footerEnd();


