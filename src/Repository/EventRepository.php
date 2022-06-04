<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findByType(?string $type, ?int $offset = 0, ?int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('e')
            ->addSelect('t')
            ->innerJoin('e.type', 't')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if ($type !== null) {
            $qb->andWhere('t.name = :type')->setParameter('type', $type);
        }

        return $qb->getQuery()->getResult();
    }
}
