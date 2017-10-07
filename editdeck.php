<?php
require_once "..//../../tsugi/config.php";
//require_once "../libs_modal.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

//include("../../groups_class.php");
//$grp = new Groups();

//include("../../acl_class.php");
//$acl = new Acl();



if ( ! $USER->instructor ) die("Requires instructor role");
// Handle your POST data here...

//handle saving of this special qtype (SHOLD PROBBALY CONVERT editquesiton.php which posts to myquestions








            
            
            
            
if (isset($_GET['slidedeck_id'])) {
        //Must be editing slide
        $slidedeck_id =  intval($_GET['slidedeck_id']);
	    //Retrieve a question 
	    $row = $PDOX->rowDie("SELECT * FROM {$p}eo_slidedecks
	        WHERE id = " . $slidedeck_id
	    );


        /*
        //get csv of users groups
        $group_id_csv = $grp->user_groups($USER->id);
        ///Check to see if allowed to edit
        if ($row['user_id'] != $USER->id) {
            //must not be question owner better check if group
            $question_gid = $grp->get_groupid_from_catid ($row['category_id']);
            if (!$group_id_csv){
            //user not in any group
                //echo "USER SHOULD NOT BE EDITING1";
                $_SESSION['error'] = "Sorry you don't have permissions to edit this question";
		        header('Location: '.addSession('../../index.php'));
		        return;
            
            } else {
                $gidarray = explode(',',$group_id_csv);
                if (!in_array($question_gid, $gidarray)) {
                    //echo "USER SHOULD NOT BE EDITING2";
                    $_SESSION['error'] = "Sorry you don't have permissions to edit this question";
		            header('Location: '.addSession('../../index.php'));
		            return;
                }
            }
        }
        
        */
        
        
        
        
       //$question_options = json_decode($row['question_options']);
       //var_dump($question_options);
       //$slide_bgcolor = $slide_options->slide_bgcolor;
        
        

        
        /*
         //check to see if question is locked by another user if not lock it
        $locked_by = $grp->lock_question($USER->id, $qid);
        if($locked_by == '') {
        //echo "o ahead and edi its not locked";
        
        } else {
        
                    $_SESSION['error'] = "Sorry this question is currently being editied by another user";
		            header('Location: '.addSession('../../myquestions.php'));
		            return;
        
        };
        */
        
        //$tags = $row['tags'];
        
                ///setup html for select category_id


        /*
        //$group_id_csv = $grp->user_groups($USER->id);
        if ($group_id_csv) {
            $getcatidsql = "SELECT category_id, parent_id, category_name FROM eo_categories WHERE user_id = ".$USER->id .  " OR group_id IN (".$group_id_csv.")";
        } else {
            $getcatidsql = "SELECT category_id, parent_id, category_name FROM eo_categories WHERE user_id = ".$USER->id ;
        }
        
        //$getcatidsql = "SELECT category_id, category_name FROM {$p}eo_categories WHERE user_id =" . $USER->id;
        $rowcats = $PDOX->allRowsDie($getcatidsql);

        $selhtml = '<div class="form-group">
                    <label>Question Category&nbsp;&nbsp;</label><select class="custom-select" name="category_id">';
            foreach ($rowcats as $cat) {
            
                if ($row['category_id'] == $cat['category_id']) {
                    $checked = "selected";
                } else {
                    $checked = "";
                }
                $selhtml .= "<option value='".$cat['category_id']."' ".$checked.">".$cat['category_name']."</option>";
            }
        $selhtml .= '</select></div>';
        */


	//$oldguess = $row ? $row['guess'] : '';
	//update fields with current question data.
	            if ($row){
	            
	            $slide_options = json_decode($row['options']);
	            $slides =  $row['slides'];
	            
	            //get first section /slide
	            
	            $first = strstr($slides, '<section>');
	            $second = strstr($first, '</section>');
	            $firstslide = str_replace($second, "", $first);
	            
	            
	            $firstslide =  str_replace("<section>","", $firstslide);
	            $firstslide =  str_replace("</section>","", $firstslide);
	            //$firstslide =  str_replace($firstslide,"</section>","");
	            //$firstslide = ltrim($firstslide, '<section>');
	            //echo "<pre>".$firstslide."</pre>";
	            //$firstslide = rtrim($firstslide, '</section>');
	            //echo $final;
	            
	            
	            
	            
	            $decktitle = $row['description'];
	
	            //$question_type =  $row['question_type'];
                    //$cid = $row['category_id'];
                    //$title = "Editing Slide";
                    //$qid = $row['question_text'];
                    $uid = $row['user_id'];
	            //$share =  $row['share'];
                        if ($row['share'] == 1){
                            $share = "checked";
                        } else {
                            $share = "";
                        }
                        
                       
                        
                    //$feedbackpos = $row['positive_feedback'];
                    //$feedbackneg = $row['negative_feedback'];
                        
                        
                        
	            }

       





} else {
    //Must be new slide

        $firstslide = '
<div class="mceTmpl">
<h1 style="text-align: center;"><span style="font-size: 36pt;">TITLE</span></h1>
<ul>
<li style="text-align: left;"><span style="font-size: 18pt;">Point 1</span></li>
<li style="text-align: left;"><span style="font-size: 18pt;">Point 2</span></li>
<li style="text-align: left;"><span style="font-size: 18pt;">Point 3</span></li>
</ul>
</div>';
        $decktitle = '';
        //$question_type = 'slide';
        $slides = '';
        //$feedback = '';
        $slidedeck_id = '';
        //$difficulty = '0';
        //$taxonomy = '0';
        $tags = '';
        $share = '';
        //$question_options = new stdClass();
        //$question_options->slide_bgcolor = '#000000';
        $slide_bgcolor = '#ffffff';
        //$question_options->ignore_case = 1;
        //$ignore_case = "checked";
        //$question_options->choose_template = '0';
        
        
        /*
        //Take care of requests from grouppage
        if (isset($_GET['gid'])) {
            $gid = intval($_GET['gid']);
        } else {
            $gid = '';
        }
        */
        
        
        /*

        //get current users home category
        $getcatidsql = "SELECT category_id FROM {$p}eo_categories WHERE user_id = '".$USER->id."'";
        $rowcatid = $PDOX->rowDie($getcatidsql);
        //$cid = $rowcatid['category_id'];

        $uid = $USER->id;
        $title = "Create Slide";
        
        $text_response = '';
		
		///setup html for select category_id
        $group_id_csv = $grp->user_groups($USER->id);

        if ($group_id_csv) {
            $getcatidsql = "SELECT category_id, parent_id, category_name, group_id FROM eo_categories WHERE user_id = ".$USER->id .  " OR group_id IN (".$group_id_csv.")";
        } else {
            $getcatidsql = "SELECT category_id, parent_id, category_name, group_id FROM eo_categories WHERE user_id = ".$USER->id ;
        }
        		
        //$getcatidsql = "SELECT category_id, category_name FROM {$p}eo_categories WHERE user_id =" . $USER->id;
        $rowcats = $PDOX->allRowsDie($getcatidsql);

        $selhtml = '<div class="form-group">
                    <label>Question Category&nbsp;&nbsp;</label><select class="custom-select" name="category_id">';
            foreach ($rowcats as $cat) {
            
                if ($gid == $cat['group_id']) {
                    $checked = "selected";
                } else {
                    $checked = "";
                }
                
                $selhtml .= "<option value='".$cat['category_id']."' ".$checked.">".$cat['category_name']."</option>";
            }
        $selhtml .= '</select></div>';

*/

}


