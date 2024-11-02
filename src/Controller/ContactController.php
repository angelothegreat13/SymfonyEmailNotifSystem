<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index')]
    public function index(Request $request, ContactRepository $contactRepository, PaginatorInterface $paginator): Response
    {
        $query = $contactRepository->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        $contacts = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts
        ]);
    }
}
