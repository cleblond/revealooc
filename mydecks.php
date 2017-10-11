<?php
require_once "config.php";
//require_once "libs.php";

//use \Tsugi\UI\Table;
use \Tsugi\Core\LTIX;
//use \Tsugi\UI\SettingsForm;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

//include("notify_class.php");
//$notif = new Notify();

//include("acl_class.php");
//$acl = new Acl();


$userid_toshow = $USER->id;
// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart(false);

//get outta here if not instructor
if ( ! $USER->instructor ) {
      $_SESSION['error'] = "Sorry you do not have permission to access this page.";
      header('Location: '.addSession('index.php'));
      return;
}


/*
$pagename = 'My Activities';
//$active = 'activities';        
include("nav.php");
output_new_question_modal();
*/


if (isset($_GET['action'])) {

   if ($_GET['action'] == 'delete') {
   $activity_id = intval($_GET['activity_id']);
   
   ///delete activity
   $delactsql = "DELETE from {$p}eo_activities WHERE activity_id = $activity_id and user_id =".$USER->id; 
   $deleteactivity = $PDOX->queryDie($delactsql);

     if($deleteactivity) {   
   //delete any attempts
       $delattemptssql = "DELETE from {$p}eo_activityattempts WHERE activity_id = $activity_id"; 
       $PDOX->queryDie($delattemptssql);
       
       ///delete any question attempts
       $delattemptssql = "DELETE from {$p}eo_questionattempts WHERE activity_id = $activity_id"; 
       $PDOX->queryDie($delattemptssql);
     }
   }
}


/*
///process addition to favorite  //C
if (isset($_GET['action'])) {

    ///process add to favorites
    if ($_GET['action'] == 'addfav') {
       $activity_id = intval($_GET['activity_id']);
        $savequery = "INSERT INTO {$p}eo_favorites (user_id, activity_id) VALUES ( :UI, :AID) ON DUPLICATE KEY UPDATE user_id=:UI, activity_id=:AID";
    $test = $PDOX->queryDie($savequery,
        array(
            ':UI' => $USER->id,
            ':AID' => $activity_id
        ), false
    );

    }




} 
*/



//copy to my activities
if (isset($_GET['action'])) {

    //process add to favorites
    if ($_GET['action'] == 'copy') {
        $activity_id = intval($_GET['activity_id']);



        $copysql = "INSERT INTO {$p}eo_activities ( activity_title, activity_info, link_id, user_id, share, max_tries, limit_secs, show_correct, builds_on_last, grading, mode, questions, points, complete_by_flag, complete_by, allow_rating, created_at, updated_at, assigned, closed, live_qnum, live_status) SELECT CONCAT('COPY-', activity_title), activity_info, :LI, :UI, '0', max_tries, limit_secs, show_correct, builds_on_last, grading, mode, questions, points, '0' , NOW(), allow_rating, NOW(), NOW(), assigned, closed, live_qnum, live_status FROM {$p}eo_activities WHERE (activity_id = :AID and share = 1) or (activity_id = :AID and user_id = :UI)";


        $activity_id = intval($_GET['activity_id']);

        $test = $PDOX->queryDie($copysql,
        array(
            ':UI' => $USER->id,
            ':LI' => $LINK->id,
            ':AID' => $activity_id
            ), false
        );

        $activity_id = $PDOX->lastInsertId();
        
        
        //notify followers
        //if ($share == 1) {
        //notify_followers($USER->id, $activity_id, 'newactivity');
        //}
        
        //should we copy questions too?


        }



} 






