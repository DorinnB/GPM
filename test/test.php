<script type="text/javascript" src="../jquery/jquery-3.1.0.js"></script>







<div>
<?php


 ?>
 </div>


<?php
$formatted_date =  date('Y/m/d H:i:s');
echo $formatted_date;
?>
<br/>
Javascript code:

<div id="timer3"></div>
<script>
var javascript_date = new Date("<?php echo $formatted_date; ?>");

javascript_date.setSeconds(javascript_date.getSeconds() + 10);
$('#timer3').html(javascript_date);
</script>
