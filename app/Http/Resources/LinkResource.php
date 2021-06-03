<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'count' => $this->orders->count(),
            'revenue' => $this->orders->sum(fn (Order $order) => $order->ambassador_revenue),
        ];
    }
}
