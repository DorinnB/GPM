<?php
class PrestartModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPrestart($id_prestart) {
      $req='SELECT shunt_cal, tune, valid_alignement, valid_extenso, valid_temperature, valid_temperature_line, signal_true, signal_tapered, DATE_FORMAT(prestart.date,"%Y-%m-%d") as date, prestart.operateur,
      job, customer, split, machine, cartouche_load,
      cell_load_gamme, technicien,
      Disp_P, Disp_i, Disp_D, Disp_Conv, Disp_Sens, Load_P, Load_i, Load_D, Load_Conv, Load_Sens, Strain_P, Strain_i, Strain_D, Strain_Conv, Strain_Sens

			FROM prestart
      LEFT JOIN postes ON postes.id_poste=prestart.id_poste
      LEFT JOIN cell_load ON cell_load.id_cell_load=postes.id_cell_load
			LEFT JOIN machines ON machines.id_machine=postes.id_machine
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=prestart.id_tbljob
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN techniciens ON techniciens.id_technicien=prestart.operateur
			WHERE prestart.id_prestart = '.$id_prestart;
//echo $req;
        return $this->db->getOne($req);
    }


}
