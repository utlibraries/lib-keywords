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

</head>
<body>

<?php
$topic_id = $_POST['topic_id'] ;
if (!is_numeric($topic_id)) {
	failure($errmsg['invalidinputs'], $location);
}

?>

<?php

//Variables
$concepts = array();
$topic_detail = array();
$search_key = array();
$keywords = array();
$old_keywords = array();

$concept_id = $_POST['concept_id'] ;

if (!is_numeric($concept_id)) {
	failure($errmsg['invalidinputs'], $location);
}

$old_keywords = get_concept_keywords($concept_id)  ;

if ( (isset($old_keywords)) && !(empty($old_keywords)) ) {
    $delete =  "DELETE FROM libkw_keywords WHERE concept_id='$concept_id'";
    $resDelete = mysql_query($delete) or die('Query failed: ' . mysql_error());
}

$current = $_POST['current'];
if (!is_numeric($current)) {
	failure($errmsg['invalidinputs'], $location);
}

$keywords = $_POST['keyword'];
foreach ($keywords as $key => $value) {
    if ( isset($value) && ($value != "") ) {
		$value = htmlentities( $value, ENT_QUOTES, "UTF-8" );
		$value = strip_tags($value) ;
		$value = utf8_decode($value);

        add_keyword($concept_id, $value) ;
    }
}

$topic_detail = get_topic_detail($topic_id) ;

$concepts = get_topic_concepts($topic_id);
$num_concepts = count($concepts);

?>

<a href="contact.php?id=<?php echo $topic_id; ?>"><img src="graphics/email-graphic.jpg" alt="Email Your Results" style="float:right;border:0px;"></a><h2>How to Generate Keywords</h2>

<h3>Your Keywords</h3>

<p>Below is the list of keywords you have generated. You can use these keywords as search terms when researching your topic. </p>

<table class="resultstable" summary="Keywords generated for research topic">
<tbody>
<tr>
<th>Topic</th>
<!-- colspan will be determined by the number of key concepts submitted... min 2 max 4 -->
<td colspan="<?php echo($num_concepts) ;?>"><?php echo($topic_detail['topic_text']) ;?></td>
</tr>
<tr>
<th>Key Concepts</th>

<?php

$tmp = 1;   //hold for determining class for css
$concept_key = array();  //for holding concept_ids in order of display of concept_text to keep keywords in line
foreach ($concepts as $key => $value) {
    $classnumber = "concept" . "$tmp" ;
    $tmp++;
    $concept_key[$classnumber] = $key;

    $search_key[$classnumber] = $value ;
    print ("<td class=\"$classnumber\">$value</td>\n");

}

?>

</tr>

<tr>
<th>Related Keywords</th>

<?php

foreach ($concept_key as $key => $value) {

    $keywords = array();
    $keywords = get_concept_keywords($value);
    print ("<td class=\"$key\">\n");

    foreach ($keywords as $k => $v) {
		$v = htmlentities( $v, ENT_QUOTES, "UTF-8" );
        $search_key[$key] .= " OR " . $v ;
        print ("$v<br />\n");
    }

    print "</td>\n" ;
}

?>

</tr>
</tbody>
</table>

<p>&nbsp;</p>

<h3>How to Use Your Keywords</h3>

<p>Now that you've created your list of keywords, you will need to combine them using BOOLEAN operators (AND and OR).<p>

<p>In your example, the combined keywords would look like this:</p>
<blockquote>
<?php
$search_count = count($search_key) ;
$tmp = 1;
$fullsearch = "";
foreach ($search_key as $key => $value) {
    print ("<p class=\"$key\">$value</p>\n");
    $fullsearch .= "(" . $value . ")";
    if ( $tmp < $search_count) {
        print "<p>AND</p>\n" ;
        $fullsearch .= " AND "  ;
    }
    $tmp++;
}


?>

</blockquote>
<!-- ENTER PATH TO YOUR OWN SEARCH ENGINE SCRIPT OR REMOVE THIS SECTION IF YOU DON'T HAVE ONE WILL HAVE TO EDIT THE OPTIONS AND VALUES DEPENDING ON WHAT YOUR DATABASES USE [CHANGES REQUIRED] -->


<h3>Automatically search with your keywords. </h3>
<p>Getting too many results when you search?  In the Catalog or database, remove some of the keywords you brainstormed for each concept.</p>
<form action="[PATH TO SEARCH ENGINE SCRIPT]" method="post" target="_blank">
        <p><label for="kwquery">
        SEARCH: </label><label for="kwengine" id="searcharea">choose an area to search</label>
        <select name="engine" size="1" id="kwengine" title="choose an area to search" style="width:300px;">
          <option selected="selected" value="[OPTION 1]">Library Catalog for books</option>
          <option value="[OPTION 2]">Academic Search Complete  for newspaper, magazine, and scholarly journal articles</option>
          <option value="[OPTION 3]">JSTOR for scholarly journal articles</option>

        </select><br /><br />
        <textarea cols="40" rows="5" name="query" id="kwquery" readonly /><?php echo "$fullsearch" ; ?></textarea>
        <input type="submit" id="GO" value="GO" /></p>
      </form>

<!-- REMOVE ABOVE SECTION IF YOU DON'T HAVE A WAY TO CONNECT TO YOUR DATABASE SEARCH ENGINE SCRIPT -->

<p>&nbsp;</p>

</body>
</html>

