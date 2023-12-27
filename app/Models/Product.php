<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'count',
        'cost',
    ];

    protected $perPage = 40;

    public function properties(): HasMany
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function searchableAs(): string
    {
        return 'product_index';
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        return $array;
    }
}
