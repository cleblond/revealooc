<?php
require_once "config.php";
//require_once "libs_modal.php";




use \Tsugi\UI\Table;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;


// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

//include("following_class.php");
//$fol = new Following();



  //create users file subfolder
          
          //clean up name of subfolder
          $subfolder = str_replace(" ", "_", $displayname);
          $subfolder = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $subfolder);
          $subfolder = mb_ereg_replace("([\.]{2,})", '', $subfolder);
          
          //echo $subfolder;
          
          if (!file_exists('uploads/' . $subfolder)) {
             mkdir('uploads/' . $subfolder, 0755, true);
          }
          
          //assign to session for responsive filemanager
          $_SESSION["RF"]["subfolder"] = $subfolder;










echo "USERID=".$USER->id;







if ( $USER->instructor ) {

     
     $sql = "SELECT * FROM {$p}slidedecks WHERE user_id = " . $USER->id;
          
          
}

// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart(false);
$OUTPUT->flashMessages();

?>


<a  target="_blank" href="showdeck.php">Show Deck</a>


<br/>
<a  href="editdeck.php">Edit Deck</a>

<br/>
<a  href="mydecks.php">My Decks</a>



<?php

 







if ( ! $USER->instructor ) die();   ///Die if not instructor role (maybe move everything below to its own page





$OUTPUT->footerStart();
?>





<?php
$OUTPUT->footerEnd();
?>
