<?php

$essai=$oEprouvette->getTest();

$element = array(
  $essai['n_fichier'],
  $essai['n_essai'],
  $essai['date'],
  $essai['test_type_abbr'],
  $essai['c_temperature'],
  $essai['customer'],
  $essai['job'],
  $essai['split'],
  $essai['n_essai'],
  $essai['prefixe'],
  $essai['nom_eprouvette'],
  $essai['machine'],
  $essai['operateur'],
  $essai['controleur']


);

$txt="";
//Pour chaque element du tableau on ajoute la valeur a $txt
foreach ($element as $value) {
  $txt.=$value.";";
}


$srcfile = '//Srvdc/donnees/LABO/Computer/GPM/TestList.txt';

$myfile = file_put_contents($srcfile, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);


?>
