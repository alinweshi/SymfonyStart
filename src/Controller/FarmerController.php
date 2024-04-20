<?php

namespace App\Controller;

use App\Entity\Farmers;
use App\Form\TaskType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/farmer', name: 'app_farmer')]
    public function index(): Response
    {
        return $this->render('farmer/index.html.twig', [
            'controller_name' => 'FarmerController',
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

    // public function viewProfile()
    // {
    //     return $this->render('farmer/viewProfile.html.twig');
    // }

    // public function editProfile()
    // {
    //     return $this->render('farmer/editProfile.html.twig');
    // }
}
