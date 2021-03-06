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
      document.getElementById('name').focus();
   }
   window.onload = formfocus;
</script>

</head>
<body>

<?php
$topic_id = $_GET['id'] ;
if (!is_numeric($topic_id)) {
	failure($errmsg['generic'], $location);
}

?>

<div style="	font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; line-height : 17.5px; text-decoration : none;">
<h2>Email Your Keywords</h2>

<p>*Required</p>

<form action="returnsummary.php" method="POST">

<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">

<p><label for="name">Your name *</label><br /><input id="name" type="text" name="name"></p>
<p><label for="email">Your email address *</label><br /><input id="email" type="text" name="email"></p>
<p><label for="instructoremail">Would you like to send your work to your instructor?  Enter the email address here.</label><br /><input id="instructoremail" type="text" name="instructoremail"></p>
<p><label for="course">Course Prefix/Number (EX. SOC 302)</label><br /><input id="course" type="text" name="course"></p>

<input type="submit" value="Submit" class="submitbutton">

</form>
</div>
<p>&nbsp;</p>



