<?php

if (!isset($TEMPLATE)) {
  // template functions
  include_once 'functions.inc.php';

  // defines the $CONFIG hash of configuration variables
  include_once '../conf/config.inc.php';

  $TITLE = 'DYFI Responses v{{VERSION}}';
  $HEAD = '<link rel="stylesheet" href="css/index.css"/>';
  $FOOT = '<script src="js/index.js"></script>';

  include 'template.inc.php';
}

?>

<div id="application">
  <noscript>
    <a href="https://www.google.com/search?q=javascript">
      This page requires javascript.
    </a>
  </noscript>
</div>

<p>Test blank form:
<form action='response.php' method='post'>
<input type="submit" name="ciim_report" value="Submit Form">
</form>
</p>

<p>Test partial form:
<form action="response.php" method="post">
<input type="hidden" name="ciim_mapLat" value="33.5001">
<input type="hidden" name="ciim_mapLon" value="-116.2301">
<input type="hidden" name="form_version" value="1.3">
<input type="hidden" name="fldSituation_felt" value="1_felt">
<input type="hidden" name="fldEffects_shelved" value="1_fell">
<input type="submit" name="ciim_report" value="Submit Form">
</form></p>

<p>Test fully completed form:
<form action="response.php" method="post">
<input type="hidden" name="ciim_mapLat" value="33.5001">
<input type="hidden" name="ciim_mapLon" value="-116.2301">
<input type="hidden" name="ciim_mapConfidence" value="">
<input type="hidden" name="code" value="1.3">
<input type="hidden" name="network" value="1.3">
<input type="hidden" name="dyficode" value="1.3">
<input type="hidden" name="ciim_time" value="">
<input type="hidden" name="" value="">
<input type="hidden" name="" value="">
<input type="hidden" name="" value="">

<input type="hidden" name="form_version" value="1.3">
<input type="hidden" name="" value="">
<input type="hidden" name="fldSituation_felt" value="1_felt">
<input type="hidden" name="fldSituation_situation" value="1_felt">
<input type="hidden" name="fldSituation_sleep" value="1_felt">
<input type="hidden" name="fldSituation_others" value="1_felt">
<input type="hidden" name="fldExperience_shaking" value="1_fell">
<input type="hidden" name="fldExperience_reaction" value="1_fell">
<input type="hidden" name="fldExperience_response" value="1_fell">
<input type="hidden" name="fldExperience_stand" value="1_fell">
<input type="hidden" name="fldEffects_shelved" value="1_fell">
<input type="submit" name="ciim_report" value="Submit Form">
</form></p>

<a href="testform.php">Go to test form.</a>

