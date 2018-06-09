<?php
if (isset($_POST['id_machine'])){
  if ($_POST['id_machine']=="0") {
  setcookie("id_machine", $_POST['id_machine'], time() + (1), "/"); // suppression
  }
  else {
    setcookie("id_machine", $_POST['id_machine'], time() + (10 * 365 * 24 * 60 * 60), "/"); // 10 ans
  }

}
?>
<html>
<body>

  <?php
  if(isset($_COOKIE['id_machine'])) {
    var_dump($_COOKIE);

    echo '
    <p>Machine : '.$_COOKIE['id_machine'].'</p>
    ';
  }
  ?>



  <form method="POST">
    <div>
      <label>Machine : </label>
      <SELECT  name="id_machine">
        <option value="0">-</option>
        <?php for ($i=1; $i < 30; $i++): ?>
          <option value="<?= $i  ?>"><?= $i?></option>
        <?php endfor ?>
      </select>


    </div>
    <input type="submit" value="valid">
  </form>

</body>
</html>
