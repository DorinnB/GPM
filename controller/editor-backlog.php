<?php
// DataTables PHP library
include( "../DataTables/Editor-PHP-1.9.2/lib/DataTables.php" );



// Alias Editor classes so they are easy to use
use
DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Mjoin,
DataTables\Editor\Options,
DataTables\Editor\Upload,
DataTables\Editor\Validate;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'info_jobs' )
->pkey( 'info_jobs.id_info_job' )
->fields(
  Field::inst( 'info_jobs.datecreation'),
  Field::inst( 'info_jobs.customer'),
  Field::inst( 'info_jobs.job'),
  Field::inst( 'info_jobs.order_val'),
  Field::inst( 'info_jobs.order_est')->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'info_jobs.order_est_subc')->setFormatter( 'Format::ifEmpty', null ),
  Field::inst( 'ubr.ubrMRSAS'),
  Field::inst( 'ubr.ubrSubC'),
  Field::inst( 'ubr.date_UBR'),

  Field::inst( 'info_jobs.job as info_jobs.invoice_final')
    ->set(false)
    ->getFormatter( function($val, $data, $opts) use ( $db ) {
        $stmt = ('SELECT invoice_final
                    FROM invoices
                     WHERE inv_job = :id
                     ORDER BY id_invoice ASC LIMIT 1');
        $result = $db ->raw()
                      ->bind(':id', $val)
                      ->exec($stmt);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ( (bool)$row ) {
            return $row["invoice_final"];
        }
        return -1;
      }),

      Field::inst( 'info_jobs.job as info_jobs.etape')
        ->set(false)
        ->getFormatter( function($val, $data, $opts) use ( $db ) {
            $stmt = ('SELECT max(etape) as etape
                        FROM info_jobs
                        LEFT JOIN tbljobs ON tbljobs.id_info_job=info_jobs.id_info_job
                        LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
                        LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
                         WHERE info_jobs.job = :id
                         AND tbljobs.tbljob_actif=1');
            $result = $db ->raw()
                          ->bind(':id', $val)
                          ->exec($stmt);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ( (bool)$row ) {
                return $row["etape"];
            }
            return -1;
          }),

  Field::inst( 'info_jobs.id_info_job' )->set( false ),
  Field::inst( 'info_jobs.job as info_jobs.invoicesSubC')
    ->set(false)
    ->getFormatter( function($val, $data, $opts) use ( $db ) {
        $stmt = ('SELECT SUM(inv_subc) as invoices
                    FROM invoices
                     WHERE inv_job = :id');
        $result = $db ->raw()
                      ->bind(':id', $val)
                      ->exec($stmt);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ( (bool)$row ) {
            return $row["invoices"];
        }
        return 0;
      }),
  Field::inst( 'info_jobs.job as info_jobs.invoicesMRSAS')
    ->set(false)
    ->getFormatter( function($val, $data, $opts) use ( $db ) {
        $stmt = ('SELECT SUM(inv_mrsas) as invoices
                    FROM invoices
                     WHERE inv_job = :id');
        $result = $db ->raw()
                      ->bind(':id', $val)
                      ->exec($stmt);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ( (bool)$row ) {
            return $row["invoices"];
        }
        return 0;
      })

  )

  ->leftJoin('ubr', 'ubr.job=info_jobs.job and ubr.id_UBR = (select u.id_ubr from ubr u where u.job=ubr.job order by date_ubr desc limit 1)','','')


  ->where('info_jobs.datecreation',$_POST['dateStartBacklog'],'>=')

  ->process($_POST)
  ->json();
  ?>
