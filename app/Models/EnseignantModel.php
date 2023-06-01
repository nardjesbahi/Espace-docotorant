<?php 
namespace App\Models;
use CodeIgniter\Model;

class EnseignantModel extends Model
{
    protected $table = 'enseignant';

    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'grade', 
        'nom_ar',
        'prenom_ar',
        'departement_id',
        'faculty_id',
        'nom_fr',
        'prenom_fr',
        'diplome',
        'externe',
        'email',
        'univOrigine_ar'
    ];
}