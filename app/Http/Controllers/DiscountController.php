<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountCalculateRequest;
use App\Http\Resources\DiscountResource;
use App\Services\DiscountCalculatorService;
use Illuminate\Http\Request;
use App\DTOs\Order\OrderDto;

class DiscountController extends Controller
{
    public function __construct(protected DiscountCalculatorService $discountCalculatorService)
    {

    }

    public function calculate(DiscountCalculateRequest $request)
    {
        $orderDto = OrderDto::fromRequest($request->validated());

        $discounts = $this->discountCalculatorService->calculate($orderDto);

        return DiscountResource::collection($discounts);
    }
}
