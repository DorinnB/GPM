<?php
/* Function for icon */
function icon($type, $test_type_abbr, $language, $specific, $icon) {

  if (file_exists('../templates/'.$type.' '.$test_type_abbr.'_'.$language.'_'.$specific.'.xlsm')) {
    echo '
    <li class="list-group-item found" onclick="createReport(\''.$type.'\',\''.$language.'\',\''.$specific.'\');">
    <img src="img/'.$icon.'.png" style="width: auto;max-height: 30px;">
    </li>
    ';
  }
  else {
    echo '
    <li class="list-group-item notfound">
    <img src="img/'.$icon.'.png" style="width: auto;max-height: 30px;">
    </li>
    ';
  }
}
?>


<div class="col-md-6">
  <p style="text-align: center; font-weight:bold;">Report<br/>Standard</p>
  <ul class='priorite-menu list-group row'>

    <?php icon('Report', $SplitInfo['test_type_abbr'], 'FR', 'Std', 'FlagFrench') ?>
    <?php icon('Report', $SplitInfo['test_type_abbr'], 'USA', 'Std', 'FlagUSA') ?>

  </ul>
  <p style="text-align: center; font-weight:bold;">Report<br/>Specific</p>
  <ul class='priorite-menu list-group row'>

    <?php icon('Report', $SplitInfo['test_type_abbr'], 'FR', $SplitInfo['specific_test'], 'FlagFrench') ?>
    <?php icon('Report', $SplitInfo['test_type_abbr'], 'USA', $SplitInfo['specific_test'], 'FlagUSA') ?>

  </ul>
</div>
<div class="col-md-6">
  <p style="text-align: center; font-weight:bold;">Annexe<br/>Standard</p>
  <ul class='priorite-menu list-group row'>

    <?php icon('Annexe', $SplitInfo['test_type_abbr'], 'FR', 'Std', 'FlagFrench') ?>
    <?php icon('Annexe', $SplitInfo['test_type_abbr'], 'USA', 'Std', 'FlagUSA') ?>

  </ul>
  <p style="text-align: center; font-weight:bold;">Annexe<br/>Specific</p>
  <ul class='priorite-menu list-group row'>

    <?php icon('Annexe', $SplitInfo['test_type_abbr'], 'FR', $SplitInfo['specific_test'], 'FlagFrench') ?>
    <?php icon('Annexe', $SplitInfo['test_type_abbr'], 'USA', $SplitInfo['specific_test'], 'FlagUSA') ?>

  </ul>
</div>
