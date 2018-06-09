<?php




$DataInput=(isset($_GET['modif']) AND $_GET['modif']=="dataInput")?"Input":"";


include 'models/lstConsigne-model.php';
$lstConsigne = new ConsigneModel($db);
$Consigne = $lstConsigne->getAllConsigne();

include 'models/lstRawData-model.php';
$lstRawData = new RawDataModel($db);
$RawData = $lstRawData->getAllRawData();






      //HISTORIQUE
      //recup de l'historique des modifications
      $histoTbljob=$oHisto->getHistoTbljobs($split['id_tbljob']);


      //s'il y a un historique pour l'eprouvette
      if (count($histoTbljob)>0) {
        //initialisation des valeurs de l'historique sur le premier de la liste
        foreach ($histoTbljob[0] as $key => $value) {
          $tbljobHisto[$key]=array();
          array_push($tbljobHisto[$key],(($value=="")?' ':$value));
        }
        //on parcours tous les historiques de l'eprouvette
        for ($i=1; $i < count($histoTbljob); $i++) {
          //pour chaque champ de la table
          foreach ($histoTbljob[$i] as $key => $value) {
            //on compare sa valeur avec celle de l'historique precedent, si different on ajoute la nouvelle valeur, sinon on garde la meme.
            if ($histoTbljob[$i][$key]!=$histoTbljob[$i-1][$key]) {
              //on affiche une etoile si vide
              array_push($tbljobHisto[$key],(($value=="")?' ':$value));
            }
          }
        }
        //pour chaque champ de l'historique,on retire le dernier enregistrement si identique a la valeur actuelle de l'eprouvette et on prepare le tooltip d'affichage
        foreach ($histoTbljob[0] as $key => $value) {
          //si la fin correspond a la derniere valeur, on supprime de l'historique
          if (isset($split[$key]) AND end($tbljobHisto[$key])==$split[$key]   ) {
            array_pop($tbljobHisto[$key]);
          }
          if (count($tbljobHisto[$key])>0 AND $tbljobHisto[$key][0]==" ") {
            array_shift($tbljobHisto[$key]);
          }
          //si l'array de chaque element n'est pas vide et correspond a la denomination des champs de $tbljob
          if (isset($split[$key]) and count($tbljobHisto[$key])>0) {
            //initialisation du tooltip
            $tbljobHisto2[$key]='<img class="histoData" src="img/changed.png" style="height:15px;" data-toggle="tooltip" title="';

            //pour chaque element, on ajoute le texte au tooltip
            foreach ($tbljobHisto[$key] as $ke => $va) {
              $tbljobHisto2[$key].=$va." | ";
            }
            //finalisation du tooltip
            $tbljobHisto2[$key].='">';
          }
        }
      }
      //on vient preparer le css dans le cas ou il n'y a pas d'historique
      foreach ($split as $key => $value) {
        $tbljobHisto2[$key]=(isset($tbljobHisto2[$key]))?$tbljobHisto2[$key]:"";
      }






// Affichage du résultat
include 'views/splitData'.$DataInput.'_PS-view.php';
