<?php
namespace App\Service;

/**
 * Class Reservation
 *
 * @package App\Service
 */
class Reservation
{
    const INTERVAL_SPEC = 'P1D';

    /**
     * @param \DateTimeInterface $fromDate
     * @param \DateTimeInterface $toDate
     * @return \DatePeriod
     * @throws \Exception
     */
    public function getRangeDates(\DateTimeInterface $fromDate, \DateTimeInterface $toDate)
    {
        return new \DatePeriod(
            new \DateTime($fromDate->format('Y-m-d')),
            new \DateInterval(self::INTERVAL_SPEC),
            new \DateTime($toDate->format('Y-m-d'))
        );
    }
}