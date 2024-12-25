<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Room;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\ClientAccountFormType;

use Symfony\Component\HttpFoundation\Request;

class ClientController extends AbstractController
{
    #[Route('home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $rooms = $entityManager->getRepository(Room::class)->findAll();
        $events = $entityManager->getRepository(Event::class)->findAll();

        return $this->render('client/index-1.html.twig', [
            'rooms' => $rooms,
            'events' => $events,
            'controller_name' => 'ClientController',
        ]);
    }
#[Route('/client/account', name: 'account_edit')]
public function editAccount(ReservationRepository $reservationRepository,
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher,
    Security $security
): Response {
    $user = $security->getUser();

    if (!$user instanceof User) {
        throw $this->createAccessDeniedException('You must be logged in to access this page.');
    }
    $reservations = $reservationRepository->findBy(['user' => $user]);
    $form = $this->createForm(ClientAccountFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $oldPassword = $form->get('oldPassword')->getData();
        $newPassword = $form->get('password')->getData();
        $newImage= $form->get('image')->getData();

        if ($newImage) {
            $originalFilename = pathinfo($newImage->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $newImage->guessExtension();

            try {
                $newImage->move(
                    $this->getParameter('photo_dir'),
                    $newFilename
                );

                $user->setImage('/uploads/images/' . $newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Failed to upload the image.');
                return $this->redirectToRoute('account_edit');
            }
        }
        if ($newPassword) {
            if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('error', 'The current password is incorrect.');
                return $this->redirectToRoute('account_edit');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Your account has been updated successfully.');

        return $this->redirectToRoute('account_edit');
    }

    return $this->render('client/account.html.twig', [
        'form' => $form->createView(),'reservations' => $reservations,
    ]);
}

}
