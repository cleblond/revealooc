<?php
require_once "config.php";

use \Tsugi\UI\Table;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;


// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

//create users file subfolder  
//clean up name of subfolder for responsive filemanager
$subfolder = str_replace(" ", "_", $displayname);
$subfolder = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $subfolder);
$subfolder = mb_ereg_replace("([\.]{2,})", '', $subfolder);
          
          //echo $subfolder;
          
if (!file_exists('uploads/' . $subfolder)) {
             mkdir('uploads/' . $subfolder, 0755, true);
}
          
          //assign to session for responsive filemanager
$_SESSION["RF"]["subfolder"] = $subfolder;

if (isset($_GET['action'])) {

       $slidedeck_id = intval($_GET['slidedeck_id']);
    if ($_GET['action'] == 'assign') {

        //only one assignement permitted per link better check to see if one exisists
        
        //$assignedsql = "SELECT activity_id from {$p}eo_activities WHERE link_id = :LI and assigned = 1";
        $assignedsql = "SELECT slidedeck_id from {$p}eo_revealassigned WHERE link_id = :LI and user_id = :UID";


        $assigned = $PDOX->allRowsDie($assignedsql,
        array(
            ':LI' => $LINK->id,
            ':UID' => $USER->id,
            ), false
        );
        
        if (!$assigned) {

            //$updatesql = "UPDATE {$p}eo_activities SET assigned = 1, link_id = :LI WHERE activity_id =:AID and user_id = :UI";
            $updatesql = "INSERT INTO {$p}eo_revealassigned SET link_id = :LI, user_id = :UID, slidedeck_id = :AID";
                        //$updatesql = "UPDATE {$p}eo_activities SET assigned = 1, link_id = :LI WHERE activity_id =:AID and user_id = :UI";

            $test = $PDOX->queryDie($updatesql,
            array(
                ':UID' => $USER->id,
                ':LI' => $LINK->id,
                ':AID' => $slidedeck_id
                ), false
            );
        } else {
        
        $_SESSION['error'] = "You can only have one assigned activity per link!  Either create another link in your LMS or unassign the existing activity!";
        header('Location: '.addSession('index.php'));
        return;
        }

        //should we copy questions too?
        //$activity_id = $PDOX->lastInsertId();

    }
    
    
        if ($_GET['action'] == 'unassign') {

//  We used to copy, but to confusing since it created many copies, now we just changethe assigned bit and we can also delete all attempts etc.
/*        $copysql = "INSERT INTO {$p}eo_activities ( activity_title, Link_id, user_id, share, max_tries, limit_secs, show_correct, builds_on_last, grading, mode, questions, complete_by_flag, complete_by, created_at, updated_at, assigned, closed ) SELECT activity_title, :LI, :UI, '0', max_tries, limit_secs, show_correct, builds_on_last, grading, mode, questions, complete_by_flag , NOW(), NOW(), NOW(), '1', closed FROM {$p}eo_activities WHERE activity_id = :AID and user_id = :UI";
*/
        //$updatesql = "UPDATE {$p}eo_activities SET assigned = 0, link_id = :LI WHERE activity_id =:AID and user_id = :UI";
        $updatesql = "DELETE FROM {$p}eo_revealassigned WHERE slidedeck_id =:AID and link_id = :LI and user_id = :UI";

        $test = $PDOX->queryDie($updatesql,
        array(
            ':UI' => $USER->id,
            ':LI' => $LINK->id,
            ':AID' => $slidedeck_id
            ), false
        );

        //should we copy questions too?
        //$activity_id = $PDOX->lastInsertId();

    }
    
    
} 


$assignedrow = $PDOX->rowDie("SELECT * FROM {$p}eo_revealassigned WHERE link_id=".$LINK->id);

if($assignedrow) {
$slidedeck_id = $assignedrow['slidedeck_id'];
$row = $PDOX->rowDie("SELECT * FROM {$p}eo_slidedecks WHERE id=".$slidedeck_id);

}




if ( !$USER->instructor ) {

    if (!$assignedrow) {
        //header('Location: '.addSession('showdeck.php?slidedeck_id=0'));
    } else { 
        header('Location: '.addSession('showdeck.php?slidedeck_id='.$assignedrow['slidedeck_id']));
    }
}

// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart(false);
$OUTPUT->flashMessages();

echo "<legend>Assigned SlideDeck</legend>";

echo '<div class="container-fluid"><a href="mydecks.php" class="btn btn-info" >My SlideDecks</a></div><br/>';

        echo('<div class="well">');

if(!$assignedrow) {
        echo ('<p>There is no SlideDeck assigned for this link!  Click on <a href="mydecks.php">"My Decks"</a> to create and assign SlideDecks!</p>');
        

        echo('</div></div>');
} else {

        echo '<h4>' . $row['description'] . '</h4>
        <p><a href="index.php?action=unassign&slidedeck_id='.$assignedrow['slidedeck_id'].'" title="Unassign"><i class="fa fa-check-square-o fa-3x" aria-hidden="true"></i></a>&nbsp;&nbsp;
        <a href="editdeck.php?slidedeck_id='.$assignedrow['slidedeck_id']. 
        '" title="Edit"><i class="fa fa-cog fa-3x" aria-hidden="true"></i></a>&nbsp;&nbsp;
        <a href="showdeck.php?slidedeck_id='.$assignedrow['slidedeck_id']. 
        '" title="Preview"><i class="fa fa-search fa-3x" aria-hidden="true"></i></a></p>
        </div></div>';

}
/////////////////////////

$OUTPUT->footerStart();
$OUTPUT->footerEnd();
?>
