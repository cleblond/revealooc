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

$row = $PDOX->rowDie("SELECT * FROM {$p}eo_questions WHERE question_id=$qid");


//generate html for this question

        $uniqueid = uniqid();
        $data['unique_id'] = $uniqueid;
        //echo $uniqueid;
        $submit_btn = "<p><button data-qtype='".$row['question_type']."' data-qid='".$row['question_id']."' type='button' class='revealsubmit' id='bnt".$uniqueid . "' >Submit</button></p>";
        $question_text = "<p>".$row['question_text']."</p>";

        if ($row['question_type'] == 'structure') {

        

        $data['question_html'] = $question_text . '<canvas id="'.$uniqueid.'" width="150" height="150" style="border:1px solid black;">This is a message that shows if the browser doesn\'t support HTML5 canvas, which all modern browsers should support now.</canvas>';

        

        }
        
        if ($row['question_type'] == 'mechanism') {
        
        $data['question_html'] = $question_text . '<canvas id="'.$uniqueid.'" width="150" height="150" style="border:1px solid black;">This is a message that shows if the browser doesn\'t support HTML5 canvas, which all modern browsers should support now.</canvas>';
	                 $answerjsonrow = $PDOX->rowDie("SELECT answer_id, answerjson , correct FROM {$p}eo_answers
		                            WHERE question_id = " . $qid. " AND correct=1"
		                            );
		                            
		             $pattern       = '/,\{[^{]+Pusher[^}]+\}/';
                     $answerjson = preg_replace($pattern, '', $answerjsonrow['answerjson']);      
	                 $data['strippedanswerjson'] = $answerjson;
	             
	   }
	      
	   if ($row['question_type'] == 'mcq') {
	             
	             
	             $sql = "SELECT answer_id, answerother, correct FROM {$p}eo_answers WHERE question_id = :QID ORDER BY RAND()";

                 $rowsa = $PDOX->allRowsDie($sql,
		         array(
		            //':QID' => $_POST['question_id'],
		            ':QID' => $qid
		         )
	             );
	             
	               $question_html = $question_text . '<div class="funkyradio">';

                    $i=1;
             
                    foreach ($rowsa as $rowa) {
                        $checked = '';
                        //set the correct answers
                        /*
                        if ($readonly == true) {
                          if ($rowa['correct'] == 1) {
                              $checked = 'checked';
                          }
                        }*/
                        //echo "Checked = ".$checked;
                        $checked='';
                   
                        $question_html .= '<div class="funkyradio-success">
                                <input value="'.$rowa['answer_id'].'"  type="checkbox" name="responseother[]" id="responseother'.$i.'" '.$checked.'>
                                <label for="responseother'.$i.'"> '.$rowa['answerother'].'</label>
                            </div>';
                        $i++;
                        //echo $answer['answerother'];
                    } 
                    //echo "<br/>".$submitbtn . $response_answer_btns_html; 
                    //$question_html .= "</form>";
                    
	                $data['question_html'] = $question_html;
	             
	             
	             
	             
	             //$data['questions']['answers'] = $rowsa;
	    }
	    
	    if ($row['question_type'] == 'numeric'  || $row['question_type'] == 'alphanumeric') {
	             
	             
	        $data['question_html'] = $question_text . '<input type = "text" id="'.$uniqueid.'"  />';
	    }
	    
	     if ($row['question_type'] == 'formula') {
	                 //echo "here mcq";
	                 $sql = "SELECT answer_id, answerother, correct FROM {$p}eo_answers WHERE question_id = :QID ORDER BY RAND()";

                     $rowa = $PDOX->rowDie($sql,
		             array(
		                //':QID' => $_POST['question_id'],
		                ':QID' => $question_id
		             )
	                 );
	                 
	                        $formula_data = json_decode($rowa['answerother']);
                            
                            $question_options = json_decode($row['question_options']);
                            $sigfigs = $question_options->sigfigs;
                            
                            //determine the random values for vars and create the html
                            $j=0;
                            $rand_vars_html = '';
                            foreach ($formula_data->variable as $vars) {
                                $data['questions']['vars'][$j] = $vars;
                                
                                $rand_var = sigfigs(random_float($formula_data->min[$j],$formula_data->max[$j]), $sigfigs);
                                $data['questions']['rand_var'][$j] = $rand_var;
                                //$rand_vars_html .= "<input type='hidden' name = 'rand_var[".$i."]' id = 'rand_var[".$i."]' value='".$rand_var."'>";  
                                //$data['questions'][$i]['question_text']
                                $data['questions']['question_text'] = str_replace("{".$vars."}", $rand_var, $data['questions']['question_text']);
                                
                                $j++;
                            }
	                 
	                 
	     }
	     
	     $data['question_html'] .= $submit_btn;
	     


        $data = $data + $row;


$json_response = json_encode($data);

// # Return the response
echo $json_response;
?>
