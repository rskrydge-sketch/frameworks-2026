<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{

    public const array DATA = [
        [
            "id"          => 1,
            "name"        => "product1",
            "description" => "product1 description",
            "price"       => 100.00
        ],
        [
            "id"          => 2,
            "name"        => "product2",
            "description" => "product2 description",
            "price"       => 200.00
        ],
        [
            "id"          => 3,
            "name"        => "product3",
            "description" => "product3 description",
            "price"       => 300.00
        ],
        [
            "id"          => 4,
            "name"        => "product4",
            "description" => "product4 description",
            "price"       => 400.00
        ],
        [
            "id"          => 5,
            "name"        => "product5",
            "description" => "product5 description",
            "price"       => 500.00
        ],
    ];

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getProductById(string $id): JsonResponse
    {
        $products = self::DATA;

        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return new JsonResponse($product, Response::HTTP_OK);
            }
        }

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }

    /**
     * @return JsonResponse
     */
    public function getProducts(): JsonResponse
    {
        return new JsonResponse(self::DATA, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createProducts(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $lastProductId = self::DATA[count(self::DATA) - 1]["id"];

        $data['id'] = $lastProductId + 1;

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProductById(string $id, Request $request): JsonResponse
    {
        $products = self::DATA;

        $needProduct = null;

        foreach ($products as $product) {
            if ($product['id'] == $id) {
                $needProduct = $product;
            }
        }

        if (empty($needProduct)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $needProduct['price'] = $data['price'];

        return new JsonResponse($needProduct, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteProductById(string $id): JsonResponse
    {
        $products = self::DATA;

        foreach ($products as $product) {
            if ($product['id'] == $id) {
                // TODO remove product
                return new JsonResponse([], Response::HTTP_NO_CONTENT);
            }
        }

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }

}
