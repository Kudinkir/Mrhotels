<?php

namespace App\Validator;

use App\Entity\Reservation;
use App\Repository\QuotesRepository;
use App\Repository\RoomsRepository;
use App\Service\Reservation as ReservationService;

class ReservationValidator
{
    /**
     * @var ReservationService
     */
    private $reservationService;

    /**
     * @var QuotesRepository
     */
    private $quotesRepository;

    public function __construct(
        ReservationService $reservationService,
        QuotesRepository $quotesRepository,
        RoomsRepository $roomsRepository
    ) {
        $this->reservationService = $reservationService;
        $this->quotesRepository = $quotesRepository;
    }

    public function validate(Reservation $reservation)
    {
        $quotes = $this->quotesRepository->getAllBeetweenDates($reservation);

        if(!count($quotes)) {
            return true;
        }
        return false;
    }
}