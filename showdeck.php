<?php
require_once "config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;


if (isset($_GET['slidedeck_id'])) {

$id = intval($_GET['slidedeck_id']);
} else {

die;
}


$sql = "SELECT * FROM {$p}eo_slidedecks WHERE id = $id";
$sliderow = $PDOX->rowDie($sql);

$slides = json_decode($sliderow['slides']);

$sections = '';
$section = ''; 
    foreach ($slides  as $slide) {            
        $slide = str_replace("text\/javascript","application\/json", $slide);
        $section .= "<section>" . $slide . "</section>";
                    
                
        //$slidestext = $slidetext . ;
        $sections .= $section;
        $section = '';
    }



$options = json_decode($sliderow['options']);
$OUTPUT->header();
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<title><? echo $sliderow['description']; ?></title>

		<link rel="stylesheet" href="css/reveal.css">
		<link rel="stylesheet" href="css/theme/<?php echo $options->theme ?>.css">
	    <link rel="stylesheet" href="css/custom.css">

		<!-- Theme used for syntax highlighting of code -->
		<link rel="stylesheet" href="lib/css/zenburn.css">

		<!-- Printing and PDF exports -->
		<script>
			var link = document.createElement( 'link' );
			link.rel = 'stylesheet';
			link.type = 'text/css';
			link.href = window.location.search.match( /print-pdf/gi ) ? 'css/print/pdf.css' : 'css/print/paper.css';
			document.getElementsByTagName( 'head' )[0].appendChild( link );
		</script>
	</head>
	<body>
		<div class="reveal">
			<div class="slides">
<?= $sections ?>
			</div>
		</div>

		<script src="lib/js/head.min.js"></script>
		<script src="js/reveal.js"></script>

		<script>
			// More info about config & dependencies:
			// - https://github.com/hakimel/reveal.js#configuration
			// - https://github.com/hakimel/reveal.js#dependencies
			Reveal.initialize({
			    transition: "<?php echo $options->transition ?>",
			    embedded: true,
				dependencies: [
					{ src: 'plugin/markdown/marked.js' },
					{ src: 'plugin/markdown/markdown.js' },
					{ src: 'plugin/notes/notes.js', async: true },
					{ src: 'plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } }
				]
			});
		</script>
	</body>
</html>


<?php




$OUTPUT->footerStart();

$OUTPUT->footerEnd();
