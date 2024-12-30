<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_dashboard')]
    public function index(ReservationRepository $reservationRepository, RoomRepository $roomRepository, EventRepository $eventRepository, CommentaireRepository $commentaireRepository, UserRepository $userRepository, ServiceRepository $serviceRepository): Response
    {  $eventStat=$eventRepository->stat();
        $serviceStat=$serviceRepository->stat();
        $RoomStats=$roomRepository->getRoomReservationStats();
        $ClientNbr=$userRepository->countUserByRole('ROLE_CLIENT');
        $AdminNbr=$userRepository->countUserByRole('ROLE_ADMIN');
        $reviewsNbr=$commentaireRepository->countRoomsComments();
        $commentsNbr=$commentaireRepository->countEventsComments();
        $roombr=$roomRepository->countRooms();
        $eventnbr=$eventRepository->countEventsafterdate($date = new \DateTime());
        $revenue=$reservationRepository->totalPrix();
        $revenueThisMonth=$reservationRepository->totalPrixThisMonth();
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController','revenue'=>$revenue,
            'selected'=>'dashboard','revenueThisMonth'=>$revenueThisMonth,'roomnbr'=>$roombr,'eventnbr'=>$eventnbr,'reviewsNbr'=>$reviewsNbr,'commentsNbr'=>$commentsNbr,'ClientNbr'=>$ClientNbr,'AdminNbr'=>$AdminNbr,'RoomStats'=>$RoomStats,'serviceStat'=>$serviceStat,'eventStat'=>$eventStat
        ]);
    }
    #[Route('/about', name: 'app_about')]
    public function about(UserRepository $userRepository, ServiceRepository $serviceRepository, EventRepository $eventRepository, RoomRepository $roomRepository): Response
    {
        $usernbr=$userRepository->countUsers();
        $eventnbr=$eventRepository->countEventsafterdate($date = new \DateTime());
        $servicenbr=$serviceRepository->countServices();
        $roomnbr=$roomRepository->countRooms();
        $services = $serviceRepository->findAll();
        $users = $userRepository->findUsersByRole('ROLE_ADMIN');
        return $this->render('admin/about.html.twig', ['users' => $users,'services' => $services,'eventnbr' => $eventnbr,'roomnbr' => $roomnbr,'usernbr'=>$usernbr,'servicenbr'=>$servicenbr]);
    }


}
