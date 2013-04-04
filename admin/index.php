<?php include_once('./header.php'); ?>

<?php

//if your institution does not use the EID system then some coding will have to be done to validate users
$eid = checkeid();
checkadmin($eid);

//Variables
$topics = array(); //array for all topics with id as index sorted based on GET sort variable

//clean up topics with no concepts from database, restrict to older than six hours so as not to affect any in progress
topic_cleanup();

//gather all topics according to sorted listing
if (isset($_GET["sort"])) {
    $sort = $_GET["sort"];
    switch ($sort) {
        case "topic_text":
            $topics = get_all_topics_by_topic() ;
            break;
        case "course":
            $topics = get_all_topics_by_course() ;
            break;
        default:
           $topics = get_all_topics_by_topic() ;
    }

} else {
    $topics = get_all_topics_by_date() ;
}

?>

<h2>How to Generate Keywords</h2>

<table class="resultstable" summary="Keywords generated for research topic">
<thead>
<tr>
<th><a href="index.php?sort=topic_text">Research Topic</a></th>
<th><a href="index.php?sort=course">Course</a></th>
</tr>
</thead>
<tbody>

<?php
//gathers information about each topic in database for report
    $topic_detail = array();

    //get topic detail based on topic_id
    foreach($topics as $key => $value){
        $topic_detail = get_topic_detail($key) ;
        $topic_id = $topic_detail['topic_id'];
        $topic_text = $topic_detail['topic_text'];
        $course = $topic_detail['course'];
?>
		<!--print out report listing of topics from database-->
        <tr>
        <td><a href="../returnsummary.php?<?php echo ("id=" . $topic_id); ?>"><?php echo $topic_text; ?>.</a></td>
        <td><?php echo $course; ?></td>
        </tr>

<?php
    }

?>


</tbody>
</table>


<p>&nbsp;</p>

<!--Add appropriate footer here-->
</body>
</html>

