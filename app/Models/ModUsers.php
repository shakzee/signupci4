<?php
namespace App\Models;
use CodeIgniter\Model;
class ModUsers extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'users';
    protected $primaryKey = 'u_id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['u_name','u_email','u_password','u_link','u_status'];
    protected $createdField = 'u_date';
    protected $updatedField = 'u_updated';

}