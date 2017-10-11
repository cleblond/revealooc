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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//echo "<pre>".$_POST['deckslidesta'].'</pre>';

$options = 'options';
$id = '';


            $options = new StdClass;
            //$question_options->choose_template = $_POST['choose_template'];
            $options->theme = $_POST['themename'];
            $options->transition = $_POST['transition'];



                if (isset($_POST['share'])) {
                $share = 1;
                } else {
                $share = 0;
                }



         if ($_POST['slidedeck_id'] == '') {  //
         
         

         
         
                 $savequery = "INSERT INTO {$p}eo_slidedecks
                (user_id, description, slides, share, options, updated_at)
                VALUES ( :UI, :DES, :SLI, :SHA, :OPT, NOW())";
                // $link_id = $_POST['link_id'];
            
            //echo $_POST['questions'];
            $PDOX->queryDie($savequery,
                array(
                    ':UI' => $USER->id,
                  //  ':ID' => $id,
                    ':DES' => $_POST['deck_title'],
                    ':SLI' => $_POST['deckslidesta'],
                    ':SHA' => $share,
                    ':OPT' => JSON_encode($options)
                ), true
            );

         
         
         
         
         } else {
         
                $savequery = "INSERT INTO {$p}eo_slidedecks
                (id, user_id, description, slides, share, options, updated_at)
                VALUES ( :ID, :UI, :DES, :SLI, :SHA, :OPT, NOW())
                ON DUPLICATE KEY UPDATE user_id = :UI, description = :DES, slides = :SLI, share = :SHA, options = :OPT, updated_at = NOW()";
            $id =  $_POST['slidedeck_id'];
            
            //echo $_POST['questions'];
            $PDOX->queryDie($savequery,
                array(
                    ':UI' => $USER->id,
                    ':ID' => $id,
                    ':DES' => $_POST['deck_title'],
                    ':SLI' => $_POST['deckslidesta'],
                    ':SHA' => $share,
                    ':OPT' => JSON_encode($options)
                ), true
            );
         
         
         
         }  // endif question_id

 







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