// Start of the output
$OUTPUT->header();
$OUTPUT->bodyStart(false);

//$share='';
$tags = '';
//echo("<h3>Edit Slide Deck</h3>");
//$OUTPUT->welcomeUserCourse();






?>





<div class="container-fluid">
    <form id="questionEditForm" action="mydecks.php" method="post" >
    <input type="hidden" name="question_id" value="<?= $qid ?>" readonly>
    <input type="hidden" name="user_id" value="<?= $uid ?>" readonly>
    <input type="hidden" id ="ajaxkeepaliveurl" name="ajaxkeepaliveurl" value="<?php addSession('../ajax/keepalive.php')?>"  readonly>


    
    <!--  question options fields -->
    <div class="panel panel-default">
<!--        <div class="panel-heading">
        Question Settings
        </div> -->
        
        <!-- responsive filemanger integration -->
        <div id="subfolder" data-subfolder="<?=$_SESSION['RF']['subfolder']?>" ></div>
        <div id="ajaxurl" data-ajaxurl="<?php echo addSession('saveimage.php')?>"></div>
        
        
        <div class = "panel-body">
            <div class="row">
                 <div class="col-md-2">
                     <div class="form-group">
                     <span class = "context-menu-one">HERE</span>
                     <label>Edit Slide Deck</label>
                         <button class="editquesave btn btn-success" onclick="saveGenerate();" type="submit" name="save" value="save">Save</button>
                         <a href='index.php' class="btn btn-warning" name="cancel"  formnovalidate>Cancel</a>
                         
                         <br/><br/>
                         <input  type="hidden"  name="slidedeck_id" value="<?=$slidedeck_id ?>">
                         <label>Options</label>
                         <div id="themeselect">Theme:
                            <select name="themename" id="themename">
                            <option value="default">default</option>
                            <option value="neon">neon</option>
                            <option value="beige">beige</option>
                            </select>
                            </div>
                            <br/>
                            <div id="transelect">Transition:
                            <select name="transition" id="transition">
                            <option value="default">default</option>
                            <option value="cube">cube</option>
                            <option value="page">page</option>
                            <option value="concave">concave</option>
                            <option value="linear">linear</option>
                            </select>
                            </div>
                         
                              <label>Slides</label>
                            <div style="display: inline;" id="slides_edit" oncontextmenu="return false;"></div>
                         
                     
                     </div>
                 </div>
            <!--</div> 
        
                <div class="row"> -->
                   <div class="col-md-10">    
                        <div class="form-group">
                        <label for="deck_title">Deck Title</label>
                        <input placeholder="Provide a title for this slide deck" type="text" class="form-control" name="deck_title" id="deck_title" value="<?= $decktitle ?>">
                        </div>
                        
                        
                            
                        
                        
                        
                        
                        
                        <!-- Question specific options  -->





                        
                        
                        <div class="form-group">
                            <label for="question_text">Slide Content</label><div id="slidenum">Editing Slide: <span id="slidenumber">1</span></div>
                           <!-- <a href="#" class="btn btn-info" id="cp1">Slide Color</a> -->
                            <input  type='hidden' id='slide_bgcolor' name = 'slide_bgcolor' value = "<?= $slide_bgcolor ?>"> <?php //if ($question_options->orientation_important == '0') echo "selected";?>
                            <Textarea style="height: 460px;" class="eofeedback"  name="question_text" id="question_text"><?= $firstslide ?></textarea>
                        </div>
                        
                        <button type="button" id="savenext" onclick="saveNext();">Save and Next Slide</button>
                        
                        <button type="button" id="savegen" onclick="saveGenerate();">Save and Generate</button>
                        
                        
                        <div class="form-group">
                            <label title="Would you like to share this question with other instructors?" class="form-check-label">
                            Share&nbsp;&nbsp;
                            <input  type="checkbox" class="form-check-input" name="share" value="true" <?=$share ?>>
                            </label>
                        </div>
                        
                        
                     
                       

                       <div class="form-group">
                             <label for="tags">Tags</label>
                             <textarea id="tags" placeholder="Comma separated list of tags" name = "tags" class="form-control"><?=$tags?></textarea>
                       </div>
                             
                        
                             
                             
                   </div> <!-- close md12 -->
                       
               </div> <!-- close row -->
           
     </div> <!-- panel-body -->
    </div><!-- panel-default -->


