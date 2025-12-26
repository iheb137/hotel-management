<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/reservation/room/{id}', name: 'reservation_new')]
    public function new(
        Room $room,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $reservation = new Reservation();
        $reservation->setRoom($room);
        $reservation->setUser($this->getUser());
        $reservation->setStatut('pending');

        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('reservation_confirmation');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'room' => $room,
        ]);
    }

    #[Route('/reservation/confirmation', name: 'reservation_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('reservation/confirmation.html.twig');
    }
    #[Route('/reservation/{id}', name: 'app_reservation_detail')]
    public function detail(Reservation $reservation): Response
    {
        // Sécurité : l’utilisateur ne peut voir que ses réservations
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('reservation/detail.html.twig', [
            'reservation' => $reservation,
        ]);
    }

}
