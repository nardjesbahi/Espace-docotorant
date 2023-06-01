<?php

//StateModel.php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model{

	protected $table = 'departement';

	protected $primaryKey = 'id';

	protected $allowedFields = ['faculty_id', 'nom','nom_ar','nom_an'];

}	
?>