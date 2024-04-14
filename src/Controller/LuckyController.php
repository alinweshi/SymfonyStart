<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky/number')]

    public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render('Start.html.twig', [
            'number' => $number,
        ]);
    }
    #[Route('/', name: "homepage")]
    public function index(): Response
    {
        return $this->render('Start.html.twig');
    }
    #[Route('/lucky/home', name: 'home')]
    public function home()
    {
        return $this->render('index.html.twig');
    }
    #[Route('/lucky/about', name: 'about')]
    public function about()
    {
        return $this->render('about.html.twig');
    }
    #[Route('/lucky/contact', name: 'contact')]
    public function contact()
    {
        return $this->render('contact.html.twig');
    }
    #[Route('/lucky/detail', name: 'detail')]
    public function detail()
    {
        return $this->render('detail.html.twig');
    }
    #[Route('/lucky/feature', name: 'feature')]
    public function feature()
    {
        return $this->render('feature.html.twig');
    }
    #[Route('/lucky/product', name: 'product')]
    public function product()
    {
        return $this->render('product.html.twig');
    }
    #[Route('/lucky/service', name: 'service')]
    public function service()
    {
        return $this->render('service.html.twig');
    }
    #[Route('/lucky/team', name: 'team')]
    public function team()
    {
        return $this->render('team.html.twig');
    }
    #[Route('/lucky/testimonial', name: 'testimonial')]
    public function testimonial()
    {
        return $this->render('testimonial.html.twig');
    }

}
