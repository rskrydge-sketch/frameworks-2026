<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        "name",
        "description",
        "price"
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        "created_at",
        "updated_at",
        "test_id"
    ];

    /**
     * @return BelongsTo
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

}
