<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Subject>
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Subject::class);
    }

    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $data = $this->createQueryBuilder('s');

        if (!empty($searchData->q)) {
            $data = $data
                ->andWhere('s.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        if ($searchData->theme !== null) {
            $data = $data
                ->andWhere('s.theme = :theme')
                ->setParameter('theme', $searchData->theme);
        }

        return $this->paginator->paginate($data, $searchData->page);
    }
}