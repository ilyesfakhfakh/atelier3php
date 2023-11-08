<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/fetchBook', name: 'fetch_book')]
    public function fetch(BookRepository $rep){
        $result=$rep->findAll();
        return $this->render("book/book.html.twig",
        ["book"=>$result,
    ]);
    }
    #[Route('/addBook', name: 'add_book')]
    public function addF(ManagerRegistry $mr,Request $req):Response{
        $b=new Book();
        $form=$this->createForm(BookType::class,$b);
        $form->handleRequest($req);
        if($form->isSubmitted() ){
            $b->setPublished(true);
            $author=$b->getAuthor();
            $author->setNbBooks($author->getNbBooks()+1);
            $em=$mr->getManager();
            $em->persist($b);
            $em->flush();
            return $this->redirectToRoute("fetch_book");
        }
        return $this->render("book/addBook.html.twig",
        ['formB'=>$form->createView(),
    ]);
    }
    #[Route('/updateBook/{ref}', name: 'updateBook',methods:['GET','POST'])]
    public function updateBook(EntityManagerInterface $em,ManagerRegistry $mr,BookRepository $repo,Request $req,$ref):Response{
        
        $b=$repo->find($ref);
        $form=$this->createForm(BookType::class,$b);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            
            $author=$b->getAuthor();
            $author->setNbBooks($author->getNbBooks()+1);
            $em=$mr->getManager();
            $em->persist($b);
            $em->flush();
            return $this->redirectToRoute("fetch_book");
        }
        return $this->render("book/updateBook.html.twig",
        ['formB'=>$form->createView(),
    ]);
    }

    #[Route('/deleteBook/{ref}', name: 'deleteBook')]
    public function delete(BookRepository $repo,$ref,ManagerRegistry $mr): Response
    {
        $book=$repo->find($ref);
        $em=$mr->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('fetch_book');

    }
    #[Route('/details/{ref}', name: 'app_author_details')]
    public function getBooksByAuthor($ref,BookRepository $repo){
            $books = $repo->getBooksByAuthor($ref);
            return $this->render('book/details.html.twig', [
            'books' => $books,
        ]);
    }
    #[Route('/qbBook/{ref}',name:'listAuthorbyemail')]
    public function qb(BookRepository $repo,$ref){
        $result=$repo->searchBookByRef($ref);
     //dd($result);
     return $this->render("book/book.html.twig",
        ['author'=>$result
    ]);

    }
}
