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
<p>
<table>
  <tr>
    <th>cookie</th>
    <th>value</th>
  </tr>
  <?php foreach ($_COOKIE as $key => $value) :  ?>
    <tr>
      <td><?= $key  ?></td>
      <td><?= $value  ?></td>
    </tr>
  <?php endforeach ?>
</table>
</p>



  <form method="POST" action="http://gpm/gpm/views/IT-cookie-view.php">
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
