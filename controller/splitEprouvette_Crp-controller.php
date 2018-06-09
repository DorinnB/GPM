<?php



//Appel du model
$ep=$oEprouvettes->getAllEprouvettes();

// Rendre votre modèle accessible
include 'models/eprouvette-model.php';



// Definition des consignes et unites
//ainsi que de l'axe des ordonnées du Chart
if (($split['c_type_1']=="R") OR ($split['c_type_1']=="A")) {
  $split['cons1']=$split['c_type_1'];
  $split['ChartCons1']='';
  $split['ChartCons2']='chartNiveau';
  $split['ChartTitreCons']=$split['c_type_2'].' ('.$split['c_unite'].')';
}
else {
  $split['cons1']=$split['c_type_1'].' ('.$split['c_unite'].')';
  $split['ChartCons1']='chartNiveau';
  $split['ChartCons2']='';
  $split['ChartTitreCons']=$split['c_type_1'].' ('.$split['c_unite'].')';
}
  $split['ChartCons3']='';
    $split['ChartCons4']='';
      $split['ChartCons5']='';



if (($split['c_type_2']=="R") OR ($split['c_type_2']=="A") OR ($split['c_type_2']=="Ramp (s)")) {
  $split['cons2']=$split['c_type_2'];
}
else {
  $split['cons2']=$split['c_type_2'].' '.$split['c_unite'];
}

//declaration variable calcul temps essai restant
$estimatedTimeLeft=0;
$unestimatedTestLeft=0;
$shortTest=0;

