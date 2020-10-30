<?php


$ini = parse_ini_file('../var/config.ini');




$thelist="";
$aa=$ini['PATH_UBR'];


  if ($handle = opendir($aa)) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        $thelist .= '<li><a href="'.$file.'">'.$aa.$file.'</a></li>';
      }
    }
    closedir($handle);
  }
?>

<h1>List of files:</h1>
<ul><?php echo $thelist; ?></ul>
