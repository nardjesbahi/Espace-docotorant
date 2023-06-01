<?php 
namespace App\Models;
use CodeIgniter\Model;

class LaboratoireModel extends Model
{
    protected $table = 'Laboratoire';

    protected $primaryKey = 'ID';
    
    protected $allowedFields = ['nom_fr', 'nom_ar'];
}