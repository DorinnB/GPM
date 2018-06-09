<?php
class DocumentModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllDocuments($tbl, $idtbl, $type) {
    $req='SELECT *
    FROM documents
    WHERE tbl='.$this->db->quote($tbl).'
    AND id_tbl='.$this->db->quote($idtbl).'
    '.(($type=="")?"":'AND type='.$this->db->quote($type));
    //echo $req;
    return $this->db->getAll($req);
  }

  public function getOneDocument($id) {
    $req='SELECT *
    FROM documents
    WHERE id_document='.$this->db->quote($id);
    //echo $req;
    return $this->db->getOne($req);
  }

  public function nbDocuments($tbl, $idtbl, $type) {
    $req='SELECT *
    FROM documents
    WHERE tbl='.$this->db->quote($tbl).'
    AND id_tbl='.$this->db->quote($idtbl).'
    '.(($type=="")?"":'AND type='.$this->db->quote($type));
    //echo $req;
    if( $this->db->isOne($req))  {
      return count($this->db->getAll($req));
    }
    else {
      return '0';
    }
  }

  public function newDocument($idtbl, $tbl, $type, $path, $name) {
    $req='INSERT INTO documents
    (tbl, id_tbl, type, path, file, document_actif)
    VALUES
    ('.$this->db->quote($tbl).', '.$this->db->quote($idtbl).', '.$this->db->quote($type).', '.$this->db->quote($path).', '.$this->db->quote($name).', 1)';
    //echo $req;
    $this->db->execute($req);
    return $this->db->lastId();
  }

}
