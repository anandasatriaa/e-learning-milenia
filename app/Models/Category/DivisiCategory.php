<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisiCategory extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return empty($this->image) ? asset('img/no_image.jpg') : asset('/storage/category/divisi/' . $this->image);
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }
}
