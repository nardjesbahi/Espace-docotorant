<?php 
namespace App\Models;
use CodeIgniter\Model;

class DossiersoutenanceModel extends Model
{
    protected $table = 'Dossiersoutenance';

    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        
       
        'Date_CSD', 
        'Date_CSF',
        'DateSoutenance',
        'Doctorant_id',
        'president_jury_id',
        'membre_1_id',
        'membre_2_id',
        'membre_3_id',
        'nmembre_4_id',
        'membre_5_id',
        'titrePub',
        'dateSoumission',
        'dateAcceptation',
        'Etape',
        ];
}?>