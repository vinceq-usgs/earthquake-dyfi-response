<?php
if (!isset($TEMPLATE)) {

  $TITLE = 'Example Form';

  // If you want to include section navigation.
  // The nearest _navigation.inc.php file will be used by default
  $NAVIGATION = true;

  include 'template.inc.php';
}
?>


<p class="alert info">
  Submit a form.
    
</p>
<form action="response.php" method="post">
<input type="text" name="fldSituation_felt" value="1_felt"><br>
<input type="text" name="fldEffects_shelved" value="1_fell"><br>
<input type="submit" name="ciim_report" value="Submit Form">
</form>
