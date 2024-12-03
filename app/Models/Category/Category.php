<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return empty($this->image) ? asset('img/no_image.jpg') : asset('/storage/category/kategori/' . $this->image);
    }

    public function divisiCategory()
    {
        return $this->belongsTo(DivisiCategory::class);
    }

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }
}
