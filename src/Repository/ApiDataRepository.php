<?php

namespace App\Repository;

use App\Entity\ApiData;
use App\Service\ApiService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiData[]    findAll()
 * @method ApiData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiData::class);
    }

    public function save(ApiService $service)
    {
        $users = $service->getUsers(10);
        
        $entityManager = $this->getEntityManager();
        foreach ($users as $user) {
            $entityManager = $this->getEntityManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
    }

    public function deleteBeforeGetNewData()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "DELETE FROM App\Entity\ApiData
            ");

        $query->execute();
    }


}
