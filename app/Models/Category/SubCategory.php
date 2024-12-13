<?php

namespace App\Models\Category;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];
    protected $table = 'sub_categories';

    public function getImageUrlAttribute()
    {
        return empty($this->image) ? asset('img/no_image.jpg') : asset('/storage/category/subkategori/' . $this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function course()
    {
        return $this->hasMany(Course::class, 'sub_category_id');
    }
}
