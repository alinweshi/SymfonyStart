<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Entity\Farmers;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

// Add this line

class FarmerController extends AbstractController
{

    public function uploadFile(Request $request, PersistenceManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $farmer = $doctrine->getRepository(Farmers::class)->find(1);

        // Get the uploaded file from the request
        $uploadedFile = $request->files->get('image');

        // Check if a file was uploaded
        if ($uploadedFile instanceof UploadedFile) {
            // Handle file upload logic here (e.g., move the file to a directory)
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

            // Move the uploaded file to the desired location
            try {
                $uploadedFile->move(
                    $this->getParameter('public_uploads_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // Handle file upload error
                // e.g., display an error message or log the error
            }

            // Assuming $farmer is an instance of your Farmers entity
            // Set the uploaded file as the image using its file path
            $farmer->setImage($fileName); // Set the file name or path in the entity
        }

        // Persist and flush the entity to save changes to the database
        // $entityManager = $doctrine->getManager();
        // $entityManager->persist($farmer);
        // $entityManager->flush();
    }

    #[Route('/farmers', name: 'farmers')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $farmers = $entityManager->getRepository(farmers::class);
        // look for *all* Product objects
        $farmers = $farmers->findAll();

        return $this->render('farmer/index.html.twig', [
            'controller_name' => 'FarmerController',
            "farmers"=>$farmers,
        ]);
    }

    #[Route('/farmer/createProfile', name: 'createProfile')]
    public function createProfile(Request $request, FileUploader $fileUploader, PersistenceManagerRegistry $doctrine, EntityManagerInterface $entityManager, ValidatorInterface $validator, SluggerInterface $slugger): Response
    {
        $farmer = new Farmers();
        $form = $this->createForm(TaskType::class, $farmer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $image = $form->get('image')->getData();
            if ($image) {
                $image = $fileUploader->upload($image);
                $farmer->setImage($image);
            }
            // Persist the entity to the database
            $entityManager->persist($farmer);
            $entityManager->flush();

            // Redirect to a success page
            return $this->redirectToRoute('farmer_success');
        }

        // Render the form
        return $this->render('farmer/createProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/farmer/success', name: 'farmer_success')]
    public function success(): Response
    {
        // Render a success page
        return $this->render('farmer/success.html.twig');
    }

    #[Route('/farmer/show/{id}', name: 'farmer_show')]

    public function show(EntityManagerInterface $entityManager, int $id)
    {
        $farmer = $entityManager->getRepository(Farmers::class)->find($id);

        if (!$farmer) {
            throw $this->createNotFoundException(
                'No farmer found for id ' . $id
            );
        }

        return $this->render('farmer/show.html.twig', ['farmer' => $farmer]);

    }
    #[Route('/farmer/{id}/edit', name: 'farmer_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Farmers $farmer, FileUploader $fileUploader): Response
    {
        // Create the form using the TaskType form type class
        $form = $this->createForm(TaskType::class, $farmer);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $image = $form->get('image')->getData();
            if ($image) {
                $image = $fileUploader->upload($image);
                $farmer->setImage($image);
            }

            // Persist changes to the database
            $entityManager->flush();
            $this->addFlash('success', 'Farmer details updated successfully!');
            // Redirect to the farmer show page
            return $this->redirectToRoute('farmer_show', ['id' => $farmer->getId()]);
        }

        // Render the edit form template with the form object
        return $this->render('farmer/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/farmer/{id}/delete', name: 'farmer_delete')]

    public function delete(EntityManagerInterface $entityManager, Farmers $farmer,int $id){
            $entityManager->remove($farmer);
            $entityManager->flush();
            $this->addFlash('delete', 'Farmer deleted successfully!');
            // Redirect to the farmer show page
            return $this->redirectToRoute('farmers');
    }

    // Other controller methods...

}
