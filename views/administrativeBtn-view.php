<div class="row" style="height:15%;">
  <div id="planningUsers" class="col-md-2" style="height:100%;">
    <a data-toggle="tooltip" title="Planning GPM" href="index.php?page=planningUsers" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
      <img type="image" src="img/calendar_yes.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
    </a>
    <div style="color:white; height:20%;">Planning</div>
  </div>
  <div id="badge" class="col-md-2" style="height:100%;">
    <a href="index.php?page=badge" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
      <img type="image" src="img/badge.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
    </a>
    <div style="color:white; height:20%;">Badge</div>
  </div>
</div>
<div class="row" style="height:15%;margin-top:10px;">

  <div id="" class="col-md-2" style="height:100%;">
    <a href="index.php?page=purchases" title="Purchases" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
      <img type="image" src="img/purchaserequest.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
    </a>
    <div style="color:white; height:20%;">POR</div>
  </div>
  <?php if($user->is_accounting()) : ?>
    <div id="" class="col-md-2" style="height:100%;">
      <a href="index.php?page=payables" title="Payables" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
        <img type="image" src="img/payable.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
      </a>
      <div style="color:white; height:20%;">Payables</div>
    </div>
    <div id="" class="col-md-2" style="height:100%;">
      <a href="index.php?page=UBR" title="UBR" class="btn btn-default btn-lg"style="width:100%; height:80%; padding:0px; border-radius:10px;">
        <img type="image" src="img/ubr.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
      </a>
      <div style="color:white; height:20%;">UBR</div>
    </div>
    <!--
    <div id="" class="col-md-2" style="height:100%;">
      <a href="index.php?page=quotations" title="Invoices" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
        <img type="image" src="img/quotation.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
      </a>
      <div style="color:white; height:20%;">Quotations</div>
    </div>
  -->
    <div id="" class="col-md-2" style="height:100%;">
      <a href="index.php?page=invoices" title="Invoices" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
        <img type="image" src="img/invoice.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
      </a>
      <div style="color:white; height:20%;">Invoices</div>
    </div>
    <div id="" class="col-md-2" style="height:100%;">
      <a href="index.php?page=backlog" title="backlog" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
        <img type="image" src="img/backlog.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
      </a>
      <div style="color:white; height:20%;">Backlog</div>
    </div>
  <?php endif ?>
</div>
<!--
<div id="badge2" class="col-md-2" style="height:10%;">
<a href="index.php?page=badge2" class="btn btn-default btn-lg" style="width:100%; height:80%; padding:0px; border-radius:10px;">
<img type="image" src="img/badge.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
</a>
</div>
-->
