<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->redirectToRoute('admin_reservations');
    }

    #[Route('/reservations', name: 'admin_reservations')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        return $this->render('admin/reservations.html.twig', [
            'reservations' => $reservationRepository->findBy([], ['createdAt' => 'DESC']),
            'selected' => 'reservations', // ✅ IMPORTANT
        ]);
    }

    #[Route('/reservation/{id}/status', name: 'admin_reservation_status', methods: ['POST'])]
    public function updateStatus(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $status = $request->request->get('status');

        if (!in_array($status, ['pending', 'accepted', 'refused'])) {
            $this->addFlash('danger', 'Statut invalide');
            return $this->redirectToRoute('admin_reservations');
        }

        $reservation->setStatut($status);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour avec succès');

        return $this->redirectToRoute('admin_reservations');
    }
}
