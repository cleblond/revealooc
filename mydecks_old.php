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


//header("X-XSS-Protection: 0");
  








echo "<h1>My Decks</h1>";
echo "USERID=".$USER->id;







if ( $USER->instructor ) {

     
     $sql = "SELECT * FROM {$p}slidedecks WHERE user_id = " . $USER->id;
          
          
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


$sql = "SELECT id, description, share, updated_at FROM {$p}eo_slidedecks WHERE user_id = " . $USER->id . " ORDER by updated_at DESC";

$decks = $PDOX->allRowsDie($sql);


// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart(false);
$OUTPUT->flashMessages();







//Table::pagedTable($decks, $searchfields, false, "request-detail");
echo '<a  href="editdeck.php">Create New Deck</a>';
echo "<table class='table'>";
    echo "<tr><th>Description</th><th>Last Update</th><th>Shared</th><th>Actions</th></tr>";
foreach ($decks as $deck) {
     $share = ($deck['share'] == 0 ? 'No' : 'Yes');
    
    echo "<tr><td>".$deck['description']. "</td><td>" . $deck['updated_at'] . "</td><td>" . $share . "</td><td><a href='editdeck.php?slidedeck_id=" . $deck['id'] . "'>Edit</a><a target='_blank' href='showdeck.php?slidedeck_id=" . $deck['id'] . "'>  View</a></td></tr>";

}
 echo "</table>";








?>




<br/>






<?php

 







if ( ! $USER->instructor ) die();   ///Die if not instructor role (maybe move everything below to its own page





$OUTPUT->footerStart();
?>





<?php
$OUTPUT->footerEnd();
?>
