<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];
    protected $fillable = ['divisi_category_id', 'nama', 'image', 'deskripsi', 'active'];

    public function getImageUrlAttribute()
    {
        return empty($this->image) ? asset('img/no_image.jpg') : asset('/storage/category/kategori/' . $this->image);
    }

    public function divisiCategory()
    {
        return $this->belongsTo(DivisiCategory::class, 'divisi_category_id');
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id')->onDelete('cascade');
    }
}
