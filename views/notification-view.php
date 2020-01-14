
<div class="modal-dialog modal-lg">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header" style="height:10%;">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title col-md-3 text-left">Notification</h3>
      <?php include('../controller/badgeAccess-controller.php'); ?>
    </div>
    <div class="modal-body" style="height:90%;">
      <div id="exTab2" class="container-fluid">
        <div class="col-md-5" style="height:100%; background-color:inherit; border:0px;">
          <ul class="nav nav-tabs nav-pills" style="height:42px;">
            <li class="active"><a data-toggle="tab" href="#inbox">Inbox</a></li>
            <li><a data-toggle="tab" href="#outbox">Outbox</a></li>
            <li><a data-toggle="tab" href="#historyI"><acronym title="History Inbox">Hist.In</acronym></a></li>
            <li><a data-toggle="tab" href="#historyO"><acronym title="History Outbox">Hist.Out</acronym></a></li>
          </ul>
          <div class="tab-content" style="height:calc(100% - 42px);overflow:auto;">
            <div id="inbox" class="tab-pane fade in active">
              <ul class="nav nav-pills nav-stacked">
                <li><a data-toggle="pill" href="#new">New</a></li>
                <?php foreach ($oNotification->getAllNotificationTo() as $key => $value) :  ?>
                  <li>
                    <a data-toggle="pill" href="#received_<?= $value['id_notification'] ?>">
                      <p>
                        <span class="from">From : <?= $value['transmitter'] ?></span>
                        <?= ($value['notification_state']==2)?'<img type="image" src="img/notification.png" style="max-width:100%; max-height:1.5em;" />':'&nbsp;' ?>
                        <span class="datetime"><?= date("Y-m-d", strtotime($value['notification_date'])) ?></span>
                      </p>
                      <p class="subject"><?= $value['subject'] ?></p>
                    </a>
                  </li>
                <?php endforeach  ?>
              </ul>
            </div>
            <div id="outbox" class="tab-pane fade">
              <ul class="nav nav-pills nav-stacked">
                <li><a data-toggle="pill" href="#new">New</a></li>
                <?php foreach ($oNotification->getAllNotificationFrom() as $key => $value) :  ?>
                  <li>
                    <a data-toggle="pill" href="#received_<?= $value['id_notification'] ?>">
                      <p>
                        <span class="from">To : <?= $value['receiver_user'] ?></span>
                        <?= ($value['notification_state']==2)?'<img type="image" src="img/notification.png" style="max-width:100%; max-height:1.5em;" />':'&nbsp;' ?>
                        <span class="datetime"><?= date("Y-m-d", strtotime($value['notification_date'])) ?></span>
                      </p>
                      <p class="subject"><?= $value['subject'] ?></p>
                    </a>
                  </li>
                <?php endforeach  ?>
              </ul>
            </div>
            <div id="historyI" class="tab-pane fade">
              <ul class="nav nav-pills nav-stacked">
                <li><a data-toggle="pill" href="#new">New</a></li>
                <?php foreach ($oNotification->getAllNotificationTo("history") as $key => $value) :  ?>
                  <li>
                    <a data-toggle="pill" href="#received_<?= $value['id_notification'] ?>">
                      <p>
                        <span class="from">From : <?= $value['transmitter'] ?></span>
                        <?= ($value['notification_state']==2)?'<img type="image" src="img/notification.png" style="max-width:100%; max-height:1.5em;" />':'&nbsp;' ?>
                        <span class="datetime"><?= date("Y-m-d", strtotime($value['notification_date'])) ?></span>
                      </p>
                      <p class="subject"><?= $value['subject'] ?></p>
                    </a>
                  </li>
                <?php endforeach  ?>
              </ul>
            </div>
            <div id="historyO" class="tab-pane fade">
              <ul class="nav nav-pills nav-stacked">
                <li><a data-toggle="pill" href="#new">New</a></li>
                <?php foreach ($oNotification->getAllNotificationFrom("history") as $key => $value) :  ?>
                  <li>
                    <a data-toggle="pill" href="#received_<?= $value['id_notification'] ?>">
                      <p>
                        <span class="from">To : <?= $value['receiver_user'] ?></span>
                        <?= ($value['notification_state']==2)?'<img type="image" src="img/notification.png" style="max-width:100%; max-height:1.5em;" />':'&nbsp;' ?>
                        <span class="datetime"><?= date("Y-m-d", strtotime($value['notification_date'])) ?></span>
                      </p>
                      <p class="subject"><?= $value['subject'] ?></p>
                    </a>
                  </li>
                <?php endforeach  ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-7 tab-content" style="height:100%;">
          <div id="new" class="tab-pane fade in" style="height:100%;">
            <form id="newNotification" style="height:100%;">
              <input type="hidden" name="type" value="sendNotification">
              <div style="height:90%; overflow-y:auto; overflow-x:hidden;">
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="receiver_type" style="display:block;">Receiver Type :</label>
                    <input id="receiver_type" name="receiver_type" data-offstyle="info" data-toggle="toggle" data-on="Frame" data-off="User" type="checkbox">
                  </div>
                  <div class="form-group col-md-8 receiver_frame" style="display:none;">
                    <label for="id_receiver_frame" style="display:block;">Frame(s) :</label>
                    <select class="form-control" id="id_receiver_frame" name="id_receiver_frame[]" multiple>
                      <option value="">-</option>
                      <?php foreach ($lstFrames as $row): ?>
                        <option value="<?= $row['id_machine'] ?>"><?= $row['machine'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="form-group col-md-8 receiver_user">
                    <label for="id_receiver_user" style="display:block;">User(s) :</label>
                    <select class="form-control" id="id_receiver_user" name="id_receiver_user[]" multiple>
                      <option value="">-</option>
                      <?php foreach ($lstUsers as $row): ?>
                        <option value="<?= $row['id_technicien'] ?>"><?= $row['technicien'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="subject">Subject :</label>
                  <input type="text" class="form-control" id="subject" name="subject" maxlength="50">
                </div>
                <div class="form-group">
                  <label for="text">Text :</label>
                  <textarea name="text" class="form-control"  id="text" name="text"style="height:100%" rows="10"></textarea>
                </div>
              </div>
              <div style="height:10%">
                <div class="col-md-2 col-md-offset-10 sendNotification" style="height:100%;">
                  <acronym title="Send">
                    <a href="#myModal" role="button" data-toggle="modal" data-target=".bs-example-modal-lg">
                      <img type="image" src="img/send.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                    </a>
                  </acronym>
                </div>
              </div>
            </form>
          </div>
          <?php foreach ($oNotification->getAllNotificationTo("history") as $key => $value) :  ?>
            <div id="received_<?= $value['id_notification'] ?>" class="tab-pane fade" style="height:100%;">
              <div style="height:90%; overflow-y:auto; overflow-x:hidden;">
                <div class="row">
                  <div class="form-group col-md-4 boxed">
                    <label class="labelTitle">From</label>
                    <label class="labelValue transmitter" data-id_transmitter="<?= $value['id_transmitter'] ?>"><?= $value['transmitter'] ?></label>
                  </div>
                  <div class="form-group col-md-4 boxed">
                    <label class="labelTitle">To</label>
                    <label class="labelValue"><?= (!empty($value['receiver_user']))?$value['receiver_user']:$value['receiver_frame'] ?></label>
                  </div>
                  <div class="form-group col-md-4 boxed">
                    <label class="labelTitle">Date</label>
                    <label class="labelValue"><?= date("Y-m-d", strtotime($value['notification_date'])) ?></label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="labelTitle">Subject</label>
                  <label class="labelValue subject"><?= $value['subject'] ?></label>
                </div>
                <div class="form-group">
                  <textarea class="text" style="height:100%; width:100%;" rows="13" disabled><?= $value['notification'] ?></textarea>
                </div>
              </div>
              <div style="height:10%">
                <div class="col-md-2 col-md-offset-6 reply boxed" data-id_notification="<?= $value['id_notification'] ?>" style="height:100%;">
                  <acronym title="Reply Notification">
                    <a href="#" role="button" >
                      <img type="image" src="img/reply.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                    </a>
                  </acronym>
                </div>
                <div class="col-md-2 notificate boxed" data-id_notification="<?= $value['id_notification'] ?>" style="height:100%;">
                  <acronym title="Toggle Notification">
                    <a href="#" role="button" >
                      <img type="image" src="img/notification.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                    </a>
                  </acronym>
                </div>
                <?php if ($value['notification_state']!=0) : ?>
                  <div class="col-md-2 deleteNotification boxed" data-id_notification="<?= $value['id_notification'] ?>" style="height:100%;">
                    <acronym title="Delete">
                      <a href="#" role="button">
                        <img type="image" src="img/cross.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                      </a>
                    </acronym>
                  </div>
                <?php endif ?>
              </div>
            </div>
          <?php endforeach  ?>
          <?php foreach ($oNotification->getAllNotificationFrom("history") as $key => $value) :  ?>
            <div id="received_<?= $value['id_notification'] ?>" class="tab-pane fade" style="height:100%;">
              <div style="height:90%; overflow-y:auto; overflow-x:hidden;">
                <div class="row">
                  <div class="form-group col-md-4 boxed">
                    <label class="labelTitle">From</label>
                    <label class="labelValue transmitter" data-id_transmitter="<?= $value['id_transmitter'] ?>"><?= $value['transmitter'] ?></label>
                  </div>
                  <div class="form-group col-md-4 boxed">
                    <label class="labelTitle">To</label>
                    <label class="labelValue"><?= (!empty($value['receiver_user']))?$value['receiver_user']:$value['receiver_frame'] ?></label>
                  </div>
                  <div class="form-group col-md-4 boxed">
                    <label class="labelTitle">Date</label>
                    <label class="labelValue"><?= date("Y-m-d", strtotime($value['notification_date'])) ?></label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="labelTitle">Subject</label>
                  <label class="labelValue subject"><?= $value['subject'] ?></label>
                </div>
                <div class="form-group">
                  <textarea class="text" style="height:100%; width:100%;" rows="13" disabled><?= $value['notification'] ?></textarea>
                </div>
              </div>
              <div style="height:10%">
                <div class="col-md-2 col-md-offset-6 reply boxed" data-id_notification="<?= $value['id_notification'] ?>" style="height:100%;">
                  <acronym title="Reply Notification">
                    <a href="#" role="button" >
                      <img type="image" src="img/reply.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                    </a>
                  </acronym>
                </div>
                <div class="col-md-2 notificate boxed" data-id_notification="<?= $value['id_notification'] ?>" style="height:100%;">
                  <acronym title="Toggle Notification">
                    <a href="#" role="button" >
                      <img type="image" src="img/notification.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                    </a>
                  </acronym>
                </div>
                <div class="col-md-2 deleteNotification boxed" data-id_notification="<?= $value['id_notification'] ?>" style="height:100%;">
                  <acronym title="Delete">
                    <a href="#" role="button">
                      <img type="image" src="img/cross.png" style="max-width:100%; max-height:100%; padding:5px 0px;" />
                    </a>
                  </acronym>
                </div>
              </div>
            </div>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="js/notification.js"></script>

<link href="lib/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="lib/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>
