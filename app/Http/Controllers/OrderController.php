<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Annotations as OA;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders",
     *     operationId="storeOrder",
     *     tags={"Pedidos"},
     *     summary="Cria um novo pedido",
     *     description="Cria um novo pedido com itens de produtos e calcula o preço total automaticamente",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"store_id", "products"},
     *             @OA\Property(property="store_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="store_id", type="integer", example=1),
     *             @OA\Property(property="total_price", type="number", format="float", example=299.97),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            "store_id" => ["required", "exists:stores,id"],
            "products" => ["required", "array"],
            "products.*.product_id" => ["required", "exists:products,id"],
            "products.*.quantity" => ["required", "integer", "min:1"],
        ]);

        $products = collect($request->input('products', []))->map(
            fn($item) => array_merge($item, Product::select('price')->find($item['product_id'])->toArray())
        );

        /** @var User $user */
        $user = $request->user();

        $total_price = $products->sum(fn($item) => $item['price'] * $item['quantity']);

        /** @var Order $order */
        $order = $user->customer->orders()->create([
            'store_id' => $request->input('store_id'),
            'total_price' => $total_price,
        ]);

        $order->products()->attach(
            $products->mapWithKeys(
                fn($item) => [
                    $item['product_id'] => [
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] * $item['quantity'],
                    ]
                ]
            )
        );

        return $order;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