//declaration des variables calculées
for($k=0;$k < count($ep);$k++)	{
  $oEp = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $ep[$k]=$oEp->getTest();
  $workflow=$oEp->getWorkflow();

  $tempCorrected=$oEp->getTempCorrected();

  $ep[$k]['comm']=(isset($workflow['comm']))?$workflow['comm']:"";

  $cycle_estimeAVG=$oEp->getEstimatedCycle();




  //temporaire pour les cas sans c_checked (mettre defaut 0 ds mysql)
  $ep[$k]['c_checked']=($ep[$k]['c_checked']=="")?0:$ep[$k]['c_checked'];
  //temporaire pour les cas sans flag_qualite (mettre defaut 0 ds mysql)
  $ep[$k]['flag_qualite']=($ep[$k]['flag_qualite']=="")?0:$ep[$k]['flag_qualite'];



    //disponibilite eprouvette
    if ($ep[$k]['d_checked']>0) {
      $ep[$k]['dispo']='6';
      $ep[$k]['dispoText']='Completed';
    }
    elseif (strtolower($ep[$k]['currentBlock'])=='send') {
      $ep[$k]['dispo']='5';
      $ep[$k]['dispoText']='Data UnChecked';
    }
    elseif ($ep[$k]['n_fichier']>0) {
      $ep[$k]['dispo']='4';
      $ep[$k]['dispoText']='Running';
    }
    else if (isset($workflow['ST']) & $workflow['ST']>0) {
      $ep[$k]['dispo']='0';
      $ep[$k]['dispoText']='Awaiting Specimen';
    }
    elseif (isset($workflow['local']) & $workflow['local']>0) {
      $ep[$k]['dispo']='1';
      $ep[$k]['dispoText']='Awaiting Previous Split';
    }
    elseif ($ep[$k]['c_checked']<=0) {
      $ep[$k]['dispo']='2';
      $ep[$k]['dispoText']='Consigne UnChecked';
    }
    elseif ($ep[$k]['c_checked']>0) {
      $ep[$k]['dispo']='3';
      $ep[$k]['dispoText']='Ready to Test';
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

    $dimDenomination=$oEp->dimensions($ep[$k]['id_dessin_type'], $ep[$k]['dim1'], $ep[$k]['dim2'], $ep[$k]['dim3']);
    //suppression des dimensions null
    foreach ($dimDenomination as $index => $data) {
      if ($data=='') {
        unset($dimDenomination[$index]);
      }
    }




    //HISTORIQUE
    //recup de l'historique des modifications
    $histoEp[$k]=$oHisto->getHistoEprouvette($ep[$k]['id_eprouvette']);
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






    $oEp->niveaumaxmin($ep[$k]['c_1_type'], $ep[$k]['c_2_type'],$ep[$k]['c_type_1_val'], $ep[$k]['c_type_2_val']);

    $ep[$k]['max']="";
    $ep[$k]['min']="";


    $maxcell=0;
    //max min control
    if ($ep[$k]['c_unite']=="MPa") {
      if ($oEp->area()>0) {
        $ep[$k]['max']=round($ep[$k]['c_type_1_val']*$oEp->area()/1000,3);
        $ep[$k]['min']=round($ep[$k]['c_type_3_val']*$oEp->area()/1000,3);
        $maxcell=max(abs($ep[$k]['max']),abs($ep[$k]['min']));
      }
    }
    elseif ($ep[$k]['c_unite']=="kN") {
      $ep[$k]['max']=round($ep[$k]['c_type_1_val'],3);
      $ep[$k]['min']=round($ep[$k]['c_type_3_val'],3);
      $maxcell=max(abs($ep[$k]['max']),abs($ep[$k]['min']));
    }
    elseif ($ep[$k]['c_unite']=="%") {
      $ep[$k]['max']=round($ep[$k]['c_type_1_val'],3);
      $ep[$k]['min']=round($ep[$k]['c_type_2_val'],3);
      $maxcell=max(abs($ep[$k]['max']),abs($ep[$k]['min']))*$ep[$k]['young']*$oEp->area()/100;
    }
    else  {
      $ep[$k]['max']="?";
      $ep[$k]['min']="?";
    }

    //check max cell used
    if ($maxcell>85) {
      echo '<script>
      $("#load250").addClass( "load250" );
      </script>';
    }
    elseif ($maxcell>8) {
      echo '<script>
      $("#load100").addClass( "load100" );
      </script>';
    }
    elseif ($maxcell>0) {
      echo '<script>
      $("#load10").addClass( "load10" );
      </script>';
    }



  }

$ep[$k]['Cycle_final']=$ep[$k]['Cycle_final']/3600;

  //Formatage des données

  $ep[$k]['Cycle_min_nonAtteint']=(!empty($ep[$k]['Cycle_min']) AND $ep[$k]['Cycle_final']<$ep[$k]['Cycle_min'] AND strtolower($ep[$k]['currentBlock'])=='send' )?'flagMini':'a';

  $ep[$k]['cycle_estimeCSS']=!empty($ep[$k]['cycle_estime'])?$ep[$k]['cycle_estime']:((!empty($cycle_estimeAVG['cycle_estime']) AND $ep[$k]['d_checked']<=0)?'estimated':'');
  $ep[$k]['cycle_estime']=!empty($ep[$k]['cycle_estime'])?$ep[$k]['cycle_estime']:((!empty($cycle_estimeAVG['cycle_estime']) AND $ep[$k]['d_checked']<=0)?$cycle_estimeAVG['cycle_estime']:'');
  $ep[$k]['Cycle_STL']=!empty($ep[$k]['Cycle_STL'])?$ep[$k]['Cycle_STL']:'';
  $ep[$k]['Cycle_final']=!empty($ep[$k]['Cycle_final'])?$ep[$k]['Cycle_final']:'';


  $ep[$k]['dilatation']=!empty($ep[$k]['dilatation'])?($ep[$k]['dilatation']-1)*100:'';

  $ep[$k]['temps_essaisCSS']=(!empty($ep[$k]['temps_essais'] AND is_numeric($ep[$k]['temps_essais'])))?'':'estimated';
  $ep[$k]['temps_essais']=(!empty($ep[$k]['temps_essais'] AND is_numeric($ep[$k]['temps_essais'])))?number_format($ep[$k]['temps_essais'], 1,'.', ' '):$ep[$k]['temps_essais'];

  $ep[$k]['CheckValue_Fracture']=(strpos($ep[$k]['Fracture'], 'OX') !==false OR strpos($ep[$k]['Fracture'], 'IX') !==false OR strpos(strtolower($ep[$k]['Fracture']), 'gage') !==false OR strpos(strtoupper($ep[$k]['Fracture']), 'NR') !==false)?'':'checkValue_actif';

  $ep[$k]['c_type_1_deci']=0;
  $ep[$k]['c_type_2_deci']=0;
  if ($ep[$k]['c_unite']=='MPa') {
    $ep[$k]['max']=(!empty($ep[$k]['max']) || ($ep[$k]['max']=="0"))?$ep[$k]['max']:'';
    $ep[$k]['min']=(!empty($ep[$k]['min']) || ($ep[$k]['min']=="0"))?$ep[$k]['min']:'';
    $ep[$k]['c_type_1_deci']=2;
    $ep[$k]['c_type_2_deci']=2;
  }
  elseif ($ep[$k]['c_unite']=='kN') {
    $ep[$k]['max']=(!empty($ep[$k]['max']) || ($ep[$k]['max']=="0"))?$ep[$k]['max']:'';
    $ep[$k]['min']=(!empty($ep[$k]['min']) || ($ep[$k]['min']=="0"))?$ep[$k]['min']:'';
    $ep[$k]['c_type_1_deci']=3;
    $ep[$k]['c_type_2_deci']=2;
  }
  elseif ($ep[$k]['c_unite']=='%') {
    $ep[$k]['max']=(!empty($ep[$k]['max']) || ($ep[$k]['max']=="0"))?$ep[$k]['max']:'';
    $ep[$k]['min']=(!empty($ep[$k]['min']) || ($ep[$k]['min']=="0"))?$ep[$k]['min']:'';
    $ep[$k]['c_type_1_deci']=3;
    $ep[$k]['c_type_2_deci']=3;
  }



  //var_dump($ep[$k]);


  //calcul cumulé du temps d'essai restant
  //calcul du temps estimé pour chaque essai
  $EstimatedTestTime=0;
  $modulo=0;

  if ($ep[$k]['Cycle_final']>0) {  //si essai demarré, on calcul le temps estimé restant (mini 0 pour pas mettre un temps negatif). 24h mini. Si l'essai s'arrete dans la journée on considere qu'on n'aura pas le temps de redemarrer
    if ($ep[$k]['cycle_estime']>0 AND $ep[$k]['c_frequence']>0) {
      if ($ep[$k]['c_cycle_STL']=="" OR $ep[$k]['cycle_estime']-$ep[$k]['Cycle_final'] < $ep[$k]['c_cycle_STL']) {
        $EstimatedTestTime=(max($ep[$k]['cycle_estime']-$ep[$k]['Cycle_final'],0)/$ep[$k]['c_frequence'])/3600;
      }
      elseif ($ep[$k]['cycle_estime'] > $ep[$k]['c_cycle_STL'] AND $ep[$k]['c_frequence_STL']>0) {
        $EstimatedTestTime=($ep[$k]['c_cycle_STL']/$ep[$k]['c_frequence']+(max($ep[$k]['cycle_estime']-$ep[$k]['Cycle_final'],0)-$ep[$k]['c_cycle_STL'])/$ep[$k]['c_frequence_STL'])/3600;
      }
      else {
        $unestimatedTestLeft+=1;  //cas particulier ou l'on n'arrive pas a calculer malgré demarrage
      }
    }

    if ($EstimatedTestTime>0) { //minimum 24h restant d'occupation machine
      $modulo=24;
    }



  }
  else {

    if ($ep[$k]['cycle_estime']>0 AND $ep[$k]['c_frequence']>0) {
      if ($ep[$k]['c_cycle_STL']=="" OR $ep[$k]['cycle_estime'] < $ep[$k]['c_cycle_STL']) {

        $EstimatedTestTime=($ep[$k]['cycle_estime']/$ep[$k]['c_frequence'])/3600;
      }
      elseif ($ep[$k]['cycle_estime'] > $ep[$k]['c_cycle_STL'] AND $ep[$k]['c_frequence_STL']>0) {
        $EstimatedTestTime=($ep[$k]['c_cycle_STL']/$ep[$k]['c_frequence']+($ep[$k]['cycle_estime']-$ep[$k]['c_cycle_STL'])/$ep[$k]['c_frequence_STL'])/3600;
      }
      else {
        $unestimatedTestLeft+=1;
      }
    }
    elseif ($ep[$k]['n_fichier']>0) {
      $EstimatedTestTime+=0;
    }
    else {
      $unestimatedTestLeft+=1;
    }


    //calcul estimé du temps "occupation machine" pour chaque essai
    if ($EstimatedTestTime>0) {

      $EstimatedTestTime+=.5; //ajout temps preparation.

      if ($ep[$k]['c_temp']>50) {
        $EstimatedTestTime+=4; //ajout temps chauffage.
      }

      $modulo=$EstimatedTestTime%24;

      if ($modulo<5) { //cumul de l'essai avec le suivant
        if ($shortTest=0) {
          $shortTest=$modulo;
        }
        else {
          $modulo=24-$shortTest;
          $shortTest=0;
        }
      }
      elseif ($modulo<24) {  //mini 24h (entre maintenant et le prochain demarrage)
        $modulo=24;
      }
    }
  }
  $estimatedTimeLeft+=(intval(floor($EstimatedTestTime/24))+$modulo/24)*24;

  //echo intval(floor($EstimatedTestTime/24))+$modulo;
  echo '
  <script>
  $("#estimatedDayLeft").html("'.(number_format($estimatedTimeLeft/24, 1,'.', ' ')).(($unestimatedTestLeft==0)?"":' + '.$unestimatedTestLeft.' test(s)').'");
  </script>
  ';


}


include $splitEp_View;
