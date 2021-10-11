<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Task;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Customer::class);
        $this->entityManager = $entityManager;
    }

    public function save(Customer $customer)
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    public function removeCustomer(Customer $customer)
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }

    // public function getCustomerWithTasks($id)
    // {
    //     $query = $this->createQueryBuilder('p')
    //         ->innerJoin(Task::class, 't')
    //         ->addSelect('t')
    //         ->where('p.id = :id')
    //         ->andWhere('t.customerId = p.id')
    //         ->setParameter('id', $id)
    //         ->getQuery()->getResult();
            
    //     return $query;
    // }
}