///handle post requests from editactivity.php for creating and editing activities
if ( isset($_POST['questions'])) {
    //echo $_POST['questions'];

    //process completion options
    if (isset($_POST['complete_by_flag'])) {
    $complete_by_flag = 1;
    $complete_by = $_POST['complete_by'];
    } else {
    $complete_by_flag = 0;
    $complete_by = date("Y-m-d H:i:s"); 
    }
    
    
    if (isset($_POST['live'])) {
    $live = 1;
    //$complete_by = $_POST['complete_by'];
    } else {
    $live = 0;
    //$complete_by = date("Y-m-d H:i:s"); 
    }
    
       
    if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
    } else {
    $mode = 'live';
    }
    
    
    
    
   
    if (isset($_POST['show_correct'])) {
    $show_correct = 1;
    } else {
    $show_correct = 0;
    }

    if (isset($_POST['builds_on_last'])) {
    $builds_on_last = 1;
    } else {
    $builds_on_last = 0;
    }

    if (isset($_POST['share'])) {
    $share = 1;
    } else {
    $share = 0;
    }
    
    if (isset($_POST['allow_rating'])) {
    $allow_rating = 1;
    } else {
    $allow_rating = 0;
    }

    if (isset($_POST['max_tries']) AND $_POST['max_tries'] !== '' ) {
    $max_tries = $_POST['max_tries'];
    } else {
    $max_tries = -1;
    }

    if (isset($_POST['grading'])) {
    $grading = $_POST['grading'];
    } else {
    $grading = 0;
    }
    //echo $_POST['enable_limit_secs'];
    if (isset($_POST['enable_limit_secs'])) {
    $limit_secs = $_POST['limit_secs'] * 60;
    } else {
    $limit_secs = -1;
    }

    //echo $_POST['questions'];
    
    
    
                if ($acl->has_permissions('trusted', $USER->id)) {
                    $clean_activity_info = $_POST['activity_info'];
                    $clean_activity_title = $_POST['activity_title'];
                } else {
                    require_once 'htmlpurifier_library/HTMLPurifier.auto.php';
                    $purifier = new HTMLPurifier();
                    $clean_activity_info = $purifier->purify($_POST['activity_info']);
                    $clean_activity_title = $purifier->purify($_POST['activity_title']);
                }
    
    

    if ($_POST['activity_id'] == '') {
      ///must be new activity
    $savequery = "INSERT INTO {$p}eo_activities
        (activity_id, activity_title, activity_info, link_id, user_id, share, max_tries, limit_secs, show_correct, builds_on_last, grading, mode, questions, points, complete_by_flag, complete_by, allow_rating, created_at, updated_at, live_status, live_qnum)
        VALUES ( :QID, :ATI, :QIN, :LI, :UI, :SHA, :MAX, :LIM, :SHO, :BOL, :HIG, :MDE, :QUS, :POI, :CBF, :CBY, :RAT, NOW(), NOW(), :LIV, 1 )";
        $link_id = $LINK->id;
        
        
    } else {
      //must be edit
    $savequery = "INSERT INTO {$p}eo_activities
        (activity_id, activity_title, activity_info, link_id, user_id, share, max_tries, limit_secs, show_correct, builds_on_last, grading, mode, questions, points, complete_by_flag, complete_by, allow_rating, created_at, updated_at, live_status, live_qnum)
        VALUES ( :QID, :ATI, :QIN, :LI, :UI, :SHA, :MAX, :LIM, :SHO, :BOL, :HIG, :MDE, :QUS, :POI, :CBF, :CBY, :RAT, NOW(), NOW(), 'waiting', 1)
        ON DUPLICATE KEY UPDATE activity_id=:QID, activity_title=:ATI, activity_info=:QIN, link_id=:LI, user_id=:UI, share=:SHA, max_tries=:MAX, limit_secs=:LIM, show_correct=:SHO, builds_on_last = :BOL, grading=:HIG, mode=:MDE, questions=:QUS, points=:POI, complete_by_flag=:CBF, complete_by=:CBY, allow_rating=:RAT, updated_at = NOW(), live_status = :LIV, live_qnum = 1";
         $link_id = $_POST['link_id'];
    }
    //echo $_POST['questions'];
    $PDOX->queryDie($savequery,
        array(
            ':LI' => $link_id,
            ':UI' => $USER->id,
            ':QID' => intval($_POST["activity_id"]),
            ':QIN' => $clean_activity_info,
            ':ATI' => $clean_activity_title,
            ':MAX' => $max_tries,
            ':HIG' => $grading,
            ':LIM' => $limit_secs,
            ':MDE' => $mode,
            ':QUS' => $_POST["questions"],
            ':POI' => $_POST["q_points"],
            ':RAT' => $allow_rating,
            ':CBF' => $complete_by_flag,
            ':CBY' => $complete_by,
            ':SHA' => $share,
            ':SHO' => $show_correct,
            ':BOL' => $builds_on_last,
            ':LIV' => 'waiting'
        ), true
    );
    
        $activity_id = $PDOX->lastInsertId();
    
            //notify followers
        if ($share == 1) {
        
        $notif->notify_followers($USER->id, $activity_id, 'newactivity');
        //notify_followers($USER->id, $activity_id, 'newactivity');
        }

}




$OUTPUT->flashMessages();
//echo("<h1 class=\"test\" >EasyOChem</h1>\n");
//$OUTPUT->welcomeUserCourse();


//$pagename = 'OpenOChem DashBoard';
//$active = 'dashboard';        
//include("nav.php");

if ( ! $USER->instructor ) die();   ///Die if not instructor role (maybe move everything below to its own page

echo "<legend>My SlideDecks</legend>";

echo '<div class="container-fluid"><a href="editdeck.php" class="btn btn-success" >New SlideDeck</a>  <a href="slidedecks.php" class="btn btn-info" >SlideDeck Bank</a></div>';

$myonly = 1;
require_once "mydecksangular.php";
$OUTPUT->footerStart();
?>

<!--
<script>
$(document).ready(function(){
    $('.deleteactivity').click(function(e) {
      alert('here');
      if(!confirm("Are you sure you want to delete this activity and all associated attempts?")) {
                e.preventDefault();
                return false;
      }
      return true;
    });

});

</script>  -->
<?php
$OUTPUT->footerEnd();


