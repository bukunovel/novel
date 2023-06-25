<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuNovel extends Model
{
    use HasFactory;

    protected $table = 'buku_novel';

    protected $fillable = [
        'judul_novel',
        'pengarang_novel',
        'penerbit_novel',
        'novel_terbit',
        'jumlah_view_novel',
        'image_novel',
    ];
}
