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


header("X-XSS-Protection: 0");
  








echo "<h1>My Decks</h1>";
echo "USERID=".$USER->id;







if ( $USER->instructor ) {

     
     $sql = "SELECT * FROM {$p}slidedecks WHERE user_id = " . $USER->id;
          
          
}

// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart(false);
$OUTPUT->flashMessages();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

echo "<pre>".$_POST['deckslidesta'].'</pre>';

$options = 'options';
$id = 0;

 $savequery = "INSERT INTO {$p}eo_slidedecks
        (id, user_id, description, slides, options, updated_at)
        VALUES ( :ID, :UI, :DES, :SLI, :OPT, NOW())
        ON DUPLICATE KEY UPDATE user_id = :UI, description = :DES, slides = :SLI, options = :OPT, updated_at = NOW()";
        // $link_id = $_POST['link_id'];
    
    //echo $_POST['questions'];
    $PDOX->queryDie($savequery,
        array(
            ':UI' => $USER->id,
            ':ID' => $id,
            ':DES' => $_POST['deck_title'],
            ':SLI' => $_POST['deckslidesta'],
            ':OPT' => $options
        ), true
    );








}


$sql = "SELECT * FROM {$p}eo_slidedecks WHERE user_id = " . $USER->id;

$decks = $PDOX->allRowsDie($sql);

foreach ($decks as $deck) {

echo "Deck id = " . $deck['id'];


}









?>




<br/>
<a  href="editdeck.php">Edit Deck</a>





<?php

 







if ( ! $USER->instructor ) die();   ///Die if not instructor role (maybe move everything below to its own page





$OUTPUT->footerStart();
?>





<?php
$OUTPUT->footerEnd();
?>
