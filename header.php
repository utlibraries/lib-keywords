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

<?php require_once "./keyword_functions.php"; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>How to Generate Keywords</title>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name="description" content="How to generate keywords for conducting research.">
<meta name="keywords" content="instruction services keywords research">

<link href="keywords.css" rel="stylesheet" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!--Add extra header tags here-->

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery(".toggle_content").hide();
  //toggle the componenet with class msg_body
  jQuery(".toggle_label").click(function()
  {
    jQuery(this).next(".toggle_content").slideToggle(500);
  });
});
</script>

