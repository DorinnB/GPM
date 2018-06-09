<script type="text/javascript" src="js/splitGestionEp.js"></script>
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header" style="height:15%;">
      <h2 class="modal-title" style="text-align: center;">
        Specimen :&nbsp;<?= (($eprouvette['prefixe']=="")?"": $eprouvette['prefixe'].' - ').$eprouvette['nom_eprouvette']  ?><sup><?= ($eprouvette['retest']!=1)?$eprouvette['retest']:'' ?></sup>
        <button class="btn" data-toggle="modal" data-target="#login-modal" style="float:right;">
          <span class="glyphicon glyphicon-log-in"></span>
        </button>
      </h2>
    </div>
    <div class="modal-body" style="height:83%;">
      <div id="exTab1" class="container-fluid">
        <div class="row" style="height:100%;">
          <div class="col-sm-4" style="height:100%;">
            <ul  class="nav nav-pills nav-stacked" style="height:100%;">
              <li style="height:25%;display:none;" id="newTest">
                <a href="#1a" data-toggle="tab" style="line-height:7vh;"><p>NEW TEST</p></a>
              </li>
              <li style="height:25%;" id="document">
                <a href="#1d" data-toggle="tab" style="line-height:7vh;"><p>DOC</p></a>
              </li>
              <li style="height:25%;" id="retest"><a href="#2a" data-toggle="tab" style="line-height:7vh;">RETEST</a>
              </li>
              <li style="height:25%;display:none;" id="delete"><a href="#3a" data-toggle="tab" style="line-height:7vh;">DELETE</a>
              </li>

            </ul>
          </div>


          <div class="col-sm-8 carre" style="height:100%;">
            <div class="tab-content clearfix" style="height:100%;">

              <div class="tab-pane" id="1a" style="height:100%;">








<div class="row" style="height:50%; padding: 0px 10px; border-bottom: medium solid  #536E94;">
  <div class="col-md-10" id="Comments" style="display:none">

      <?php if ($eprouvette['c_checked']<=0 OR $eprouvette['checked']<=0) :  ?>
        <p style="color:red; font-size:150%; font-weight: bold;">
          <?= ($eprouvette['c_checked']<=0)?'Consigne Unchecked !':'' ?>
          <?= ($eprouvette['checked']<=0)?'<br/>Job Unchecked !':'' ?>
        </p>
        <input type="hidden" name="checker" value="0">
      <?php endif ?>

      <?php if ($checkTechSplit) :  ?>
        <p style="margin:5px 0px;">Please Read OneNote and those comments :</p>
        <textarea disabled class="gestionEpCommentaire"><?= $checkTechSplit['tbljob_commentaire'] ?></textarea>
      <?php endif ?>

  </div>
  <div class="col-md-10" id="notComments" style="padding-right:10px;">

    <form class="form-group" style="height:100%;" id="update_Aux">

        <label for="eprouvette_InOut_A">Date Start :</label>
        <input type="text" class="form-control <?= ($eprouvette['eprouvette_InOut_A']=="")?'newval':'' ?>" name="eprouvette_InOut_A" id="eprouvette_InOut_A" value="<?= ($eprouvette['eprouvette_InOut_A']=="")?date('Y-m-d'):$eprouvette['eprouvette_InOut_A'] ?>" <?= ($eprouvette['d_checked']>0)?"disabled":""  ?>>

        <label for="eprouvette_InOut_B">Date End :</label>
        <input type="text" class="form-control <?= ($eprouvette['eprouvette_InOut_B']=="")?'newval':'' ?>" name="eprouvette_InOut_B" id="eprouvette_InOut_B" value="<?= ($eprouvette['eprouvette_InOut_B']=="")?date('Y-m-d'):$eprouvette['eprouvette_InOut_B'] ?>" <?= ($eprouvette['d_checked']>0)?"disabled":""  ?>>

        <input type="hidden" id="checkAux" name="checkAux" value="<?=  ($eprouvette['d_checked']==0)?'save':(($eprouvette['d_checked']<0)?'valid':'cancel')	?>">
        <input type="hidden" id="idEprouvette" name="idEprouvette" value="<?=  $_GET['idEp'] ?>">
        <input type="hidden" name="iduser" value="<?=  $_COOKIE['id_user'] ?>">

    </form>
  </div>
  <div class="col-md-2" style="position: relative; height:100%;">
    <button type="button" id="flecheUp3" style="width:100%;background-color:dimgray;" title="Previous Specimen">
      <span class="glyphicon glyphicon-chevron-up"></span>
    </button>

    <button type="button" id="hideInfo" style="width:100%;background-color:dimgray;margin-top:10px;" title="Show/Hide Info-Task">
      <span id="hideInfo_icone" class="glyphicon glyphicon-eye-close"><br/>Info</span>
    </button>

    <div class="check<?=	$eprouvette['d_checked']	?>" id="d_checked" data-d_checked="<?= $eprouvette['d_checked'] ?>" style="position:absolute; bottom:0; background-color:<?=	($eprouvette['d_checked']==0)?'':(($eprouvette['d_checked']<=0)?'darkred':'darkgreen')	?>">
      <img type="image" src="img/<?=	($eprouvette['d_checked']==0)?'nextJob.png':(($eprouvette['d_checked']<0)?'save.png':'check.png')	?>" style="height:60px;padding:5px; margin: auto;" title="<?=	($eprouvette['d_checked']<=0)?'Check Aux ('.abs($eprouvette['d_checked']).')':$eprouvette['d_checked']	?>">
    </div>

  </div>
