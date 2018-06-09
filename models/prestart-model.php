<?php
class PrestartModel
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


    public function newPrestart(){
      $reqInsert='INSERT INTO prestart
      (
        id_poste,
        id_tbljob,
        shunt_cal,
        tune,
        custom_frequency,
        valid_alignement,
        valid_extenso,
        valid_temperature,
        valid_temperature_line,
        signal_true,
        signal_tapered,
        id_temperature_correction_parameter,
        operateur
      )

      VALUES (
        '.$this->id_poste.',
        '.$this->id_tbljob.',
        '.$this->shunt_cal.',
        '.$this->tune.',
        '.$this->custom_frequency.',
        '.$this->valid_alignement.',
        '.$this->valid_extenso.',
        '.$this->valid_temperature.',
        '.$this->valid_temperature_line.',
        '.$this->signal_true.',
        '.$this->signal_tapered.',
        (SELECT max(id_temperature_correction_parameter) FROM temperature_correction_parameters),
        '.$this->operateur.'
      );';

        //echo $reqInsert;
        $this->db->execute($reqInsert);
        return $this->db->lastId();
      }

}
