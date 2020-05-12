<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Rooms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rooms|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rooms|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rooms[]    findAll()
 * @method Rooms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomsRepository extends ServiceEntityRepository
{
    /**
     * @var QuotesRepository
     */
    private $quotesRepository;

    public function __construct(ManagerRegistry $registry, QuotesRepository $quotesRepository)
    {
        parent::__construct($registry, Rooms::class);
        $this->quotesRepository = $quotesRepository;
    }

    /**
     * @param Reservation $reservation
     * @return mixed
     */
    public function getAvailableRooms(Reservation $reservation)
    {
        $query = $this->createQueryBuilder('r');
        $from = $reservation->getEntryDate()->setTime(0, 0, 0)->format('Y-m-d H:m:s');
        $to = $reservation->getExitDate()->setTime(23, 59, 59)->format('Y-m-d H:m:s');
        return $query
            ->where($query->expr()->notIn('r.id', $this->quotesRepository->getAlternativesRoomsByDates($reservation)->getDQL()))
            ->andWhere('r.hotel = :hotel')
            ->setParameter('hotel', $reservation->getRoom()->getHotel())
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Rooms[] Returns an array of Rooms objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rooms
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
