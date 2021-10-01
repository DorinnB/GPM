<?php
//a supprimer pour enregistrement
header ("Content-type: image/png");


$image = imagecreatefrompng("base300NB.png"); // La photo est la destination

// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
$largeur = imagesx($image);
$hauteur = imagesy($image);

//imagestring($image, $police, $x, $y, $texte_a_ecrire, $couleur);
// Il existe aussi la fonction imagestringup  qui fonctionne exactement de la même manière, sauf qu'elle écrit le texte verticalement et non horizontalement !

$noir = imagecolorallocate($image, 0, 0, 0);


putenv('GDFONTPATH=' . realpath('.'));
$font = 'arial.ttf';

//imagettftext($image, 30, 0, 200, 200, $noir, $font, 'TEST TEST'); ==> image, size, rotation, posX, posY, color, font, text)

function textCentre($image, $size, $orientation, $row, $noir, $font, $string) {
  $bbox = imagettfbbox($size, 0, $font, $string);
  $x = $bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2) ;
  imagettftext($image, $size, 0, $x, $row, $noir, $font, $string);
}
function textCentreDecale($image, $size, $orientation, $row, $noir, $font, $string) {
  $bbox = imagettfbbox($size, 0, $font, $string);
  $x = $bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2)+90 ;
  imagettftext($image, $size, 0, $x, $row, $noir, $font, $string);
}

function textCentre1($image, $size, $orientation, $row, $noir, $font, $string) {
  $bbox = imagettfbbox($size, 0, $font, $string);
  $x = $bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2) ;
  imagettftext($image, $size, 0, $x*0.3, $row, $noir, $font, $string);
}
function textCentre2($image, $size, $orientation, $row, $noir, $font, $string) {
  $bbox = imagettfbbox($size, 0, $font, $string);
  $x = $bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2) ;
  imagettftext($image, $size, 0, $x*1.7, $row, $noir, $font, $string);
}

$row=80; $col=250; $col2=450;
$size=20; $size1=20; $size2=25; $size3=40;$size4=70;

textCentreDecale($image, $size4, 0, $row, $noir, $font, 'STRAIN CAL');
$row+=100;

textCentreDecale($image, $size4, 0, $row, $noir, $font, '10032');
$row+=50;

textCentre1($image, $size1, 0, $row, $noir, $font, 'FRAME');
textCentre($image, $size1, 0, $row, $noir, $font, 'By');
textCentre2($image, $size1, 0, $row, $noir, $font, 'Gamme');
$row+=50;
textCentre1($image, $size2, 0, $row, $noir, $font, '20021');
textCentre($image, $size2, 0, $row, $noir, $font, 'PGO');
textCentre2($image, $size3, 0, $row, $noir, $font, '-2/+5%');
$row+=50;

textCentre1($image, $size1, 0, $row, $noir, $font, 'Date');
textCentre2($image, $size1, 0, $row, $noir, $font, 'Due');
$row+=50;
textCentre1($image, $size2, 0, $row, $noir, $font, '02 Apr 2021');
$row+=40;
textCentre2($image, $size4, 0, $row, $noir, $font, '01 Jul 2021');
$row+=80;

textCentre($image, $size3, 0, $row, $noir, $font, '0v=-2.345v / sc=7.456v');


/*
imagettftext($image, 30, 0, $largeur/2-100, 50, $noir, $font, 'ALIGNEMENT');

$row=150; $col=250; $col2=450;
$size=20; $size2=25;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Frame:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, '20021');
$row+=50;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Date Cal:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, '2021-01-01');
$row+=50;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Date Due:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, '2021-03-01');
imagettftext($image, $size2, 0, $col2-$size2, $row-$size2*0.8, $noir, $font, '┌───────┐');
imagettftext($image, $size2, 0, $col2-$size2, $row+$size2*0.8, $noir, $font, '└───────┘');  //alt 179 191 192 217 218
$row+=50;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Tooling Top:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, 'M12-1-P-100-75');
$row+=50;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Tooling Bot:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, 'M12-1-P-100-76');
$row+=50;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Zero:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, '0.238 Volt');
$row+=50;
imagettftext($image, $size, 0, $col, $row, $noir, $font, 'Technician:');
imagettftext($image, $size2, 0, $col2, $row, $noir, $font, 'PGO');

*/




imagejpeg($image);
imagepng($image, "../test/Expected.png");

  exec('"C:\Program Files\IrfanView\i_view64.exe" c:\wamp\www\GPM\\test\Expected.png" /print="ZD420"');

?>
