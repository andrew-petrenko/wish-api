<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wish\ChangeGoalAmountRequest;
use App\Http\Requests\Wish\ChargeDepositAmountRequest;
use App\Http\Requests\Wish\CreateRequest;
use App\Http\Requests\Wish\UpdateRequest;
use App\Http\Resources\WishResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;
use WishApp\Service\Wish\Contracts\WishServiceInterface;
use WishApp\Service\Wish\DTO\CreateWishDTO;
use WishApp\Service\Wish\DTO\UpdateWishDTO;

class WishController extends Controller
{
    private WishServiceInterface $wishService;

    public function __construct(WishServiceInterface $wishService)
    {
        $this->wishService = $wishService;
    }

    public function index(): JsonResponse
    {
        $collection = $this->wishService->getAllByUser(Uuid::fromString(auth()->user()->id));

        return \response()->json(
            ['data' => WishResource::collection($collection->all())],
            Response::HTTP_OK
        );
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $storeData = CreateWishDTO::createFromArray(
            array_merge($request->all(), ['user_id' => auth()->user()->id])
        );
        $wish = $this->wishService->create($storeData);

        return \response()->json(['data' => WishResource::make($wish)], Response::HTTP_CREATED);
    }

    public function update(UpdateRequest $request, string $uuid): JsonResponse
    {
        $wish = $this->wishService->update(
            Uuid::fromString($uuid),
            Uuid::fromString(auth()->user()->id),
            UpdateWishDTO::createFromArray($request->all())
        );

        return \response()->json(['data' => WishResource::make($wish)], Response::HTTP_OK);
    }

    public function changeGoal(ChangeGoalAmountRequest $request, string $uuid): JsonResponse
    {
        $wish = $this->wishService->changeGoalAmount(
            Uuid::fromString($uuid),
            Uuid::fromString(auth()->user()->id),
            $request->goalAmount()
        );

        return \response()->json(['data' => WishResource::make($wish)], Response::HTTP_OK);
    }

    public function chargeDeposit(ChargeDepositAmountRequest $request, string $uuid): JsonResponse
    {
        $wish = $this->wishService->chargeDepositAmount(
            Uuid::fromString($uuid),
            Uuid::fromString(auth()->user()->id),
            $request->depositAmount()
        );

        return \response()->json(['data' => WishResource::make($wish)], Response::HTTP_OK);
    }

    public function delete(string $uuid): JsonResponse
    {
        $this->wishService->delete(Uuid::fromString($uuid), Uuid::fromString(auth()->user()->id));

        return \response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
