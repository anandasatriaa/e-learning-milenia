<?php

namespace App\Models\Matriks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category\DivisiCategory;

class DashboardMatriks extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dashboard_matriks_kompetensi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'divisi_category_id',
        'nama',
        'kode_dashboard',
        'tgl_update',
        'image_ttd_1',
        'image_ttd_2',
        'image_ttd_3',
        'nama_ttd_1',
        'nama_ttd_2',
        'nama_ttd_3',
    ];

    /**
     * Get the associated division category.
     */
    public function divisiCategory()
    {
        return $this->belongsTo(DivisiCategory::class, 'divisi_category_id');
    }
}
