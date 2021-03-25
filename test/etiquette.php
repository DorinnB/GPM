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


$row=75; $col=250; $col2=450;
$size=20; $size2=25; $size3=50;

imagettftext($image, $size3, 0, $largeur/2-100, $row, $noir, $font, 'ALIGNEMENT');
$row+=100;

textCentre($image, $size3, 0, $row, $noir, $font, 'GAMME / Equipement');
$row+=100;

imagettftext($image, $size2, 0, $col, $row, $noir, $font, 'Frame:');
imagettftext($image, $size3, 0, $col2, $row, $noir, $font, '20021');
$row+=100;

//textCentre($image, $size3, 0, $row, $noir, $font, 'date');
imagettftext($image, $size2, 0, $col, $row, $noir, $font, 'Frame:');
imagettftext($image, $size3, 0, $col2, $row, $noir, $font, '20021');
$row+=100;

textCentre($image, $size3, 0, $row, $noir, $font, 'com');


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
?>
