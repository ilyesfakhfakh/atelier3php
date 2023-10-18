<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use App\Form\StudentFormType;

class StrudentController extends AbstractController
{
    #[Route('/strudent', name: 'app_strudent')]
    public function index(): Response
    {
        return $this->render('strudent/index.html.twig', [
            'controller_name' => 'StrudentController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(StudentRepository $repo):Response
    {
        $result=$repo->findAll();
        return $this->render('strudent/test.html.twig',[
            'response'=>$result
        ]);
        

    }
    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $mr): Response
    {
        $s=new Student();
        $s->setName('test');
        $s->setAge(22);
        $s->setEmail('i@k');
        $em=$mr->getManager();
        $em->persist($s);
        $em->flush();
        return $this->redirectToRoute('fetch');
    
    }
    #[Route('/addForm', name: 'addForm' ,methods:['GET','POST'])]
    public function addF(EntityManagerInterface $em , Request $req):Response
    {
        $s=new Student(); 
        $form=$this->createForm(StudentFormType::class,$s); //creation de form
        $form->handleRequest($req); //recuperation de donnée
        if($form->isSubmitted())
        {
        $em->persist($s); 
        $em->flush();
        return $this->redirectToRoute('fetch');
        }
        $formView=$form->createView();

        return $this->render('strudent/add.html.twig',[
        'f'=>$formView
        ]);

    }
    #[Route('/update/{id}', name: 'update' ,methods:['GET','POST'])]
    public function updatee(EntityManagerInterface $em ,ManagerRegistry $doctrine,$id,Request $req,StudentRepository $repo):Response
    {
        $c=$repo->find($id);
        $form=$this->createForm(StudentFormType::class,$c); 
        $form->handleRequest($req); 
        if($form->isSubmitted())
        {
        $em=$doctrine->getManager();
        $em->flush();

         
        return $this->redirectToRoute('fetch');
        }
        $formView=$form->createView(); 

        return $this->render('strudent/add.html.twig',[
        'f'=>$formView
        ]);

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(StudentRepository $repo,$id,ManagerRegistry $mr): Response
    {
        $s=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($s);
        $em->flush();
        return $this->redirectToRoute('fetch');

    }
}
