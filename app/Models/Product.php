<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $image
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'price'];

    protected static function flushCache(): void
    {
        // for ($i = 0; $i < 1000; $i++) {
        //     if (!Cache::hasKey('products_paginated_page_' . $i))
        //         break;

        //     Cache::forget('products_paginated_page_' . $i);
        // }
        Cache::forget('products_full_collection');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => static::flushCache());
        static::updated(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function getFullCachedCollection(): Collection
    {
        return Cache::remember('products_full_collection', 60 * 30, fn () => static::all());
    }

    protected static function getPaginatedCacheKey(int $page): string
    {
        return 'products_paginated_page_' . $page;
    }

    public static function getPaginatedCache(int $page, int $perPage = 15): LengthAwarePaginator
    {
        return Cache::remember(static::getPaginatedCacheKey($page), 60 * 30, fn () => static::paginate($perPage, ['*'], 'page', $page));
    }
}