</div>


<div class="row" style="height:50%; padding: 0px 10px;">
  <form class="form-group" id="update_d_commentaire">
    <div class="col-md-10">
      <b style="float:left;">Lab Observation</b><br/>
      <textarea rows="4" class="form-control" id="d_commentaire" name="d_commentaire"  style="resize:none;background-color:#5B9BD5;border-width:0px; color:white;"><?= $eprouvette['d_commentaire'] ?></textarea>
    </div>
    <div class="col-md-2">
      <div id="flagQualite" data-flagQualite="<?= $eprouvette['flag_qualite'] ?>"  data-idepflagqualite="<?= $eprouvette['id_eprouvette'] ?>">
        <img type="image" src="img/warning_<?= $iconeFlagQualite ?>.png" style="height:60px; padding:5px 5px; margin: auto;" title="<?=	($eprouvette['flag_qualite']==0)?'Quality Flag':$eprouvette['flag_qualite']	?>">
      </div>
      <div id="save_d_commentaire" style="cursor:pointer;">
        <img type="image" src="img/save.png" style="height:60px; padding:5px 5px;display: block; margin: auto;">
      </div>
      <button type="button" id="flecheDown3" style="width:100%;background-color:dimgray;" title="Next Specimen">
        <span class="glyphicon glyphicon-chevron-down"></span>
      </button>
    </div>
    <input type="hidden" id="idEp" name="idEp" value="<?=  $_GET['idEp'] ?>">
    <input type="hidden" name="technicien" value="<?=  $_COOKIE['technicien'] ?>">
  </form>
</div>


              </div>

              <div class="tab-pane" id="1d" style="height:100%;">

                <figure style="display:inline-block;" class="document" data-type="FT">
                  <img  src="img/excel-FT.png" height="90" width="60"  alt="" />
                  <figcaption>FT (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'FT'); ?>)</figcaption>
                </figure>


                <a href="controller/createPrestart-controller.php?id_prestart=<?= $eprouvette['id_prestart'] ?>&id_ep=<?= $_GET['idEp'] ?>" style="display:inline-block;">
                  <figure>

                    <img  src="img/excel-Prestart.png" height="90" width="60" alt="" />
                    <figcaption>Prestart</figcaption>
                  </figure>
                </a>

                <figure style="display:inline-block;" class="document" data-type="Restart">
                  <img  src="img/excel-Restart.png" height="90" width="60"  alt="" />
                  <figcaption>Restart (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'Restart'); ?>)</figcaption>
                </figure>

                <figure style="display:inline-block;" id="doc_IRR" class="document" data-type="IRR">
                  <img  src="img/excel-IRR.png" height="90" width="60"  alt="" />
                  <figcaption>IRR (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'IRR'); ?>)</figcaption>
                </figure>

                <figure style="display:inline-block;" class="document" data-type="CAR">
                  <img  src="img/excel-Corrective.png" height="90" width="60"  alt="" />
                  <figcaption><acronym title="Corrective Action Request">CAR</acronym> (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'CAR'); ?>)</figcaption>
                </figure>

                <figure style="display:inline-block;" class="document" data-type="Other">
                  <img  src="img/doc.png" height="40" width="40"  alt="" />
                  <figcaption><acronym title="Other">Oth</acronym> (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'Other'); ?>)</figcaption>
                </figure>

                <a href="#" id="createXML" style="display:inline-block;">
                  <figure>
                    <img  src="img/xml.png" height="40" width="40" alt="" />
                    <figcaption>XML</figcaption>
                  </figure>
                </a>

                <div id="doc_ep" style=height:40%;>

                </div>

              </div>


              <div class="tab-pane" id="2a" style="height:100%;">
                <h3 style="height:100%;">
                  <div class="row">
                    <div class="col-md-12">
                      <p>Do you really want to retest this specimen ?</p>
                    </div>
                  </div>
                  <div class="row">
                    <button type="submit" class="btn btn-default" onclick="retest('<?= $eprouvette ['id_eprouvette'] ?>','<?= $eprouvette ['id_job'] ?>');">
                      Retest this specimen
                    </button>
                  </div>
                </h3>
              </div>
              <div class="tab-pane" id="3a" style="height:100%;">
                <h3 style="height:100%;">
                  <div class="row">
                    <div class="col-md-12">
                      <p>Do you really want to delete this restart ?</p>
                    </div>
                  </div>
                  <div class="row">
                    <button type="submit" class="btn btn-default" onclick="delTest('<?= $eprouvette ['id_eprouvette'] ?>','<?= $eprouvette ['id_job'] ?>');">
                      Delete this restart
                    </button>
                  </div>
                </h3>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>

      </div>
    </div>


  </div>
