<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisiCategory extends Model
{
    use HasFactory;

    protected $table = 'divisi_categories';
    protected $appends = ['image_url'];
    protected $fillable = ['learning_cat_id', 'nama', 'image', 'deskripsi', 'active'];

    public function getImageUrlAttribute()
    {
        return empty($this->image) ? asset('img/no_image.jpg') : asset('/storage/category/divisi/' . $this->image);
    }

    public function learningCategory()
    {
        return $this->belongsTo(LearningCategory::class, 'learning_cat_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'divisi_category_id');
    }    
}
