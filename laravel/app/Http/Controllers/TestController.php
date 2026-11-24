<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getProductById(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(
                ['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($product, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getProducts(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $products = Product::where('user_id', $user->id)->get();

        return response()->json($products, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createProducts(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $request->user();

        $product = DB::transaction(function () use ($data) {
            $product = new Product();

            $product->fill($data);

            $product->save();

            return $product;
        });

        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProductById(string $id, Request $request): JsonResponse
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(
                ['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $product->name = $data['name'];
        }

        if (isset($data['description'])) {
            $product->description = $data['description'];
        }

        if (isset($data['price'])) {
            $product->price = $data['price'];
        }

        $product->save();

        return new JsonResponse($product, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteProductById(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(
                ['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $product->delete();

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }

}
