<?php
class DwgModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function dimDenomination()  {
      return $this->_dimDenomination;
    }


    public function getAllDwg() {
        return $this->db->getAll('SELECT * FROM dessins where dessin_actif=1 ORDER BY id_dessin=0, type, dessin;');
    }

    public function getDwg($id) {
        return $this->db->getOne('SELECT * FROM dessins where id_dessin='.$id);
    }


    public function dimensions($id_dessin_type){
      $req = 'SELECT denomination_1, denomination_2, denomination_3
      FROM dessin_types
      WHERE id_dessin_type='.$id_dessin_type;
      //echo $req.'<br/><br/>';
      return $this->db->getOne($req);
    }


    public function OLDdimension($format){
      if ($format=="Cylindrique")	{
        $this->_dimDenomination=array("Diam.");
      }
      elseif ($format=="Tube")	{
        $this->_dimDenomination=array("OD","ID");
      }
      elseif ($format=="Plate")	{
        $this->_dimDenomination=array("Largeur","Epaisseur");
      }
      elseif ($format=="Plate Percée")	{
        $this->_dimDenomination=array("Largeur","Epaisseur","ø trou");
      }
      else	{
        $this->_dimDenomination=array("rien");
      }
    }
}
