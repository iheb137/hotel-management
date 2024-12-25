<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\CommentaireFormType;
use App\Form\ReservationFormType;
use App\Form\ReservationFormType2;
use App\Repository\CommentaireRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{



    #[Route('client/reservation/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Room $room, Request $request, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository): Response
    {
        $reservation = new Reservation();
        $commentaire = new Commentaire();
        $reservation->setUser($this->getUser());
        $comments=$commentaireRepository->findByRoomId($room->getId());
        $commentNumber=$commentaireRepository->countRoomComments($room->getId());
        $reservation->setRoom($room);
        $currentDate = new \DateTime();

        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form2 = $this->createForm(CommentaireFormType::class, $commentaire);
        $form->handleRequest($request);
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $reservation->getStartDate();
            $endDate = $reservation->getEndDate();
            if ($startDate < $currentDate) {
                $this->addFlash('error', 'Start date cannot be in the past.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);
            }
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

            $reservation->setStatut('pending');


            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        if($form2->isSubmitted() && $form2->isValid()) {
            $commentaire->setUser($this->getUser());
            $commentaire->setRoom($room);
            $commentaire->setDate(new \DateTime());
            $commentaire->setEvent(null);
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);

        }
        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'room' => $room,
            'comments' => $comments,
            'nbrComments' => $commentNumber,
        ]);
    }
    #[Route('/client/reservation', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();


        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    #[Route('/admin/reservation', name: 'admin_reservation_list')]
    public function display(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $queryBuilder = $entityManager->getRepository(Reservation::class)->createQueryBuilder('reservation');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('reservation/list.html.twig', [
            'reservations' => $pagination,
            'controller_name' => 'ReservationController',
        ]);
    }
    #[Route('/admin/reservation/delete/{id}', name: 'delete_reservation')]
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Reservation supprimée avec succès.');
        return $this->redirectToRoute('admin_reservation_list');
    }
    #[Route('/admin/reservation/refuse/{id}', name: 'Refuser_reservation')]
    public function RefuseReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
$reservation->setStatut('refused');
        $entityManager->flush();
        $this->addFlash('success', 'Reservation Réfusée avec succes.');
        return $this->redirectToRoute('admin_reservation_list');
    }
    #[Route('/admin/reservation/accept/{id}', name: 'Accept_reservation')]
    public function AcceptReservation(Reservation $reservation,EntityManagerInterface $entityManager): Response
    {
        $reservation->setStatut('accepted');
        $entityManager->flush();

        $this->addFlash('success', 'Reservation Acceptée avec succes.');
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
    #[Route('/comment/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function deleteComment(Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {

        $room = $commentaire->getRoom();
        $entityManager->remove($commentaire);
        $entityManager->flush();
        $this->addFlash('success', 'Comment deleted successfully.');
        return $this->redirectToRoute('app_reservation_new', ['id' => $room->getId()]);
    }


}