<!-- <div id="deckslides">   </div> -->
<textarea name="deckslidesta" id="deckslidesta"><?= $slides ?></textarea>








<link href="js/jquery-ui.min.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="styles.css" type="text/css"> -->
<link rel="stylesheet" href="css/mce-reveal-styles.css" type="text/css">
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/editdeck.js"></script>

<!--  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<!-- <script type="text/javascript" src="../../js/functions.js"></script> -->
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>


<script type="text/javascript">

$(document).ready(function () {

    

	tinymce.init({
      menubar: true,
      statusbar: true,
      width: '800',
      //height: '720',
      max_height: '600',
      max_width: '800',
      resize: false,
	  selector: '.eofeedback',
	  browser_spellcheck : true,
	  relative_urls: false,
	  visualblocks_default_state: true,
	  content_css: 'css/mce-reveal-styles.css', 
	  resize: true,
	  plugins: [
	    'advlist autolink autoresize lists link image charmap print preview anchor',
	    'searchreplace visualblocks code fullscreen noneditable',
	    'insertdatetime media table textcolor contextmenu paste code responsivefilemanager ooclink ooccdimage emoticons image media template formula hr colorpicker'
	  ],
	  toolbar: 'insertfile undo redo | styleselect | fontselect fontsizeselect bold italic forecolor backcolor mybutton | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | charmap | emoticons hr | responsivefilemanager | ooclink ooccdimage | code image media | template formula',
	   font_formats: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n',
	   setup: function (editor) {
            editor.on('keyup', function (e) {
                //console.debug('Editor contents was modified. Contents: ' + editor.getContent({
                    //format: 'text'
                //}));
                $('#questionEditForm').bootstrapValidator('revalidateField', 'question_text');
            });
            
             editor.on('init', function() {
                tinymce.activeEditor.getBody().style.backgroundColor = $('#slide_bgcolor').val();
            });
            
            
            editor.addButton('mybutton', {
                text: 'Slide Color',
                icon: false,
                onclick: function () {
                
                editor.windowManager.open({
                
				title: 'Slide Background color',
				body: [
        
          {
            type   : 'colorpicker',
            name   : 'colorpicker'
            //label  : 'colorbutton',
            //textcolor_rows: "4",
          },
          
         
        ],
        
        
        	onsubmit: function(e){
        	
        	console.log(e.data.colorpicker);
        	tinymce.activeEditor.getBody().style.backgroundColor = e.data.colorpicker;
        	$('#slide_bgcolor').val(e.data.colorpicker);
			console.dir(e);
		}
        
        
        
			});
                
                
                
                
                
                
                
                
                
                
                
                
                    //tinymce.get("tinymce-background-colorpicker").settings.color_picker_callback.call(null, function(color){    		console.log(color);		}, "#ffffff");
                    //editor.getBody().style.backgroundColor = '#E5FFCC';
                }
            });
           
            
            
            
        },
        

        
        image_advtab: true ,
        
        table_default_styles: {
             width: '100%'
            },

        templates: [
        { title: 'basic', url: 'mcetemplates/basic.html' },
        { title: 'Bulleted', url: 'mcetemplates/basic-bulleted.html' },
        { title: 'Bulleted-Big', url: 'mcetemplates/basic-bulleted-big.html' },
        { title: 'Figure', url: 'mcetemplates/figure.html' },
        { title: '2-Column', url: 'mcetemplates/2-col.html' },
        { title: '3-Column', url: 'mcetemplates/3-col.html' }],
        //templates: "templates/test.html",
        
        image_caption: true,
        media_live_embeds: true,
        imagetools_cors_hosts: ['tinymce.com', 'codepen.io'],
        //valid_elements:'script[language|type|src], div[style], span[id|class]',        
        extended_valid_elements:'script[language|type|src], div[style], span[id|class]',
        external_filemanager_path:"/tsugi/mod/openochem/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        external_plugins: { "filemanager" : "/tsugi/mod/openochem/filemanager/plugin.min.js"}
       
	  
	});
	    
	    /*
        $('#cp1').colorpicker().on('changeColor', function(e) {
            //$('body')[0].style.backgroundColor = e.color.toString('rgba');
            tinymce.activeEditor.getBody().style.backgroundColor = e.color.toString('rgba');
                
            $('#slide_bgcolor').val(e.color.toString('rgba'));
                
        });
        */

    $('#questionEditForm')
        .bootstrapValidator({
        excluded: [':disabled'],
        submitButtons: 'button[type="submit"]:not([formnovalidate])',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            question_text: {
                validators: {
                  callback: {
                        message: 'Question Text must be between 5 and 600 characters long',
                        callback: function (value, validator, $field) {
                            // Get the plain text without HTML
                            //console.log('gere we go');
                            //var text = tinyMCE.activeEditor.getContent({
                            //    format: 'text'
                            //});
                            var text = tinyMCE.get('question_text').getContent({
                                format: 'text'
                            });
                            //console.log(text.length);
                            //console.log(text);
                            return text.length <= 600 && text.length >= 5;
                        }
                    }
                }
            },
            
                       question_title: {
                validators: {
                  callback: {
                        message: 'Question title must be between 5 and 60 characters long',
                        callback: function (value, validator, $field) {
                            text = $('#question_title').val();
                            return text.length <= 60 && text.length >= 5;
                        }
                    }
                }
            }
            
            
            
            
            
        }
    })
        .on('success.form.bv', function (e) {
    });
    
    
 
    
});


