<?php
namespace App\Event;

use App\Entity\Quotes;
use App\Entity\Reservation;
use App\Repository\RoomsRepository;
use App\Validator\ReservationValidator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Repository\QuotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use App\Service\Reservation as ReservationService;
/**
 * Class EasyAdminSubscriber
 *
 * @package App\Event
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @var QuotesRepository
     */
    private $quotesRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;

    const RESERVATION_ENTITY = 'Reservation';
    /**
     * @var ReservationValidator
     */
    private $reservationValidator;
    /**
     * @var \App\Service\Reservation
     */
    private $reservationService;
    /**
     * @var RoomsRepository
     */
    private $roomsRepository;

    /**
     * EasyAdminSubscriber constructor.
     *
     * @param QuotesRepository $quotesRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        QuotesRepository $quotesRepository,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        RouterInterface $router,
        ReservationValidator $reservationValidator,
        ReservationService $reservationService,
        RoomsRepository $roomsRepository
    ) {
        $this->quotesRepository = $quotesRepository;
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->router = $router;
        $this->reservationValidator = $reservationValidator;
        $this->reservationService = $reservationService;
        $this->roomsRepository = $roomsRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::POST_UPDATE => 'onPreUpdate',
        ];
    }

    /**
     * @param GenericEvent $event
     * @throws \Exception
     */
    public function onPreUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if($this->getClassName(get_class($entity)) == self::RESERVATION_ENTITY) {
            $this->handleReservationUpdate($entity);
        }
    }

    /**
     * @param Reservation $entity
     * @return mixed
     * @throws \Exception
     */
    private function handleReservationUpdate(Reservation $entity)
    {
        if(!$this->reservationValidator->validate($entity)) {
            $availableRooms = $this->roomsRepository->getAvailableRooms($entity);
            $rooms = [];
            foreach($availableRooms as $availableRoom) {
                $rooms[] = $availableRoom->getId();
            }

            $this->session->getFlashBag()->add(
                'warning', 'Обнаружены пересечения, доступны номера ' . implode(', ', $rooms)
            );

            $redirect = $this->redirectToRoute('easyadmin', [
                'action' => 'edit',
                'entity' => self::RESERVATION_ENTITY,
                'id' => $entity->getId()
            ]);
            header('Location: ' . $redirect->getTargetUrl());
            exit;
        }

        $quotes = $this->quotesRepository->findBy(['reservation' => $entity]);
        foreach($quotes as $quote) {
            $entity->removeQoute($quote);
        }
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $period = $this->reservationService->getRangeDates(
            $entity->getEntryDate(),
            $entity->getExitDate()
        );

        foreach($period as $periodDate) {
            $quote = new Quotes();
            $quote->setDate($periodDate);
            $quote->setRoom($entity->getRoom());
            $quote->setHotel($entity->getRoom()->getHotel());
            $quote->setReservation($entity);
            $this->entityManager->persist($quote);
        }
        $this->entityManager->flush();
    }

    /**
     * @param $namespaceName
     * @return mixed
     */
    private function getClassName($namespaceName)
    {
        $path = explode('\\', $namespaceName);
        return array_pop($path);
    }

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @final
     */
    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
}