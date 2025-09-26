<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedReports extends Model
{
    use HasFactory;

    protected $table = 'imported_reports';
    protected $primaryKey = 'id';


}
