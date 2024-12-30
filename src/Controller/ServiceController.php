<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceFormType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{

    #[Route('/service/ajout', name: 'app_service_ajout')]
    public function ajoutService(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceFormType::class, $service);
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

                    $service->setThumbnail('/uploads/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the thumbnail.');
                    return $this->redirectToRoute('app_service_list');
                }
            }
            $service->setCount(0);
            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash('success', 'Service ajouté avec succès.');
            return $this->redirectToRoute('app_service_list');
        }

        return $this->render('service/ajout.html.twig', [
            'ServiceForm' => $form->createView(),            'is_edit' => false,

        ]);
    }
    #[Route('/admin/service/edit/{id}', name: 'edit_service')]
    public function editService(Service $service, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceFormType::class, $service);
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

                    $service->setThumbnail('/uploads/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the thumbnail.');
                    return $this->redirectToRoute('app_service_list', ['id' => $service->getId()]);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Service modifiée avec succès.');
            return $this->redirectToRoute('app_service_list');
        }

        return $this->render('service/ajout.html.twig', [
            'ServiceForm' => $form->createView(),
            'is_edit' => true,
            'serviceId' => $service->getId()
        ]);
    }

    #[Route('/admin/service_list', name: 'app_service_list')]
function listService(Request $request, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        return $this->render('service/list.html.twig', ['services' => $services]);
    }
    #[Route('/admin/service/delete/{id}', name: 'delete_service')]
    public function deleteService(Service $service, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($service);
        $entityManager->flush();
        $this->addFlash('success', 'Service supprimée avec succès.');
        return $this->redirectToRoute('app_service_list');


    }


}
