<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServiceController extends AbstractController
{
    
    /**
      *@Route("/service/{name}",name="show_Service")
     */
     public function showService($name):Response{
        return new Response("afficher service:".$name);
     }
     public function goToIndex():RedirectResponse{

        return $this->redirectToRoute('home');
     }
}
