<?php


$file='//SRVDC/DONNEES/LABO/Calibrations/PDF/1_TempLine_20001_K.pdf';


 echo "modifié le : " . date ("F d Y H:i:s.", filemtime($file));
echo '<br/>';

    echo "modifié le : " . date("F d Y H:i:s.", filectime($file));
echo '<br/>';
echo fileowner ($file);
var_dump( stat ($file));



?>
