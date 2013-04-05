<?php include_once('./header.php'); ?>

<script type="text/javascript">
   function formfocus() {
      document.getElementById('concept1').focus();
   }
   window.onload = formfocus;
</script>

</head>
<body>

<!--Add institutional branding here-->

<?php

//Variables
$clean = array(); //array for filtered inputs
$old_concepts = array();

$topic = rtrim($_POST['topic']);
$topic_id = rtrim($_POST['topic_id']);

if ( isset($topic) && ($topic != "") ) {
	$topic = htmlentities( $topic, ENT_QUOTES, "UTF-8" );
	$topic = strip_tags($topic) ;
	$topic = utf8_decode($topic);
    $clean['topic'] = $topic ;

	$topic_id = add_topic($clean['topic']) ;

} elseif ( isset($topic_id) && ($topic_id != "") ) {
	if (!is_numeric($topic_id)) {
		failure($errmsg['invalidinputs'], $location);
	}

	$old_concepts = get_topic_concepts($topic_id)  ;

	if ( (isset($old_concepts)) && !(empty($old_concepts)) ) {
		$delete =  "DELETE FROM libkw_concepts WHERE topic_id='$topic_id'";
		$resDelete = mysql_query($delete) or die('Query failed: ' . mysql_error());
	}

} else {
	failure($errmsg['invalidinputs'], $location);
}

?>

<h2>How to Generate Keywords</h2>

<h3>List Your Key Concepts</h3>

<form action="keywords1.php" method="POST">
<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
<p>Your research topic is:</p>
<blockquote><?php echo $clean['topic']; ?></blockquote>
<p>Now, identify 2-4 key concepts within your research topic. Each key concept might be 1-2 words.</p>
<div class="toggle_area">
    <div class="toggle_label"><a href="#">See Examples</a></div>

    <div class="toggle_content">
        <div class="example">For example, let's say your research topic was "Effects of media on women's body image". Your key concepts would be:
        <ul><li>media</li>
        <li>women</li>
        <li>body image</li>
        </ul>
        </div>
    </div>
</div>



<p><label for="concept1" class="hidden">Key Concept 1</label><input id="concept1" type="text" name="concept[]" class="dataentry"></p>
<p><label for="concept2" class="hidden">Key Concept 2</label><input id="concept2" type="text" name="concept[]" class="dataentry"></p>
<p><label for="concept3" class="hidden">Key Concept 3</label><input id="concept3" type="text" name="concept[]" class="dataentry"></p>
<p><label for="concept4" class="hidden">Key Concept 4</label><input id="concept4" type="text" name="concept[]" class="dataentry"></p>

<input type="submit" value="Next" class="submitbutton">

</form>

<p>&nbsp;</p>

</body>
</html>

