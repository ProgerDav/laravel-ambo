<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Link
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\LinkFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Link newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Link query()
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 */
class Link extends Model
{
    use HasFactory;

    /**
     * If guarded is empty => all columns are fillable
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'code', 'code');
    }
}
