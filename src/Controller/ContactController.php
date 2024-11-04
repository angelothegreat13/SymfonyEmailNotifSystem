<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Contact;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index', methods: ['GET'])]
    public function index(Request $request, ContactRepository $contactRepository, PaginatorInterface $paginator): Response
    {
        $query = $contactRepository->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        $contacts = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts
        ]);
    }

    #[Route('/contact', name: 'contact_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $submittedToken = $request->getPayload()->get('token');

        if ($this->isCsrfTokenValid('store-contact', $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $contact = new Contact();
        $contact->setName($request->get('name'));
        $contact->setCompany($request->get('company'));
        $contact->setEmail($request->get('email'));

        $errors = $validator->validate($contact);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $entityManager->persist($contact);
        $entityManager->flush();

        return new Response('Saved new contact with id '.$contact->getId());
    }


}
