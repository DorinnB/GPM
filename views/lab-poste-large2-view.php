<div class="lab">
  <?php if (isset($poste[$n_poste])): ?>


    <div class="col-md-12 machineView" style="border:1px solid black; margin:<?= (8-$nb)*7+5 ?>px 0px;background-color:<?= $poste[$n_poste]['background-color'] ?>;color:<?= $poste[$n_poste]['color'] ?>;display:<?=  (($poste[$n_poste]['currentBlock_temp']=='Send' or $poste[$n_poste]['currentBlock_temp']=='send') AND $poste[$n_poste]['etape']!=53)?'none':'block'  ?>;">
      <div class="col-md-10" style="padding:0px;">
        <div class="col-md-3 toForCast" style="padding:0px;">
          <b><?= $poste[$n_poste]['machine']  ?></b>
        </div>
        <div class="col-md-9" style="white-space:nowrap;">
          <?= $poste[$n_poste]['currentBlock_temp']  ?>
        </div>
        <div class="col-md-12 machineNoClick" style="padding:0px;">
          <a href="index.php?page=split&amp;id_tbljob=<?=  $poste[$n_poste]['id_job'] ?>">
            <b>Job:</b> <?= $poste[$n_poste]['customer'].' '.$poste[$n_poste]['job'].' '. $poste[$n_poste]['split']  ?><i style="font-size:x-small;"> (<?= $poste[$n_poste]['statut']  ?>)</i><br/>
            <b>T°:</b> <?= (!empty($poste[$n_poste]['c_temperature'])?number_format($poste[$n_poste]['c_temperature'], 0,'.', ' '):'').(!empty($poste[$n_poste]['temperature_temp'])?" [".number_format($poste[$n_poste]['temperature_temp'], 1,'.', ' ')."]":'')   ?></br>
            <?= isset($poste[$n_poste]['Cycle_final_temp'])?$poste[$n_poste]['Cycle_final_temp'].'&nbsp;<b>cy.</b>&nbsp;('.$poste[$n_poste]['tempsRestant'].'h<b>&nbsp;left</b>)':''  ?>
          </a>
        </div>
      </div>
      <div class="col-md-2" style="padding-right:0px;">
        <img src="img/<?= $poste[$n_poste]['icone_file']  ?>" style="width: auto;max-height: 20px; margin-top:5px;"><br/>
        <img src="img/medal_<?= $poste[$n_poste]['prio_machine_forecast']  ?>" style="width: auto;max-height: 20px; margin-top:10px;">
      </div>
    </div>


    <div class="col-md-12 foreCastView" style="border:1px solid black; margin:<?= (8-$nb)*7+5 ?>px 0px;background-color:#536E94;color:white;display:<?=  (($poste[$n_poste]['currentBlock_temp']=='Send' or $poste[$n_poste]['currentBlock_temp']=='send') AND $poste[$n_poste]['etape']!=53)?'block':'none'  ?>;">
      <div class="col-md-10" style="padding:0px;">
        <div class="col-md-3 toMachine" style="padding:0px;">
          <b><?= $poste[$n_poste]['machine']  ?></b>
        </div>
        <div class="col-md-9" style="white-space:nowrap;">
          (<?= $poste[$n_poste]['statut']  ?>)
        </div>
        <div class="col-md-12 machineNoClick" style="padding:0px;">
          <?php if ($poste[$n_poste]['planned']['job']): ?>
            <i style="font-size:x-small;">Planned: <?= $poste[$n_poste]['planned']['job']  ?></i>
          <?php endif ?>
          <textarea class="commentaire" data-id="<?= $poste[$n_poste]['id_machine']  ?>" rows=1 style="resize: none; background-color:#536E94; width:100%; border:0px;"><?= $poste[$n_poste]['texte_machine_forecast'] ?></textarea>
        </div>
      </div>
        <div class="col-md-2" style="padding-right:0px;">
          <img id="icone_<?= $poste[$n_poste]['id_machine']   ?>" src="img/<?= $poste[$n_poste]['icone_file']  ?>" class="icone" data-id="<?= $poste[$n_poste]['id_machine']  ?>" style="width: auto;max-height: 30px; margin-top:5px;">
          <img id="priorite_<?= $poste[$n_poste]['id_machine']   ?>" src="img/medal_<?= $poste[$n_poste]['prio_machine_forecast']  ?>" class="priorite" data-id="<?= $poste[$n_poste]['id_machine']  ?>" style="width: auto;max-height: 30px; margin-top:10px;">
        </div>
    </div>

  <?php endif ?>
</div>
