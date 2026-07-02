<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /** フォーム等から一括代入を許可するカラム(マスアサインメント対策) */
    protected $fillable = ['title', 'description', 'completed'];

    /** DB の値を PHP の型に変換するルール */
    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }
}
