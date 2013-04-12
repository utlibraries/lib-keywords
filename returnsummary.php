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

<?php
//Variables
$clean = array() ;
$concepts = array();
$topic_detail = array();
$search_key = array();

if (isset($_GET["id"])) {
    $topic_id = $_GET["id"];
    if (!is_numeric($topic_id)) {
		failure($errmsg['invalidinputs'], $location);
    }

} elseif (isset($_POST["topic_id"])) {
    $topic_id = $_POST["topic_id"];
    if (!is_numeric($topic_id)) {
		failure($errmsg['invalidinputs'], $location);
    }
    $clean['topic_id'] = $topic_id;

    $name = $_POST["name"] ;
    $email = $_POST["email"] ;
    $instructoremail = $_POST["instructoremail"] ;
    $course = $_POST["course"] ;

    if ( isset($name) && ($name != "") ) {
        $name = htmlentities( $name, ENT_QUOTES, "UTF-8" ) ;
        $clean['name'] = $name;
    } else {
        failure($errmsg['required'], $location);
    }

    if ( isset($email) && ($email != "") ) {
        filteremail($email) ;
        $clean['email'] = $email;
    } else {
        failure($errmsg['required'], $location);
    }

    if ( isset($instructoremail) && ($instructoremail != "") ) {
        filteremail($instructoremail) ;
        $clean['instructoremail'] = $instructoremail;
    }

    if ( isset($course) && ($course != "") ) {
        $course = htmlentities( $course, ENT_QUOTES, "UTF-8" ) ;
        filteralphanum($course) ;
        $clean['course'] = $course;
    }

    //both email and name are required
    if ( isset($email) && isset($name) ) {
	    processemail($clean);
	} elseif ( isset($instructoremail) && (!(isset($email)) || !(isset($name)) ) ) {
	    failure($errmsg['errormail'], $location);
	} else {
		$http_location = "returnsummary.php?id=$topic_id" ;
		header("Location: $http_location");
	}


} else {
    $http_location = "admin/index.php" ;
    header("Location: $http_location");
}

$topic_detail = get_topic_detail($topic_id) ;

$concepts = get_topic_concepts($topic_id);
$num_concepts = count($concepts);

?>

<h2>How to Generate Keywords</h2>

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

<h3>Automatically search with your keywords. </h3>
<p>Getting too many results when you search?  In the Catalog or database, remove some of the keywords you brainstormed for each concept.</p>
<form action="http://www.lib.utexas.edu/phpsearchbox.html" method="post" target="_blank">
        <p><label for="kwquery">
        SEARCH: </label><label for="kwengine" id="searcharea">choose an area to search</label>
        <select name="engine" size="1" id="kwengine" title="choose an area to search" style="width:300px;">
          <option selected="selected" value="iiikw">Library Catalog for books</option>
          <option value="acadsearchcomp">Academic Search Complete  for newspaper, magazine, and scholarly journal articles</option>
          <option value="jstor">JSTOR for scholarly journal articles</option>

        </select><br /><br />
        <textarea cols="40" rows="5" name="query" id="kwquery" readonly /><?php echo "$fullsearch" ; ?></textarea>
        <input type="submit" id="GO" value="GO" /></p>
      </form>


<p>&nbsp;</p>

</body>
</html>

