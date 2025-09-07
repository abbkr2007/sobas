<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_title',
        'document_key',
        'document_path',
        'theme',
        'paper_type', // Add this field
        'created_by',
        'user_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
