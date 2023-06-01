<?php 
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['email', 'pwd','role','departement_id','faculty_id'];
}