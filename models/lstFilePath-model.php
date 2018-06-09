<?php
class FilePathsModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function __set($property,$value) {
      if (is_numeric($value)){
        $this->$property = $value;
      }
      else {
        $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
      }
    }

    public function getAllFilePath() {
        return $this->db->getAll('SELECT * FROM file_paths ORDER BY file_type;');
    }

    public function getFilePath() {
        return $this->db->getOne('SELECT * FROM file_paths WHERE file_type='.$this->file_type);
    }
}
