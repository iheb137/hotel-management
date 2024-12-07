<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('admin/event/ajout', name: 'ajout_event')]
    public function ajoutEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event= new Event();
        $form= $this->createForm(EventFormType::class, $event);
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

                    $event->setThumbnail('/uploads/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the thumbnail.');
                    return $this->redirectToRoute('ajout_event');
                }
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Event ajouté avec succès.');
            return $this->redirectToRoute('all_event');
        }
        return $this->render('event/ajout.html.twig', [
            'EventForm'=> $form->createView(),
            'is_edit'=> false,
            'selected'=> 'evenement']);
    }

    #[Route('admin/event/all', name: 'all_event')]
    public function allEvents(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager->getRepository(Event::class)->findAll();
        return $this->render('event/list_events.html.twig', [
            'events' => $events,
            'selected'=> 'evenement'
        ]);
    }

    #[Route('admin/event/edit/{id}', name: 'edit_event')]
    public function editEvent(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        $form= $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            $this->addFlash('success', 'Evenement modifié avec succès.');
            return $this->redirectToRoute('all_event');
        }
        return $this->render('event/ajout.html.twig', [
            'EventForm'=> $form->createView(),
            'is_edit'=> true,
            'eventId' => $event->getId(),
        ]);
    }

    #[Route('admin/event/delete/{id}', name: 'delete_event')]
    public function deleteEvent(EntityManagerInterface $entityManager, Event $event): Response
    {
        $entityManager->remove($event);
        $entityManager->flush();
        $this->addFlash('success', 'Evenement supprimé avec succès.');
        return $this->redirectToRoute('all_event');
    }

}
