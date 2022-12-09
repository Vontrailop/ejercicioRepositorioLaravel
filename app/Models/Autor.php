<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = "autores";

    protected $fillable =[
        'id',
        'name',
        'first_surname',
        'second_surname'
    ];
    public $timestamps = false;

    public function books(){
        return $this->belongsToMany(
            Book::class,
            'authors_books',
            'autores_id',
            'book_id'
        );
    }
}
