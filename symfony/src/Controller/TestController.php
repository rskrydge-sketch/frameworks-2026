<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    #[Route('/products/{id}', name: 'app_get_product_by_id', methods: ['GET'])]
    public function getProductById(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);

        if (empty($product)) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, Response::HTTP_OK);
    }

    //
    #[Route('/products', name: 'app_get_products', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->json($products, Response::HTTP_OK);
    }

    #[Route('/products', name: 'app_create_products', methods: ['POST'])]
    public function createProducts(Request $request): JsonResponse
    {
        $productData = json_decode($request->getContent(), true);

        $test = new Test();

        $test->setName($productData['name']);
        $test->setDescription($productData['description']);

        $this->entityManager->persist($test);

        $product = new Product();

        $product
            ->setName($productData['name'])
            ->setDescription($productData['description'])
            ->setPrice($productData['price']);

        $product->setTest($test);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'app_update_product_by_id', methods: ['PATCH'])]
    public function updateProductById(string $id, Request $request): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);

        if (empty($product)) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $productData = json_decode($request->getContent(), true);

        if (isset($productData['name'])) {
            $product->setName($productData['name']);
        }

        if (isset($productData['description'])) {
            $product->setDescription($productData['description']);
        }

        if (isset($productData['price'])) {
            $product->setPrice($productData['price']);
        }

        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_OK);
    }

    #[Route('/products/{id}', name: 'app_delete_product_by_id', methods: ['DELETE'])]
    public function deleteProductById(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);

        if (empty($product)) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NOT_FOUND);
    }

}
