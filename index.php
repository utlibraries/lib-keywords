<?php include_once('./header.php'); ?>

<script type="text/javascript">
   function formfocus() {
      document.getElementById('topic').focus();
   }
   window.onload = formfocus;
</script>

</head>
<body>

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

