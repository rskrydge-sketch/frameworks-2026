<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{

    public const FIELDS = [
        "name"        => "LIKE",
        "description" => "LIKE",
        "price"       => "BETWEEN"
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function getProducts(array $criteria = []): mixed
    {
        $queryBuilder = $this->createQueryBuilder('products');

        $queryBuilder = $this->filterData($queryBuilder, $criteria);

        if (!empty($criteria['price']['lte']) || !empty($criteria['price']['gte'])) {
            $queryBuilder
                ->andWhere('products.price <= :priceLte AND products.price >= :priceGte')
                ->setParameter('priceLte', $criteria['price']['lte'])
                ->setParameter('priceGte', $criteria['price']['gte']);
        }

        $page = $criteria['page'] ?? 1;
        $itemsPerPage = $criteria['itemsPerPage'] ?? 10;

        return $queryBuilder
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     * @return mixed
     */
    public function filterData(QueryBuilder $queryBuilder, array $criteria = []): mixed
    {
        $fields = self::FIELDS;

        foreach ($fields as $key => $value) {
            if (isset($criteria[$key]) && $value == "LIKE") {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->like($queryBuilder->getRootAlias() . "." . $key, ':' . $key))
                    ->setParameter($key, "%" . $criteria[$key] . "%");
            }
        }

        return $queryBuilder;
    }

}
