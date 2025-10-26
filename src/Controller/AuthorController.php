<?php

namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\SearchType;
use App\Repository\AuthorRepository;
use App\Service\HappyQuote;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/authorName/{name}', name: 'showAuthor')]
    public function showAuthor($name)
    {
        return $this->render('author/show.html.twig', ['nom' => $name]);
    }

    #[Route('/afficher', name: 'afficher')]
    public function Afficher()
    {
        return new Response('Hello');
    }

     #[Route('/list', name: 'list')]
    public function listAuthors()
    {
        $authors = Array(
            Array('id' => 1, 'picture' => './assets/images/image.png', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            Array('id' => 2, 'picture' => './assets/images/image.png', 'username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200),
            Array('id' => 3, 'picture' => './assets/images/image.png', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
       return $this->render('author/list.html.twig',['list'=>$authors]);
    }

    #[Route('/ShowAllAuthor',name:'ShowAllAuthor')]
    public function ShowAllAuthor(AuthorRepository $repo,
    HappyQuote $quote){
    //new//
        $bestQuotes= $quote->getHappyMessage();
    ///////
    $authors=$repo->findAll();
     return $this->render('author/listAuthor.html.twig'
     ,['list'=>$authors,'theBest'=>$bestQuotes
]);
    }

    #[Route('/add', name:'add')]
    public function Add(ManagerRegistry $doctrine){
       $author=new Author();
       $author->setUsername('Test');
       $author->setEmail('test@esprit.tn');
       $author->setAge(25);
       $em=$doctrine->getManager();
       $em->persist($author);
       $em->flush();
       return new Response('Author added successfully');
    }

    #[Route('/addForm', name:'addForm')]
    public function addForm(ManagerRegistry $doctrine, Request $request){
     
       $author=new Author();
       $form=$this->createForm(AuthorType::class,$author);
       $form->add('Add',SubmitType::class);
     
       $form->handleRequest($request);
       if($form->isSubmitted()){
       $em=$doctrine->getManager();
       $em->persist($author);
       $em->flush();
       return $this->redirect('ShowAllAuthor');
       }

      return $this->render('author/add.html.twig',['formulaire'=>$form->createView()]);
    }

    #[Route('/delete/{id}',name:'delete')]
    public function Delete( ManagerRegistry $doctrine, $id, AuthorRepository $repo){
           //chercher un auteur selon son ID
           $author=$repo->find($id);
           
           $em=$doctrine->getManager();
           $em->remove($author);
           $em->flush();
           return $this->redirect('ShowAllAuthor');
    }

    #[Route('/showDetailsAuthor/{id}', name:'showDetailsAuthor')]
    public function showDetailsAuthor(AuthorRepository $repo, $id){
       $author=$repo->find($id);
       return $this->render('author/ShowDetailsAuthor.html.twig',['author'=>$author]);
    }

    #[Route('/ShowAllQB', name:'ShowAllQB')]
    public function ShowAllQB(AuthorRepository $repo){
     
     $authors=$repo->findAllAuthorsQB();
     $form=$this->createForm(SearchType::class);
     return $this->render('author/listAuthor.html.twig'
     ,['list'=>$authors,'form'=>$form->createForm()]);
    }

    #[Route('/ShowAllAuthorsByEmail',name:'ShowAllAuthorsByEmail')]
    public function ShowAllAuthorsByEmail(AuthorRepository $repo){
     $authors=$repo->listAuthorByEmail();
     return $this->render('author/listAuthor.html.twig'
     ,['list'=>$authors]);

    }

    #[Route('/ShowAllAuthorsDQL',name:'ShowAllAuthorsDQL')]
    public function ShowAllAuthorsDQL(AuthorRepository $repo){
      $authors=$repo->ShowAllAuthorsDQL();
       return $this->render('author/listAuthor.html.twig'
     ,['list'=>$authors]);
    }


}
