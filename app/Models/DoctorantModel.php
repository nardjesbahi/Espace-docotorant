<?php 
namespace App\Models;
use CodeIgniter\Model;

class DoctorantModel extends Model
{
    protected $table = 'Doctorant';

    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'nom_fr', 
        'prenom_fr',
        'nom_ar',
        'prenom_ar',
        'date_nec',
        'lieu_nec',
        'lieu_nec_arabe',
        'prenom_pere',
        'nom_mere',
        'prenom_mere',
        'adresse',
        'domaine',
        'filiere',
        'specialite',
        'domaine_ar',
        'filiere_ar', 
        'specialite_ar',
        'fac_id',
        'dept_id',
        'type',
        'sujet',
        'DatePremiereInscription',
        'NumeroDecretDoctorat',
        'dateDecretDoctorat', 
        'sexe',
        'user_id',
        'situation',
        'CoursSpecialite',
        'CoursMethodologie',
        'CoursTIC',
        'CoursAnglais',
        'immatriculeBAC',
        'nationalite_ar',
        'encadrant_id',
        'co_encadrant_id',
        'email',
        'telephone',
        'nomLabo',
        'dossierPapierValide',
        'reserveDossierPapier',
        'DateDoutenanceRemplitAgent',
        'token',
        'dossier soutenance',
        ];
}