<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<link href="css/quotation.css" rel="stylesheet">

<?php   include('controller/quotation-controller.php'); ?>

<div id="idcontact" style="display:none;"><?= $quotation['id_contact'] ?></div>

<div id="page-content-wrapper" style="height:100%">
  <div class="container-fluid">

    <form id="quotation" style="height:100%">
      <div class="row" style="height:20%; padding-top:10px;">
        <div class="col-md-6">
          <div class="row">
            <input type="hidden" name="id" value="<?= $quotation['id_quotation'] ?>">
            <H3>QUOTATION # <?= date('y', strtotime($quotation['creation_date'])).'-'.sprintf('%05d',$quotation['id_quotation']) ?></H3>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Titre</span>
                <input type="text" class="form-control" name="titre" value="<?= $quotation['title'] ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group">
              <div class="input-group">
                <span class="input-group-addon">RFQ</span>
                <input type="text" class="form-control" name="RFQ" value="<?= $quotation['rfq'] ?>">
              </div>
            </div>
            <div class="col-md-2 col-md-offset-1 form-group">
              <div class="input-group">
                <span class="input-group-addon">Version</span>
                <input type="text" class="form-control" name="ver" value="<?= ($quotation['ver']>0)?$quotation['ver']:0 ?>">
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Customer</span>
                <select id="ref_customer"  class="form-control" name="ref_customer">
                  <?php foreach ($ref_customer as $row): ?>
                    <option value="<?= $row['id_entreprise'] ?>" <?=  ($quotation['customer']== $row['id_entreprise'])?"selected":""  ?>><?= $row['id_entreprise'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Customer</span>
                <input type="text" class="form-control" id="nomclient" name="customer" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Contact&nbsp;&nbsp;&nbsp;</span>
                <select id="id_contact" name="id_contact" class="form-control">
                  <option>Please choose from above</option>
                </select>
              </div>
            </div>
          </div>
        </div>








        <div class="col-md-2">


          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Language</span>
              <input class="form-control" <?=	($quotation['lang']==1)?'checked':''	?> id="lang" name="lang" data-toggle="toggle" data-on="<img src='img/FlagUSA.png' style='max-width: auto;max-height: 20px;'>" data-off="<img src='img/FlagFrench.png' style='max-width: auto;max-height: 20px;'>" type="checkbox" onChange='showSave(); changeWarning();'>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Currency</span>
              <input class="form-control" <?=	($quotation['currency']==1)?'checked':''	?> id="currency" name="currency" data-toggle="toggle" data-on="<img src='img/dollar.png' style='max-width: auto;max-height: 20px;'>" data-off="<img src='img/euro.png' style='max-width: auto;max-height: 20px;'>" type="checkbox" onChange='showSave(); changeWarning();'>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group popover-markup" data-placement="bottom">
                <span class="input-group-addon">MRSAS Notes</span>
                <div class="head hide">MRSAS Notes</div>
                <div class="content hide">
                  <div class="form-group">
                    <textarea class="bubble_commentaire" name="c_commentaire" rows="10" cols="50"><?= $quotation['mrsasComments'] ?></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
<!--
        <div class="col-md-1">
          <div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Internationalization">
            <p class="title">
              <span class="name">Language :</span>
            </p>
            <p class="title">
              <span class="name">Currency :</span>
            </p>
          </div>
        </div>
        <div class="col-md-1">
          <div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Internationalization">
            <p class="title">
              <span class="value">
                <input <?=	($quotation['lang']==1)?'checked':''	?> id="lang" name="lang" data-toggle="toggle" data-on="<img src='img/FlagUSA.png' style='max-width: auto;max-height: 20px;'>" data-off="<img src='img/FlagFrench.png' style='max-width: auto;max-height: 20px;'>" type="checkbox" onChange='showSave(); changeWarning();'>
              </span>
            </p>
            <p class="title">
              <span class="value">
                <input <?=	($quotation['currency']==1)?'checked':''	?> id="currency" name="currency" data-toggle="toggle" data-on="<img src='img/dollar.png' style='max-width: auto;max-height: 20px;'>" data-off="<img src='img/euro.png' style='max-width: auto;max-height: 20px;'>" type="checkbox" onChange='showSave(); changeWarning();'>
              </span>
            </p>
          </div>
        </div>
-->
        <div class="col-md-2">
          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <input type="hidden" id="id_preparer" name="id_preparer" value="<?= $quotation['id_preparer']  ?>">
                <span class="input-group-btn" id="changePrep">
                  <button class="btn btn-primary" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true">
                  </span> Preparator</button>
                </span>
                <input type="text" class="form-control <?= (($quotation['id_preparer']>0)?'checkOK':'checkNOK') ?>" id="preparer" value="<?= $quotation['preparer']  ?>" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <input type="hidden" id="id_checker" name="id_checker" value="<?= $quotation['id_checker']  ?>">
                <span class="input-group-btn" id="changeCheck">
                  <button class="btn btn-primary" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true">
                  </span> Checker&nbsp;&nbsp;&nbsp;&nbsp;</button>
                </span>
                <input type="text" class="form-control <?= (($quotation['id_checker']>0)?'checkOK':'checkNOK') ?>" id="checker" value="<?= $quotation['checker']  ?>" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 form-group">
              <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="text" class="form-control" id="quotation_date" name="quotation_date" value="<?= $quotation['quotation_date'] ?>">
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="row" id="pricingList2" style="height:70%; overflow-y:auto; overflow-x:hidden; padding: 5px 0; border: 2px solid black;">
        <ul id="sortable">
          <?php foreach ($quotationlist as $key => $value) : ?>
            <?php if ($value['type']=="title") : ?>
              <li id="quotationlist_<?= $key ?>" class="ui-state-default">
                <div class="row">
                  <div class="col-md-1 handle" style="width:5%;">
                    <span class="glyphicon glyphicon-move"></span>
                    <input type="hidden" class="form-control newtype" name="quotationlist_<?= $key ?>_type" value="title">
                  </div>
                  <div class="col-md-7 description">
                    <div class="input-group">
                      <span class="input-group-addon">Titre</span>
                      <input type="text" class="form-control newTitle" name="quotationlist_<?= $key ?>_description" value="<?= $value['description'] ?>"></textarea>
                    </div>
                  </div>
                  <div class="col-md-1 col-md-offset-4" style="color:red; width:2%;">
                    <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
                  </div>
                </div>
              </li>
            <?php elseif ($value['type']=="comment") : ?>
              <li id="quotationlist_<?= $key ?>" class="ui-state-default">
                <div class="row">
                  <div class="col-md-1 handle" style="width:5%;">
                    <span class="glyphicon glyphicon-move"></span>
                    <input type="hidden" class="form-control newtype" name="quotationlist_<?= $key ?>_type" value="comment">
                  </div>
                  <div class="col-md-7 comments">
                    <div class="input-group">
                      <span class="input-group-addon">Comment</span>
                      <textarea class="form-control comments" rows="2" name="quotationlist_<?= $key ?>_comments"><?= $value['comments'] ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-1 col-md-offset-4" style="color:red; width:2%;">
                    <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
                  </div>
                </div>
              </li>
            <?php elseif ($value['type']=="code") : ?>
              <li id="quotationlist_<?= $key ?>" class="ui-state-default">
                <div class="row">
                  <div class="col-md-1 handle" style="width:5%;">
                    <span class="glyphicon glyphicon-move"></span>
                    <input type="hidden" class="form-control newtype" name="quotationlist_<?= $key ?>_type" value="code">
                  </div>
                  <div class="col-md-1">
                    <div class="input-group">
                      <span class="input-group-addon">Code</span>
                      <input type="text" class="form-control prodCode" name="quotationlist_<?= $key ?>_prodCode" value="<?= $value['prodCode'] ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">Description</span>
                      <input type="text" class="form-control description" name="quotationlist_<?= $key ?>_description" value="<?= $value['description'] ?>">
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="input-group">
                      <span class="input-group-addon">Unit</span>
                      <input type="text" class="form-control unit" name="quotationlist_<?= $key ?>_unit" value="<?= $value['unit'] ?>">
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="input-group">
                      <span class="input-group-addon">Price</span>
                      <input type="text" class="form-control price" name="quotationlist_<?= $key ?>_price" value="<?= $value['price'] ?>">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">
                      <span class="input-group-addon">Total</span>
                      <input type="text" class="form-control right total" disabled >
                    </div>
                  </div>
                  <div class="col-md-1" style="color:red; width:2%;">
                    <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-1" style="width:5%;">
                  </div>
                  <div class="col-md-7">
                    <div class="input-group">
                      <span class="input-group-addon">Comment</span>
                      <textarea class="form-control comments" rows="2" name="quotationlist_<?= $key ?>_comments"><?= $value['comments'] ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-1" style="color:blue;">
                    <span class="glyphicon glyphicon-list-alt" style="margin-top:10px;" data-toggle="modal" data-target="#HourlyChargeModal" onClick="$('#hourlycharge').val($(this).parents('li').attr('id'));" ></span>
                  </div>
                </div>
              </li>
            <?php elseif ($value['type']=="subTotal") : ?>
              <li id="quotationlist_<?= $key ?>" class="ui-state-default">
                <div class="row">
                  <div class="col-md-1 handle" style="width:5%;">
                    <span class="glyphicon glyphicon-move"></span>
                    <input type="hidden" class="form-control newtype" name="quotationlist_<?= $key ?>_type" value="subTotal">
                  </div>
                  <div class="col-md-7">
                  </div>
                  <div class="col-md-2">
                    <input type="text" class="form-control newTitle" value="ESTIMATED SUB-TOTAL">
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">
                      <span class="input-group-addon">Total</span>
                      <input type="text" class="form-control right total subTotal decimal2" disabled >
                    </div>
                  </div>
                  <div class="col-md-1" style="color:red; width:2%;">
                    <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
                  </div>
                </div>
              </li>
            <?php else : ?>
              ERROR !
            <?php endif ?>
          <?php endforeach ?>
        </ul>
      </div>

      <div class="row" style="height:10%; overflow-y:auto; padding-top: 5px;">
        <div class="col-md-1" style="width:5%;">
        </div>

        <div class="col-md-7 comments">
          <div class="input-group">
            <span class="input-group-addon">Additional Comment</span>
            <textarea class="form-control comments" rows="2" name="endComments"><?= $quotation['endComments'] ?></textarea>
          </div>
        </div>

        <div class="col-md-2">
          <button type="button" class="btn btn-default btn-lg col-md-5" onClick="addNewTitle()">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Title
          </button>
          <button type="button" class="btn btn-default btn-lg col-md-5 col-md-offset-1" onClick="addNewComment()">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Comment
          </button>
          <button type="button" class="btn btn-default btn-lg col-md-5" data-toggle="modal" data-target="#NewCodeModal">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Code
          </button>
          <button type="button" class="btn btn-default btn-lg col-md-5 col-md-offset-1" onClick="addNewSubTotal()">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> SubTotal
          </button>
        </div>

        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Total</span>
            <input type="text" class="form-control right" disabled id="totalQuotation" >
          </div>
          <a href="controller/createQuotation-controller.php?id_quotation=<?=	$_GET['id_quotation']	?>" class="btn btn-primary btn-lg col-md-5" id="printQuotation" style="width:100%;">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> PRINT
          </a>
          <button type="button" class="btn btn-warning btn-lg col-md-5" id="saveQuotation" style="width:100%; display:none;">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> SAVE
          </button>
        </div>

      </div>



    </div>
  </form>
</div>
</div>

<script type="text/javascript" src="js/quotation.js"></script>



<div id="NewCodeModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:80%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New Code</h4>
        <div class="alert alert-warning alert-dismissible" style="text-align: center;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Warning!</strong><br/>
          The language is defined as <img id="warning_lang" src='<?=	($quotation['lang']==1)?'img/FlagUSA.png':'img/FlagFrench.png'	?>' style='max-width: auto;max-height: 20px;'> and
          the currency is defined as <img id="warning_currency" src='<?=	($quotation['currency']==1)?'img/dollar.png':'img/euro.png'	?>' style='max-width: auto;max-height: 20px;'>.
        </div>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" onsubmit="addNewCode();return false;">
          <table id="table_pricinglists" class="table table-condensed table-hover table-bordered dataTable" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th><acronym title="Check">Check</acronym></th>
                <th><acronym title="prodCode - OpnCode">Code</acronym></th>
                <th><acronym title="Associated Split">Split</acronym></th>
                <th><acronym title="Pricing List">Pricing List</acronym></th>
                <th><acronym title="Pricing List">Pricing List [USA]</acronym></th>
                <th><acronym title="Pricing List FR">Pricing List [FR]</acronym></th>
                <th><acronym title="USD">USD</acronym></th>
                <th><acronym title="EURO">EURO</acronym></th>
                <th><acronym title="Type 0=comments, 1=nbtest, 2=hrsup">Type</acronym></th>
                <th>Actif</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th><acronym title="Check">Check</acronym></th>
                <th><acronym title="prodCode - OpnCode">Code</acronym></th>
                <th><acronym title="Associated Split">Split</acronym></th>
                <th><acronym title="pricingList">pricingList</acronym></th>
                <th><acronym title="pricingListFR">pricingListUS</acronym></th>
                <th><acronym title="pricingListFR">pricingListFR</acronym></th>
                <th><acronym title="USD">USD</acronym></th>
                <th><acronym title="EURO">EURO</acronym></th>
                <th><acronym title="Type 0=comments, 1=nbtest, 2=hrsup">Type</acronym></th>
                <th>Actif</th>
              </tr>
            </tfoot>
          </table>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<div id="HourlyChargeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hourly Charge Calculation</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" onsubmit="addHourlyCharge();return false;">
          <input type="hidden" id="hourlycharge">
          <table id="table_hourlycharge" class="table table-condensed table-hover table-bordered dataTable" cellspacing="0">
            <thead>
              <tr>
                <th><acronym title="Specimen Number">Nb</acronym></th>
                <th><acronym title="Estimated Cycle">Est. Cy.</acronym></th>
                <th><acronym title="Frequency">F (Hz)</acronym></th>
                <th><acronym title="Switch to Load Cycle">STL Cy.</acronym></th>
                <th><acronym title="Switch to Load Frequency">F(Hz)</acronym></th>
                <th><acronym title="Test Time (hours)">Test (Hr)</acronym></th>
                <th><acronym title="Hourly Charge">Add (Hr)</acronym></th>
              </tr>
            </thead>
            <tbody>
              <?php for ($i=0; $i < 10; $i++) : ?>
                <tr>
                  <td contenteditable class="nb" onkeyup="calcHourlyCharge();"></td>
                  <td contenteditable class="cy" onkeyup="calcHourlyCharge();"></td>
                  <td contenteditable class="freq" onkeyup="calcHourlyCharge();"></td>
                  <td contenteditable class="stl" onkeyup="calcHourlyCharge();"></td>
                  <td contenteditable class="fstl" onkeyup="calcHourlyCharge();"></td>
                  <td class="total right"></td>
                  <td class="hrsup right"></td>
                </tr>
              <?php endfor ?>

            </tbody>
          </table>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Add Comments & Value</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<li id="newTitle" class="ui-state-default" style="display:none;">
  <div class="row">
    <div class="col-md-1 handle" style="width:5%;">
      <span class="glyphicon glyphicon-move"></span>
      <input type="hidden" class="form-control newtype" id="type" value="title">
    </div>
    <div class="col-md-7 description">
      <div class="input-group">
        <span class="input-group-addon">Titre</span>
        <input type="text" class="form-control newTitle" id="description">
      </div>
    </div>
    <div class="col-md-1 col-md-offset-4" style="color:red; width:2%;">
      <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
    </div>
  </div>
</li>

<li id="newComment" class="ui-state-default" style="display:none;">
  <div class="row">
    <div class="col-md-1 handle" style="width:5%;">
      <span class="glyphicon glyphicon-move"></span>
      <input type="hidden" class="form-control newtype" id="type" value="comment">
    </div>
    <div class="col-md-7 comments">
      <div class="input-group">
        <span class="input-group-addon">Comment</span>
        <textarea class="form-control" rows="2" id="comments"></textarea>
      </div>
    </div>
    <div class="col-md-1 col-md-offset-4" style="color:red; width:2%;">
      <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
    </div>
  </div>
</li>

<li id="newCode" class="ui-state-default" style="display:none;">
  <div class="row">
    <div class="col-md-1 handle" style="width:5%;">
      <span class="glyphicon glyphicon-move"></span>
      <input type="hidden" class="form-control newtype" id="type" value="code">
    </div>
    <div class="col-md-1">
      <div class="input-group">
        <span class="input-group-addon">Code</span>
        <input type="text" class="form-control prodCode" >
      </div>
    </div>
    <div class="col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Description</span>
        <input type="text" class="form-control description" >
      </div>
    </div>
    <div class="col-md-1">
      <div class="input-group">
        <span class="input-group-addon">Unit</span>
        <input type="text" class="form-control unit" >
      </div>
    </div>
    <div class="col-md-1">
      <div class="input-group">
        <span class="input-group-addon">Price</span>
        <input type="text" class="form-control price" >
      </div>
    </div>
    <div class="col-md-2">
      <div class="input-group">
        <span class="input-group-addon">Total</span>
        <input type="text" class="form-control right total" disabled >
      </div>
    </div>
    <div class="col-md-1" style="color:red; width:2%;">
      <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
    </div>
  </div>

  <div class="row">
    <div class="col-md-1" style="width:5%;">
    </div>
    <div class="col-md-7">
      <div class="input-group">
        <span class="input-group-addon">Comment</span>
        <textarea class="form-control comments" rows="2"></textarea>
      </div>
    </div>
    <div class="col-md-1" style="color:blue;">
      <span class="glyphicon glyphicon-list-alt" style="margin-top:10px;" data-toggle="modal" data-target="#HourlyChargeModal" onClick="$('#hourlycharge').val($(this).parents('li').attr('id'));" ></span>
    </div>
  </div>
</li>

<li id="newSubTotal" class="ui-state-default" style="display:none;">
  <div class="row">
    <div class="col-md-1 handle" style="width:5%;">
      <span class="glyphicon glyphicon-move"></span>
      <input type="hidden" class="form-control newtype" id="type" value="subTotal">
    </div>
    <div class="col-md-7">
    </div>
    <div class="col-md-2">
      <input type="text" class="form-control newTitle" value="ESTIMATED SUB-TOTAL">
    </div>
    <div class="col-md-2">
      <div class="input-group">
        <span class="input-group-addon">Total</span>
        <input type="text" class="form-control right total subTotal" disabled >
      </div>
    </div>
    <div class="col-md-1" style="color:red; width:2%;">
      <span class="glyphicon glyphicon-trash" style="margin-top:10px;" onClick="$(this).parents('li').remove(); showSave(); calcTotal();"></span>
    </div>
  </div>
</li>


<link href="lib/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="lib/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>
<?php
require('views/login-view.php');
?>
