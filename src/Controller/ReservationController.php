<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\ReservationFormType;
use App\Form\ReservationFormType2;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{



    #[Route('reservation/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Room $room, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $reservation->setUser($this->getUser());
        $reservation->setRoom($room);

        $form = $this->createForm(ReservationFormType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $reservation->getStartDate();
            $endDate = $reservation->getEndDate();

            if ($startDate > $endDate) {
                $this->addFlash('error', 'Start date must be before end date.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);
            }

            $interval = $startDate->diff($endDate);
            if ($interval->m > 1 || $interval->days > 31) {
                $this->addFlash('error', 'Reservation duration cannot exceed 1 month.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);
            }

            $days = $startDate->diff($endDate)->days;
            $prix = $days * $room->getPrix();
            $reservation->setPrix($prix);

            $currentDate = new \DateTime();
            if ($currentDate >= $startDate && $currentDate <= $endDate) {
                $reservation->setStatut('encour');
            }
            else if ($currentDate < $startDate)
            {
                $reservation->setStatut('pending');
            }
            else{
                $reservation->setStatut('completed');
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'room' => $room
        ]);
    }
    #[Route('/reservation', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You need to be logged in to view reservations.');
        }

        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    #[Route('/admin/reservation', name: 'admin_reservation_list', methods: ['GET'])]
    public function display(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();
        return $this->render('reservation/list.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    #[Route('/admin/reservation/delete/{id}', name: 'delete_reservation')]
    public function deleteRoom(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Reservation supprimée avec succès.');
        return $this->redirectToRoute('admin_reservation_list');
    }
    #[Route('admin/reservation/ajout', name: 'admin_reservation_ajout')]
    public function ajout_reservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationFormType2::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $reservation->getStartDate();
            $endDate = $reservation->getEndDate();
            $room = $reservation->getRoom();
            if ($startDate > $endDate) {
                $this->addFlash('error', 'Start date must be before end date.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);
            }

            $interval = $startDate->diff($endDate);
            if ($interval->m > 1 || $interval->days > 31) {
                $this->addFlash('error', 'Reservation duration cannot exceed 1 month.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);
            }

            $days = $startDate->diff($endDate)->days;
            $prix = $days * $room->getPrix();
            $reservation->setPrix($prix);

            $currentDate = new \DateTime();
            if ($currentDate >= $startDate && $currentDate <= $endDate) {
                $reservation->setStatut('encour');
            }
            else if ($currentDate < $startDate)
            {
                $reservation->setStatut('pending');
            }
            else{
                $reservation->setStatut('completed');
            }
            $entityManager->persist($reservation);
            $entityManager->flush();
            return $this->redirectToRoute('admin_reservation_list');
        }
        return $this->render('reservation/new_reservation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
