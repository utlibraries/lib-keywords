<?php
/*
 *	Copyright 2013 University of Texas at Austin
 *
 *  Licensed under the Educational Community License, Version 2.0 (the "License"); 
 *  you may not use this file except in compliance with the License. You may obtain a 
 *  copy of the License	at:
 *
 *  http://www.osedu.org/licenses/ECL-2.0
 *
 *	Unless required by applicable law or agreed to in writing, software
 *	distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 *	WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 *	License for the specific language governing permissions and limitations
 *	under the License.
 *
 */
?>

<?php include_once('./header.php'); ?>

<script type="text/javascript">
   function formfocus() {
      document.getElementById('concept3keyword1').focus();
   }
   window.onload = formfocus;
</script>

</head>
<body>

<!--Add institutional branding here-->

<?php

//Variables
$keywords = array();
$old_keywords = array();
$all_concepts = array();

$keywords = $_POST['keyword'];

$concept_id = $_POST['concept_id'] ;
if (!is_numeric($concept_id)) {
	failure($errmsg['invalidinputs'], $location);
}

$old_keywords = get_concept_keywords($concept_id)  ;

if ( (isset($old_keywords)) && !(empty($old_keywords)) ) {
    $delete =  "DELETE FROM libkw_keywords WHERE concept_id='$concept_id'";
    $resDelete = mysql_query($delete) or die('Query failed: ' . mysql_error());
}

$topic_id = $_POST['topic_id'] ;
if (!is_numeric($topic_id)) {
	failure($errmsg['invalidinputs'], $location);
}

$current = $_POST['current'];
if (!is_numeric($current)) {
	failure($errmsg['invalidinputs'], $location);
}

foreach ($keywords as $key => $value) {
    if ( isset($value) && ($value != "") ) {
        $value = htmlentities( $value, ENT_QUOTES, "UTF-8" );

        add_keyword($concept_id, $value) ;
    }
}

?>

<h2>How to Generate Keywords</h2>

<h3>Related Keywords for <em>

<?php

    $all_concepts = get_topic_concept_ids($topic_id) ;
    sort($all_concepts) ;
    $new_concept_text = get_concept_from_ID($all_concepts[$current+1]) ;
    echo $new_concept_text;
    $newcurrent = $current + 1 ;

echo "</em></h3>\n" ;

if ( isset($all_concepts[3]) && ($all_concepts[3] != "") ) {
    print "<form action=\"keywords4.php\" method=\"POST\">\n" ;
} else {
    print "<form action=\"summary.php\" method=\"POST\">\n" ;
}

?>

<input type="hidden" name="concept_id" value="<?php echo $all_concepts[$newcurrent]; ?>">
<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
<input type="hidden" name="current" value="<?php echo $newcurrent; ?>">

<p>Your key concepts are:</p>
<blockquote>
    <ul>

    <?php
        foreach ($all_concepts as $c) {
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
</div>
</p>

<h4>Now your third key concept, <em><?php echo $new_concept_text; ?></em>:</h4>

<p><label for="concept3keyword1" class="hidden">Keyword 1</label><input id="concept3keyword1" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept3keyword2" class="hidden">Keyword 2</label><input id="concept3keyword2" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept3keyword3" class="hidden">Keyword 3</label><input id="concept3keyword3" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept3keyword4" class="hidden">Keyword 4</label><input id="concept3keyword4" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept3keyword5" class="hidden">Keyword 5</label><input id="concept3keyword5" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept3keyword6" class="hidden">Keyword 6</label><input id="concept3keyword6" type="text" name="keyword[]" class="dataentry"></p>
<p><label for="concept3keyword7" class="hidden">Keyword 7</label><input id="concept3keyword7" type="text" name="keyword[]" class="dataentry"></p>

<input type="submit" value="Next" class="submitbutton">

</form>

<p>&nbsp;</p>

</body>
</html>

