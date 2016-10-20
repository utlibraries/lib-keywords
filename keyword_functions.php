<?php
/*  Name: keyword_functions.php
    Description: Functions for Keyword Project
*/

//requires adding database info here
// Connecting, selecting database
$link = db_connect("INSERT_DATABASE_NAME_HERE");

// Error Messages (edit as needed)
$errmsg['generic'] = "<br /><p>Error occurred processing your request please try again. <br />If the problem persists please contact the system administrator.</p>";
$errmsg['perms'] = "<p> Sorry, you do not have access to this page.</p>" ;
$errmsg['required'] =  "<p>Both Student Name and Email are required.</p>";
$errmsg['invalidinputs'] = "<br /><p>Check that you entered information for the last step. Or <strong><a href=\"index.php\">start over</a></strong>.  </p>";
$errmsg['invalidemail'] = "<p> Email address is not valid.</p>";
$errmsg['errormail'] = "<p> We're sorry, in order to send your work to your instructor we need your email address and your instructor's email address. </p>";
$errmsg['noeid'] = "<p>EID required to access this page.</p>";
$errmsg['toofewconcepts'] = "<p>A minimum of 2 concepts per topic are required.</p>";


//when failure occurs it prints out the appropriate error message
function failure($errmsg)
{
    print "$errmsg" ;
    print "</body>\n</html>\n" ;
    exit ;
}

//requires adding MYSQL database information here
function db_connect($db)
{

    $dbhost = ini_get("mysql.default_host");
    $dbuser = "INSERT_DATABASE_USER_HERE";      // change to appropriate
    $dbpass = "INSERT_DATABASE_PASSWORD_HERE";      // change to appropriate

    $dbname = strtoupper($db);

    if ($dbname == "") {
        $dbname = "INSERT_DATABASE_NAME_HERE";      // change to appropriate
        $dbpass = "INSERT_DATABASE_PASSWORD_HERE";      // change to appropriate
    }

    // Connecting, selecting database
    $link = mysql_connect($dbhost, $dbuser, $dbpass)
         or die('Could not connect: ' . mysql_error());

    mysql_select_db($dbname) or die('Could not select database');

    return $link;
}

//update existing topic info to include email/course info from summary page
function processemail($clean)
{

    global $errmsg;
    $identify = array() ;

    $topic_id = $clean["topic_id"] ;
    $name = $clean["name"] ;
    $email = $clean["email"] ;
    $instructoremail = $clean["instructoremail"] ;
    $course = $clean["course"] ;

//change the "From" address to correspond to your service
    $headers = "From: youremail@yourinstitution.com\r\n" ;
    $subject = "Results of Keyword Tool";

    $mail_array = array();
    if ( ( (isset($email)) && !(empty($email)) ) && ( (isset($name)) && !(empty($name)) ) ) {
        filteremail($email) ;
        array_push($mail_array, $email);
        array_push($identify, $name);
    } else {
        failure($errmsg['required']);
    }

    if ( (isset($instructoremail)) && !(empty($instructoremail)) ) {

        filteremail($instructoremail) ;
        array_push($mail_array, $instructoremail);

    } else {
        $instructoremail = "" ;
    }

    if ( (isset($course)) && !(empty($course)) ) {

        filteralphaplus($course) ;
        $course = course_normalization($course) ;
        array_push($identify, $course);

    } else {
        $course = "" ;
    }

    $update = "UPDATE libkw_topics
        SET
        course          = '$course'
                WHERE topic_id = '$topic_id'";

    $resUpdate = mysql_query($update);
    if (!$resUpdate) {
        failure($errmsg['generic']);
    }

//edit urls to appropriate full urls since they will be displayed
    $url = 'http://www.yourinstitution.com/keywords/returnsummary.php?id=' . $topic_id  ;
    $homeurl = 'http://www.yourinstitution.com/keywords/index.php'  ;

//change email message to appropriate message for your institution
    $message = '<html><body>';
    $message .= "<p>View the keywords generated by $email (" ;
    $message .= implode(', ', $identify) ;
    $message .= ") at $url. </p> " ;
    $message .= "<p>This message was sent via $homeurl. </p> ";
    $message .= "<p>Please note that we do not verify email addresses. </p> " ;
    $message .= "<p>If you have questions about the Keyword tool, please contact youremail@yourinstitution.com </p>";

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (!(empty($mail_array))) {
        $to = implode(", ", $mail_array) ;

        mail($to,$subject,$message,$headers);
    }


}

// DO NOT EDIT BELOW THIS LINE //

function db_close($link)
{
    mysql_close($link);
}

//only letters, numbers,  & spaces allowed
function filteralphaplus($test)
{
    global $errmsg;

    if( !(preg_match("/[a-zA-Z0-9\s\.\'\-]+/", $test)) ) {
        failure($errmsg['invalidinputs']);
    }

}

//strictly looking for alpha numeric input
function filteralphanum($testcase)
{
    global $errmsg;

    $testcase = htmlentities( $testcase, ENT_QUOTES, "UTF-8" ) ;
    $str = preg_replace('/\s+/', '', $testcase);

    if (!(ctype_alnum($str))) {
        failure($errmsg['invalidinputs']);
    }
}

