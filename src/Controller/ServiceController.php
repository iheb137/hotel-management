<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                    return $this->redirectToRoute('app_service_ajout');
                }
            }
            $service->setCount(0);
            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash('success', 'Service ajouté avec succès.');
            return $this->redirectToRoute('app_service');
        }

        return $this->render('service/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
