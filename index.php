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
      document.getElementById('topic').focus();
   }
   window.onload = formfocus;
</script>

</head>
<body>

<!--Add institutional branding here-->

<h2>How to Generate Keywords</h2>

<h3>Create a Research Topic</h3>

<form action="concepts.php" method="POST">

    <p><label for="topic">Enter your research topic below.</label> Try to limit the topic to one sentence that fully describes your research. Here are a few examples:</p>
    <ul><li>Effects of media on women's body image</li>
    <li>Trends in information technology in the workplace</li>
    <li>Fast food causes health risks for children</li>
    </ul>

    <input id="topic" type="text" name="topic" class="dataentry">

    <input type="submit" value="Next" class="submitbutton">

</form>

<p>&nbsp;</p>

<a href="http://www.ala.org/CFApps/Primo/public/search.cfm"><img src="graphics/color_Primo.gif" alt="Peer-Reviewed Instructional Materials Online Database" /></a>
&nbsp;
<a rel="license"
href="http://creativecommons.org/licenses/by-nc/3.0/deed.en_US"><img
alt="Creative Commons License" style="border-width:0"
src="http://i.creativecommons.org/l/by-nc/3.0/88x31.png" /></a>

</body>
</html>

