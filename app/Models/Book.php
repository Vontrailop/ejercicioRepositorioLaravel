<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = "books";

    protected $fillable =[
        'id',
        'isbn',
        'title',
        'description',
        'published_date',
        'category_id',
        'editorial_id'
    ];
    public $timestamps = false;

    public function category(){
        return $this->belongsTo(Category::class, 'category_id','id');
    }

    public function editorials(){
        return $this->belongsTo(Editorial::class, 'editorial_id','id');
    }

    public function autores(){
        return $this->belongsToMany(
            Autor::class,
            'authors_books',
            'book_id',
            'autores_id'
        );
    }

    public function download(){
        return $this->hasOne(BookDownload::class);
    }
}
