<?php
// src/Controller/MailerController.php
namespace App\Controller;

use App\Entity\Farmers;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;


class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer, PersistenceManagerRegistry $doctrine): Response
    {
        // Fetch a Farmer entity (for demonstration)
        $farmer = $doctrine->getRepository(Farmers::class)->find(1);

        $email = (new TemplatedEmail())
            ->from(new Address('alinweshi@gmail.com', 'mailtrap'))
            ->to('alinweshi@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('WelcomeEmail.html.twig')
            ->context([
                'user_name' => $farmer->getFirstName() . "" . $farmer->getLastName(), // Assuming you have a method to get the user name from the Farmer entity
            ]);

        try {
            // Send the email
            $mailer->send($email);

            // If no exception is thrown, the email was sent successfully
            return new Response('Email was sent', 200);
            // If no exception is thrown, the email was sent successfully
        } catch (TransportExceptionInterface $e) {
            // If an exception is thrown, handle the error
            $error = $e->getMessage();
            return new Response($error = $e->getMessage());
        }

        // ...
    }
}
