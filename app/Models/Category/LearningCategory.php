<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningCategory extends Model
{
    use HasFactory;

    protected $table = 'learning_cat';
    protected $fillable = ['nama', 'image', 'deskripsi', 'active'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return empty($this->image) ? asset('img/no_image.jpg') : asset('/storage/category/learning/' . $this->image);
    }

    public function divisiCategories()
    {
        return $this->hasMany(DivisiCategory::class, 'learning_cat_id')->onDelete('cascade');
    }

    public function category()
    {
        return $this->hasMany(Category::class, 'learning_cat_id');
    }

}
