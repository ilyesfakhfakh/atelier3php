<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/listA', name: 'list_author')]
    public function listAuthor(AuthorRepository $rep):Response{
        $result=$rep->findAll();
        return $this->render("author/authorList.html.twig",
        ['author'=>$result
    ]);
    }
    #[Route('/add', name: 'add_author')]
    public function add(ManagerRegistry $mr):Response{
        $a=new Author();
        $a->setUsername("hama");
        $a->setEmail("hama@gmail.com");
        $em=$mr->getManager();
        $em->persist($a);
        $em->flush();
        return $this->redirectToRoute("list_author");
    }

    #[Route('/addF', name: 'addF_author')]
    public function addF(ManagerRegistry $mr,Request $req):Response{
        $a=new Author();
        $form=$this->createForm(AuthorType::class,$a);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$mr->getManager();
            $em->persist($a);
            $em->flush();
            return $this->redirectToRoute("list_author");
        }
        return $this->render("author/form.html.twig",
        ['formA'=>$form->createView(),
    ]);
    }
    #[Route('/update/{id}', name: 'update' ,methods:['GET','POST'])]
    public function updatee(EntityManagerInterface $em ,ManagerRegistry $doctrine,$id,Request $req,AuthorRepository $repo):Response
    {
        $c=$repo->find($id);//recuperation
        $form=$this->createForm(AuthorType::class,$c); 
        $form->handleRequest($req); 
        if($form->isSubmitted())
        {
        $em=$doctrine->getManager();
        $em->flush();

         
        return $this->redirectToRoute('list_author');
        }
        $formView=$form->createView(); 

        return $this->render('author/form.html.twig',[
        'formA'=>$formView
        ]);

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(AuthorRepository $repo,$id,ManagerRegistry $mr): Response
    {
        $s=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($s);
        $em->flush();
        return $this->redirectToRoute('list_author');

    }
    #[Route('/qb1',name:'listAuthorbyemail')]
    public function qb(AuthorRepository $repo){
        $result=$repo->listAuthorByEmail();
     //dd($result);
     return $this->render("author/authorList.html.twig",
        ['author'=>$result
    ]);

    }


}
