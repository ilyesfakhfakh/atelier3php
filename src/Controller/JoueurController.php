<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\JoueurRepository;
use App\Repository\VoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    #[Route('/joueur', name: 'app_joueur')]
    public function index(): Response
    {
        return $this->render('joueur/index.html.twig', [
            'controller_name' => 'JoueurController',
        ]);
    }
    #[Route('/affiche', name: 'affiche_joueur')]
   
    function Affiche(JoueurRepository $repo){
     $obj=$repo->findAll();
     return $this->render('Joueur/listJoueur.html.twig',
     ['joueur'=>$obj]);
    }
    #[Route('/DetailVote/{id}',name:'Detail')]
    function DetailV($id,VoteRepository $repo){
        $obj=$repo->find($id);
        return $this->render(
            'Joueur/detailVote.html.twig',
        ['id'=>$obj]);
    }

    #[Route('/qb', name: 'qb')]
   
    public function qb(JoueurRepository $repo){
        $result=$repo->showJoueurs();
     //dd($result);
     return $this->render("Joueur/listJoueur.html.twig",
        ['joueur'=>$result
    ]);

    }
    #[Route('/joueur/form', name: 'joueur_add')]
    public function AddVote(ManagerRegistry $doctrine, Request $request): Response
    {
        $vote =new Vote();
        $form=$this->createForm(VoteType::class,$vote);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($vote);
            $em->flush();
            return $this-> redirectToRoute('affiche_joueur');
        }
        return $this->render('Joueur/form.html.twig',[
            'formA'=>$form->createView(),
        ]);
    }
    #[Route('/moy', name: 'moy')]
    public function afficherMoyenneVotes(JoueurRepository $repository)
    {
        
        $joueurs = $repository->findAll();

        $moyennesVotes = [];

        foreach ($joueurs as $joueur) {
            $votes = $joueur->getVotes();
            $totalVotes = 0;
            $nombreVotes = count($votes);

            foreach ($votes as $vote) {
                $totalVotes += $vote->getNoteVote();
            }

            $moyenne = $nombreVotes > 0 ? $totalVotes / $nombreVotes : 0;
            $moyennesVotes[$joueur->getId()] = $moyenne;
            
        }

        return $this->render('Joueur/listJoueur.html.twig', [
            'joueur' => $joueurs,
            'moyennesVotes' => $moyenne,
        ]);
    }

}
