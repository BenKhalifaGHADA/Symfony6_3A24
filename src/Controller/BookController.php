<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook',name:'addBook')]
    public function addBook(){
    
     $book=new Book();
     $form=$this->createForm(BookType::class,$book);
     $form->add('add',SubmitType::class);  
     return $this->render('book/add.html.twig',['formulaire'=>$form->createView()]);
    }
}
