<?php
class USER
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function register($user,$mdp)
  {
    return $this->db->query('INSERT INTO techniciens (technicien,mdp)
    VALUES('.$user.', '.$mdp.')');
  }

  public function login($iduser,$mdp,$rememberme)
  {
    $req='SELECT * FROM techniciens WHERE id_technicien='.$iduser;

    $userRow = $this->db->getOne($req);

    if(isset($userRow['id_technicien']) && ($mdp == $userRow['mdp']))
    {
      $maReponse = array('result' => 'correct', 'id_technicien' => $userRow['id_technicien'], 'technicien' => $userRow['technicien']);

      if($rememberme=='true') {
        setcookie("id_user", $iduser, time() + (86400 * 100), "/"); //100jours
        setcookie("technicien", $userRow['technicien'], time() + (86400 * 100), "/"); //100jours
        setcookie("password", $mdp, time() + (86400 * 100), "/"); //100jours
        setcookie("expiry", time() + (86400 * 100), time() + (86400 * 100), "/"); //100jours
      }
      else {
        setcookie("id_user", $iduser, time() + (10*60), "/"); //10 minutes = detruit le cookie
        setcookie("technicien", $userRow['technicien'], time() + (10*60), "/"); //10 minutes  = detruit le cookie
        setcookie("password", $mdp, time() + (10*60), "/"); //10 minutes = detruit le cookie
        setcookie("expiry", time() + (10*60), time() + (10*60), "/"); //10 minutes = detruit le cookie
      }
      echo json_encode($maReponse);
    }
    else
    {
      $maReponse = array('result' => 'Password incorrect');
      setcookie("id_user", "", time() + (1), "/"); //1 secondes = detruit le cookie
      setcookie("technicien", "", time() + (1), "/"); //1 secondes = detruit le cookie
      setcookie("password", "", time() + (1), "/"); //1 secondes = detruit le cookie
      setcookie("expiry", time() + (1), time() + (1), "/"); //1 secondes = detruit le cookie
      echo json_encode($maReponse);
    }
  }

  public function shortlogin($iduser,$mdp)


  {
    $req='SELECT * FROM techniciens WHERE id_technicien='.$iduser;

    $userRow = $this->db->getOne($req);

    if(isset($userRow['id_technicien']) && ($mdp == $userRow['mdp']))
    {
      $maReponse = array('result' => 'correct', 'id_technicien' => $userRow['id_technicien'], 'technicien' => $userRow['technicien'], 'expiry'=>$_COOKIE['expiry']);

      echo json_encode($maReponse);
    }
    else
    {
      $maReponse = array('result' => 'Password incorrect');
      setcookie("id_user", "", time() + (1), "/"); //1 secondes = detruit le cookie
      setcookie("technicien", "", time() + (1), "/"); //1 secondes = detruit le cookie
      setcookie("password", "", time() + (1), "/"); //1 secondes = detruit le cookie
      setcookie("expiry", "", time() + (1), "/"); //1 secondes = detruit le cookie
      echo json_encode($maReponse);
    }
  }

  public function is_loggedin()
  {
    if(isset($_COOKIE['id_user']))
    {
      return true;
    }
  }

  public function redirect($url)
  {
    header("Location: $url");
  }

  public function logout()
  {
    session_destroy();
    unset($_COOKIE['id_user']);
    unset($_COOKIE['technicien']);
    return true;
  }

  public function getUser()
  {
    return $_COOKIE['id_user'];
  }

  public function getTechnicien()
  {
    return $_COOKIE['technicien'];
  }
}
?>
