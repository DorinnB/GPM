<?php



//Appel du model
$ep=$oEprouvettes->getAllEprouvettes();


include 'models/eprouvette-model.php';



// Rendre votre modèle accessible
include 'models/annexe_IQC-model.php';


$oEp = new AnnexeIQCModel($db);
$ep=$oEp->getAllIQC($split['id_tbljob']);

//declaration des variables calculées
for($k=0;$k < count($ep);$k++)	{
  $oEp = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $workflow=$oEp->getWorkflow();





  //temporaire pour les cas sans c_checked (mettre defaut 0 ds mysql)
  $ep[$k]['c_checked']=($ep[$k]['c_checked']=="")?0:$ep[$k]['c_checked'];
  //temporaire pour les cas sans flag_qualite (mettre defaut 0 ds mysql)
  $ep[$k]['flag_qualite']=($ep[$k]['flag_qualite']=="")?0:$ep[$k]['flag_qualite'];





  $ep[$k]['dispo']='0';
  $ep[$k]['dispoText']='Awaiting Specimen';
  //disponibilite eprouvette
  if ($ep[$k]['d_checked']>0) {
    $ep[$k]['dispo']='6';
    $ep[$k]['dispoText']='Completed';
  }
  elseif (isset($workflow['ST']) & $workflow['ST']>0) {
    $ep[$k]['dispo']='0';
    $ep[$k]['dispoText']='Awaiting Specimen';
  }
  elseif (isset($workflow['local']) & $workflow['local']>0) {
    $ep[$k]['dispo']='1';
    $ep[$k]['dispoText']='Awaiting Previous Split';
  }
  elseif (isset($ep[$k]['master_eprouvette_inOut_A']) & $ep[$k]['master_eprouvette_inOut_A']>0) {
    $ep[$k]['dispo']='3';
    $ep[$k]['dispoText']='Ready to Test';
  }





  if ($k>0 && $format!=$ep[$k]['type']) {
    //pour la 2eme et + eprouvette du split, si le format change
    $dimDenomination=array("ERREUR !","MULTIPLE","FORMAT");
    $nbDim = 3;
  }
  else {
    //on sauvegarde le format d'eprouvette (pour voir s'il change)
    $format=  $ep[$k]['type'];


    $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);

    $dimDenomination=$oEp->dimensions($ep[$k]['id_dessin_type'], $ep[$k]['dim1'], $ep[$k]['dim2'], $ep[$k]['dim3']);
    //suppression des dimensions null
    foreach ($dimDenomination as $index => $data) {
      if ($data=='') {
        unset($dimDenomination[$index]);
      }
    }




    //HISTORIQUE
    //recup de l'historique des modifications
    $histoEp[$k]=$oHisto->getHistoAnnexeIQC($ep[$k]['id_eprouvette']);
    //s'il y a un historique pour l'eprouvette
    if (count($histoEp[$k])>0) {
      //initialisation des valeurs de l'historique sur le premier de la liste
      foreach ($histoEp[$k][0] as $key => $value) {
        $epHisto[$k][$key]=array();
        array_push($epHisto[$k][$key],(($value=="")?' ':$value));
      }
      //on parcours tous les historiques de l'eprouvette
      for ($i=1; $i < count($histoEp[$k]); $i++) {
        //pour chaque champ de la table
        foreach ($histoEp[$k][$i] as $key => $value) {
          //on compare sa valeur avec celle de l'historique precedent, si different on ajoute la nouvelle valeur, sinon on garde la meme.
          if ($histoEp[$k][$i][$key]!=$histoEp[$k][$i-1][$key]) {
            //on affiche une etoile si vide
            array_push($epHisto[$k][$key],(($value=="")?' ':$value));
          }
        }
      }
      //pour chaque champ de l'historique,on retire le dernier enregistrement si identique a la valeur actuelle de l'eprouvette et on prepare le tooltip d'affichage
      foreach ($histoEp[$k][0] as $key => $value) {
        //si la fin correspond a la derniere valeur, on supprime de l'historique
        if (isset($ep[$k][$key]) AND end($epHisto[$k][$key])==$ep[$k][$key]   ) {
          array_pop($epHisto[$k][$key]);
        }
        if (count($epHisto[$k][$key])>0 AND $epHisto[$k][$key][0]==" ") {
          array_shift($epHisto[$k][$key]);
        }
        //si l'array de chaque element n'est pas vide et correspond a la denomination des champs de $ep
        if (isset($ep[$k][$key]) and count($epHisto[$k][$key])>0) {
          //initialisation du tooltip
          $epHisto2[$k][$key]=' data-toggle="tooltip" title="';

          //pour chaque element, on ajoute le texte au tooltip
          foreach ($epHisto[$k][$key] as $ke => $va) {
            $epHisto2[$k][$key].=$va." | ";
          }
          //finalisation du tooltip
          $epHisto2[$k][$key].='"';
        }
      }
    }
    //on vient preparer le css dans le cas ou il n'y a pas d'historique
    foreach ($ep[$k] as $key => $value) {
      $epHisto2[$k][$key]=(isset($epHisto2[$k][$key]))?$epHisto2[$k][$key]:"";
    }




  }



  //Formatage des données
  $ep[$k]['dim1'] =!empty($ep[$k]['dim1'] )?number_format($ep[$k]['dim1'] , 3,'.', ' '):'';
  $ep[$k]['dim2'] =!empty($ep[$k]['dim2'] )?number_format($ep[$k]['dim2'] , 3,'.', ' '):'';
  $ep[$k]['dim3'] =!empty($ep[$k]['dim3'] )?number_format($ep[$k]['dim3'] , 3,'.', ' '):'';






}





//Changement de la page chargé selon le menu choisi
include $splitEp_View;
