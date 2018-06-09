<?php
class TestModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getTest() {
        return $this->db->getAll('select info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, currentBlock, eprouvettes.Cycle_final, split, machine, poste
          from enregistrementessais
          left join eprouvettes on eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
          left join master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
          left join info_jobs on info_jobs.id_info_job=tbljobs.id_info_job
          left join prestart on prestart.id_prestart=enregistrementessais.id_prestart
          left join postes on postes.id_poste=prestart.id_poste
          left join machines on machines.id_machine=postes.id_machine

          where n_fichier in (SELECT max(n_fichier)
          FROM `enregistrementessais`
          left join prestart on prestart.id_prestart=enregistrementessais.id_prestart
          left join postes on postes.id_poste=prestart.id_poste
          group by postes.id_machine)
          AND currentBlock is not null AND currentBlock !="Send"
          order by machine');
    }
}
