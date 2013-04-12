<?php
/*
 *  Copyright 2013 University of Texas at Austin
 *
 *  Licensed under the Educational Community License, Version 2.0 (the "License"); 
 *  you may not use this file except in compliance with the License. You may obtain a 
 *  copy of the License	at:
 *
 *  http://www.osedu.org/licenses/ECL-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 *  WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 *  License for the specific language governing permissions and limitations
 *  under the License.
 *
 */
?>

<?php include_once('./header.php'); ?>

<script type="text/javascript">
   function formfocus() {
      document.getElementById('concept1keyword1').focus();
   }
   window.onload = formfocus;
</script>

</head>
<body>

<!--Add institutional branding here-->

<?php

//Variables
$concepts = array();
$old_concepts = array();
$concept_ids = array();
$tmp = array();

$topic_id = $_POST['topic_id'] ;
if (!is_numeric($topic_id)) {
	failure($errmsg['invalidinputs'], $location);
}

$old_concepts = get_topic_concepts($topic_id)  ;

if ( (isset($old_concepts)) && !(empty($old_concepts)) ) {
	$delete =  "DELETE FROM libkw_concepts WHERE topic_id='$topic_id'";
	$resDelete = mysql_query($delete) or die('Query failed: ' . mysql_error());
}

$concepts = $_POST['concept'];

$num_concepts = 0 ;
foreach ($concepts as $key => $value) {
    if ( isset($value) && ($value != "") ) {
		$value = htmlentities( $value, ENT_QUOTES, "UTF-8" );
		$value = strip_tags($value) ;
		$value = utf8_decode($value);

        $id = add_concept($value, $topic_id) ;
        array_push($concept_ids, $id);
        $num_concepts++ ;
    }
}

if ($num_concepts < 2) {
	failure($errmsg['toofewconcepts'], $location);
}

sort($concept_ids) ;

$old_keywords = get_concept_keywords($concept_id)  ;

if ( (isset($old_keywords)) && !(empty($old_keywords)) ) {
    $delete =  "DELETE FROM libkw_keywords WHERE concept_id='$concept_id[0]'";
    $resDelete = mysql_query($delete) or die('Query failed: ' . mysql_error());
}

?>

<h2>How to Generate Keywords</h2>

<h3>Related Keywords for <em>

<?php

    $current_concept_text = get_concept_from_ID($concept_ids[0]) ;
    echo $current_concept_text;

?>

</em></h3>

<form action="keywords2.php" method="POST">
<input type="hidden" name="concept_id" value="<?php echo $concept_ids[0]; ?>">
<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
<input type="hidden" name="current" value="0">

<p>Your key concepts are:</p>
<blockquote>
    <ul>

    <?php
        foreach ($concept_ids as $c) {
            $concept_text = get_concept_from_ID($c) ;
            echo "<li>$concept_text</li>" ;
        }

    ?>
    </ul>
</blockquote>
<p>Now, try to list at least 3 related keywords for each of your key concepts. These might be synonyms, broader terms, more specific terms, etc.
<div class="toggle_area">
    <div class="toggle_label"><a href="#">See Examples</a></div>

    <div class="toggle_content"><br />
        <div class="example">For example, let's say your key concept was "women". Some related keywords might be:
        <ul><li>woman - Some authors might choose to use the singular version of women.</li>
        <li>gender - This is a broader term that might provide a few additional search results.</li>
        <li>girls - This is a more specific term might provide focus to your search results.</li>
        </ul>
        </div>
    </div>
</div></p>

<h4>Let's begin with your first key concept, <em><?php echo $current_concept_text; ?></em>:</h4>

<p><label for="concept1keyword1" class="hidden">Keyword 1</label><input id="concept1keyword1" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept1keyword2" class="hidden">Keyword 2</label><input id="concept1keyword2" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept1keyword3" class="hidden">Keyword 3</label><input id="concept1keyword3" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept1keyword4" class="hidden">Keyword 4</label><input id="concept1keyword4" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept1keyword5" class="hidden">Keyword 5</label><input id="concept1keyword5" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept1keyword6" class="hidden">Keyword 6</label><input id="concept1keyword6" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept1keyword7" class="hidden">Keyword 7</label><input id="concept1keyword7" type="text" name="keyword[]" class="dataentry"></p>

<input type="submit" value="Next" class="submitbutton">

</form>

<p>&nbsp;</p>

</body>
</html>

