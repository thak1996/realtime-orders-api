<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     description="Modelo de Pedido",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="customer_id", type="integer", example=1),
 *     @OA\Property(property="store_id", type="integer", example=1),
 *     @OA\Property(property="total_price", type="number", format="float", example=299.97),
 *     @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"}, example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "total_price",
    ];


    /**
     * Summary of store
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Summary of customer
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Summary of products
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity', 'price');
    }
}
