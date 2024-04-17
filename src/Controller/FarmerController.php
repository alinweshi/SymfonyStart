<?php

namespace App\Controller;

use App\Entity\Farmers;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

// Add this line

class FarmerController extends AbstractController
{
    #[Route('/farmer', name: 'app_farmer')]
    public function index(): Response
    {
        return $this->render('farmer/index.html.twig', [
            'controller_name' => 'FarmerController',
        ]);
    }

    // #[Route('/farmer/createProfile', name: 'createProfile')]
    // public function createProfile()
    // {
    //     return $this->render('farmer/createProfile.html.twig');
    // }

    #[Route('/farmer/createProfile', name: 'createProfile')]

    public function createProfile(Environment $twig, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $farmer = new Farmers();
        $form = $this->createForm(TaskType::class, $farmer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve data from the form
            $farmer = $form->getData();

            // // Process the form data
            // $firstName = $request->request->get('firstName');
            // $lastName = $request->request->get('lastName');
            // $email = $request->request->get('email');
            // $phone = $request->request->get('phone');
            // $password = $request->request->get('password');
            // $image = $request->request->get('image');
            // $errors = $validator->validate($farmer);

            // // Create a new Farmer entity
            // $farmer->setFirstName($firstName);
            // $farmer->setLastName($lastName);
            // $farmer->setEmail($email);
            // $farmer->setPhone($phone);
            // $farmer->setPassword($password);
            // $farmer->setImage($image);
            // // Set other properties as needed
            // // $farmer->setImage($image);

            // Persist the entity to the database
            $entityManager->persist($farmer);
            $entityManager->flush();

            // Redirect to a success page or return a success response
            return $this->redirectToRoute('farmer_success');
        }

        // Render the form
        return new Response($twig->render('farmer/createProfile.html.twig', ['form' => $form->createView()])
        );

    }

    #[Route('/farmer/success', name: 'farmer_success')]

    public function success(): Response
    {
        // Render a success page
        return $this->render('farmer/success.html.twig');
    }
    // public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    // {
    //     // Get form data from the request
    //     $firstName = $request->request->get('firstName');
    //     $lastName = $request->request->get('lastName');
    //     $email = $request->request->get('email');
    //     $phone = $request->request->get('phone');
    //     $password = $request->request->get('password');

    //     // Validate each form field individually
    //     $errors = [
    //         'firstName' => $validator->validate($firstName, [
    //             new NotBlank(['message' => 'Please enter your first name']),
    //             new Length(
    //                 min: 10,
    //                 max: 50,
    //                 minMessage: 'Your first name must be at least {{ limit }} characters long',
    //                 maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    //             ),
    //         ]),
    //         'lastName' => $validator->validate($lastName, [
    //             new NotBlank(['message' => 'Please enter your last name']),
    //         ]),
    //         'email' => $validator->validate($email, [
    //             new NotBlank(['message' => 'Please enter your email']),
    //             new Email(['message' => 'Please enter a valid email']),
    //         ]),
    //         'phone' => $validator->validate($phone, [
    //             new NotBlank(['message' => 'Please enter your phone number']),
    //         ]),
    //         'password' => $validator->validate($password, [
    //             new NotBlank(['message' => 'Please enter your password']),
    //             new PasswordStrength([
    //                 'minScore' => PasswordStrength::STRENGTH_VERY_STRONG, // Very strong password required
    //                 'message' => 'Your password is not strong enough',
    //             ]),
    //         ]),
    //     ];

    //     // Check if there are any errors
    //     foreach ($errors as $fieldErrors) {
    //         if (count($fieldErrors) > 0) {
    //             // If there are errors, return them as a JSON response
    //             return new Response($fieldErrors);
    //         }
    //     }

    //     // If no errors, continue with saving the farmer entity
    //     $farmer = new Farmers();
    //     $farmer->setFirstName($firstName);
    //     $farmer->setLastName($lastName);
    //     $farmer->setEmail($email);
    //     $farmer->setPhone($phone);
    //     $farmer->setPassword($password);

    //     $entityManager->persist($farmer);
    //     $entityManager->flush();

    //     // Return a success response
    //     return $this->json(['message' => 'Farmer created successfully']);
    // }

    // tell Doctrine you want to (eventually) save the farmer (no queries yet)
    //     $entityManager->persist($farmer);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     return new Response('Saved new farmer with id ' . $farmer->getId());
    // }

    public function viewProfile()
    {
        return $this->render('farmer/viewProfile.html.twig');
    }

    public function editProfile()
    {
        return $this->render('farmer/editProfile.html.twig');
    }
}
