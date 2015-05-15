<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MMetal extends Model {

    protected $table = 'materials_metals';

    protected $primaryKey = 'material_id';

    public $timestamps = false;

}
