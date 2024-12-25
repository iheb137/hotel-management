<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

class RoomController extends AbstractController
{


    #[Route('/admin/room/ajout', name: 'ajout_room')]
    public function ajout_room(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form['thumbnail']->getData();

            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('photo_dir'),
                        $newFilename
                    );

                    $room->setThumbnail('/uploads/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the thumbnail.');
                    return $this->redirectToRoute('ajout_room');
                }
            }

            $entityManager->persist($room);
            $entityManager->flush();

            $this->addFlash('success', 'Chambre ajoutée avec succès.');
            return $this->redirectToRoute('all_room');
        }

        return $this->render('room/ajout.html.twig', [
            'RoomForm' => $form->createView(),
            'is_edit' => false,
            'selected' => 'chambre'
        ]);
    }
    #[Route('/roomlist', name: 'app_room')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $entityManager->getRepository(Room::class)->createQueryBuilder('room');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('room/index.html.twig', [
            'rooms' => $pagination,
            'controller_name' => 'RoomController',
        ]);
    }


    #[Route('/admin/room/all', name: 'all_room')]
    public function all_room(EntityManagerInterface $entityManager): Response
    {
        $rooms = $entityManager->getRepository(Room::class)->findAll();

        return $this->render('room/list_rooms.html.twig', [
            'rooms' => $rooms,
            'selected'=>'chambre'
        ]);
    }

    #[Route('/admin/room/delete/{id}', name: 'delete_room')]
    public function deleteRoom(Room $room, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($room);
        $entityManager->flush();

        $this->addFlash('success', 'Chambre supprimée avec succès.');
        return $this->redirectToRoute('all_room');
    }

    #[Route('/admin/room/edit/{id}', name: 'edit_room')]
    public function editRoom(Room $room, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form['thumbnail']->getData();

            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('photo_dir'),
                        $newFilename
                    );

                    // Update the entity's thumbnail path
                    $room->setThumbnail('/uploads/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the thumbnail.');
                    return $this->redirectToRoute('edit_room', ['id' => $room->getId()]);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Chambre modifiée avec succès.');
            return $this->redirectToRoute('all_room');
        }

        return $this->render('room/ajout.html.twig', [
            'RoomForm' => $form->createView(),
            'is_edit' => true,
            'roomId' => $room->getId()
        ]);
    }

}