//testing for valid email formats
function filteremail($email)
{
    global $errmsg;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))   {
        failure($errmsg['invalidemail']);
    }
}


//course name normalization
function course_normalization($test)
{
    global $errmsg;

    $course = strtoupper($test);
    $course = trim($course);
    $course = preg_replace('/\s+/', ' ', $course);

    return $course;
}

//new topic
function add_topic($topic)
{
    global $errmsg;

    $topic = mysql_real_escape_string($topic);

    $today = time();

    $insert = "INSERT INTO libkw_topics
        SET
        topic_text            = '$topic',
        date_added            = '$today' " ;

    $resInsert = mysql_query($insert);
    if (!$resInsert) {
        failure($errmsg['generic']);
    }

    // Performing SQL query
    $query = "SELECT topic_id FROM libkw_topics WHERE topic_text='$topic' AND date_added='$today'";
    $result = mysql_query($query) or failure($errmsg['generic']);;

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $topic_id   = $row['topic_id'];
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $topic_id ;

}

//new concept
function add_concept($concept, $topic_id)
{
    global $errmsg;

    $concept = mysql_real_escape_string($concept);

    $topic_id = mysql_real_escape_string($topic_id);

    $today = time();

    $insert = "INSERT INTO libkw_concepts
        SET
        concept_text    = '$concept',
        topic_id        = '$topic_id',
        date_added      = '$today' " ;

    $resInsert = mysql_query($insert);
    if (!$resInsert) {
        failure($errmsg['generic']);
    }

    // Performing SQL query
    $query = "SELECT concept_id FROM libkw_concepts WHERE concept_text='$concept' AND date_added='$today'";
    $result = mysql_query($query) or failure($errmsg['generic']);;

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $concept_id = $row['concept_id'];
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $concept_id ;

}

//new keyword
function add_keyword($concept_id, $keyword)
{
    global $errmsg;

    $concept_id = mysql_real_escape_string($concept_id);

    $keyword = mysql_real_escape_string($keyword);

    $today = time();

    $insert = "INSERT INTO libkw_keywords
        SET
        keyword_text    = '$keyword',
        concept_id      = '$concept_id',
        date_added      = '$today' " ;

    $resInsert = mysql_query($insert);
    if (!$resInsert) {
        failure($errmsg['generic']);
    }

}

//get topic detail from ID
function get_topic_detail ($topic_id)
{
    global $errmsg;

    $topic_id = mysql_real_escape_string($topic_id);

    $detail = array();
    $detail['topic_id'] = $topic_id ;

    // Performing SQL query
    $query = "SELECT topic_text, course FROM libkw_topics WHERE topic_id='$topic_id'";
    $result = mysql_query($query) or failure($errmsg['generic']);;

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $detail['topic_text']       = $row['topic_text'];
            $detail['course']           = $row['course'];
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $detail ;
}

//get topic concepts from ID
function get_topic_concepts ($topic_id)
{
    global $errmsg;

    $topic_id = mysql_real_escape_string($topic_id);

    $concepts = array();

    // Performing SQL query
    $query = "SELECT concept_id, concept_text FROM libkw_concepts WHERE topic_id='$topic_id'";
    $result = mysql_query($query) or failure($errmsg['generic']);;

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $concept_text           = $row['concept_text'];
            $concept_id             = $row['concept_id'];
            $concepts[$concept_id]  = $concept_text;
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $concepts ;
}

//get topic concepts from ID
function get_topic_concept_ids ($topic_id)
{
    global $errmsg;

    $topic_id = mysql_real_escape_string($topic_id);

    $concepts = array();

    // Performing SQL query
    $query = "SELECT concept_id FROM libkw_concepts WHERE topic_id='$topic_id'";
    $result = mysql_query($query) or failure($errmsg['generic']);;

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $concept_id = $row['concept_id'];
            array_push($concepts, $concept_id);
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $concepts ;
}

//get concept keywords from concept_id
function get_concept_keywords ($concept_id)
{
    global $errmsg;

    $concept_id = mysql_real_escape_string($concept_id);

    $keywords = array();

    // Performing SQL query
    $query = "SELECT keyword_id, keyword_text FROM libkw_keywords WHERE concept_id='$concept_id'";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $keyword_text           = $row['keyword_text'];
            $keyword_id             = $row['keyword_id'];
            $keywords[$keyword_id]  = $keyword_text;
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $keywords ;
}

//get concept from ID
function get_concept_from_ID ($concept_id)
{
    global $errmsg;

    $concept_id = mysql_real_escape_string($concept_id);

    // Performing SQL query
    $query = "SELECT * FROM libkw_concepts WHERE concept_id='$concept_id'";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());


    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $concept_text = $row['concept_text'];
        }
    } else {
        mysql_free_result($result);
        failure($errmsg['generic']);
    }

    // Free resultset
    mysql_free_result($result);

    return $concept_text ;
}
