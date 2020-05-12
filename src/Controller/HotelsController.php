<?php

namespace App\Controller;

use App\Entity\Hotels;
use App\Entity\Reservation;
use App\Repository\RoomsRepository;
use App\Validator\ReservationValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HotelsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HotelsController extends AbstractController
{
    /**
     * @Route("/hotels/list", name="hotels_list")
     */
    public function list(HotelsRepository $hotelsRepository)
    {
        $hotels = $hotelsRepository->findActive();
        return $this->json(['hotels' => $hotels]
        );
    }

    /**
     * @Route("/hotels/item/{id}", name="hotel_item")
     * @ParamConverter("id", class="\App\Entity\Hotels")
     */
    public function item(
        Hotels $hotel,
        int $id
    )
    {
        $result = ['hotel_name' => $hotel->getName()];
        $result['rooms'] = [];
        foreach ($hotel->getRooms() as $room) {
            foreach ($room->getQoutes() as $quote) {
                array_push($result['rooms'], 'Roomid = ' . $room->getId() . ', name ' . $room->getCategory() . ' ' . $quote->getDate()->format('Y-m-d H:m:s'));
            }
        }
        return $this->json(['result' => $result]);
    }

    /**
     * @Route("/hotels/add_reservation", name="hotels_add_reservation")
     */
    public function addReservation(
        Request $request,
        ReservationValidator $reservationValidator,
        RoomsRepository $roomsRepository
    )
    {
        $entry_date = new \DateTime($request->query->get("entry_date"));
        $exit_date = new \DateTime($request->query->get("exit_date"));
        $room = $roomsRepository->find($request->query->get("room_id"));
        $entityManager = $this->getDoctrine()->getManager();
        $new_res = new Reservation();
        $new_res->setRoom($room);
        $new_res->setEntryDate($entry_date);
        $new_res->setGuestsQuantity($request->query->get("guest_quantity"));
        $new_res->setExitDate($exit_date);
        $new_res->setGuestPhone($request->query->get("phone"));
        $new_res->setGuestEmail($request->query->get("email"));
        $entityManager->persist($new_res);
        $entityManager->flush();
        if (!$reservationValidator->validate($new_res)) {
            $availableRooms = $roomsRepository->getAvailableRooms($new_res);
            $rooms = [];
            foreach ($availableRooms as $availableRoom) {
                $rooms[] = $availableRoom->getId();
            }
            $entityManager->remove($new_res);
            $entityManager->flush();
            return $this->json(['error' => 'Обнаружены пересечения, доступны номера с id ' . implode(', ', $rooms)]
            );
        }
        return $this->json(['result' => 'ok']);
    }
}