</script>



<!-- menu for slide sorting -->
 <div id='cntnr'>
    <ul id='items'>
      <li>+ Before</li>
      <li>+ After</li>  
    </ul>
    <hr />
    <ul id='items'>
      <li>Move up</li>
      <li>Move down</li>  
    </ul>
    <hr />
    <ul id='items'>
      <li>Delete</li>
    </ul>
  </div>







</div>
</form>
</body>
</html>



<?php


if (isset($_GET['question_id'])) {
    echo '<script type="text/javascript" src="js/keepalive.js"></script>';        
}

$OUTPUT->footerStart();




?>
<!--
  <link rel="stylesheet" href="https://swisnl.github.io/jQuery-contextMenu/css/screen.css" type="text/css"/>
    <link rel="stylesheet" href="https://swisnl.github.io/jQuery-contextMenu/css/theme.css" type="text/css"/>
    <link rel="stylesheet" href="https://swisnl.github.io/jQuery-contextMenu/css/theme-fixes.css" type="text/css"/> -->


<script type="text/javascript" src="js/bootstrapValidator.min.js"></script>

<!--
<script type="text/javascript" src="js/jquery.contextMenu.js"></script>
<script type="text/javascript" src="js/jquery.ui.position.js"></script> -->

<!-- <script language="javascript" type="text/javascript" src="../../js/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"/></script>
<link rel="stylesheet" href="../../js/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" type="text/css"> -->

<link rel="stylesheet" href="js/bootstrapValidator.min.css" type="text/css">
<?php
$OUTPUT->footerEnd();

function is_checked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }



