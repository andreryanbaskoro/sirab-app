<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PekerjaanMaterial extends Model
{
    protected $fillable = ['pekerjaan_id', 'material_id', 'qty'];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
