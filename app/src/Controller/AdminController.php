<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(ContactRepository $contact): Response
    {
        return $this->render('admin/index.html.twig', [
            'contact' => $contact->findAll(),
        ]);
    }

    /**
     * @param Request $request MessageRepository $messageRepository
     * @return JsonResponse
     * @Route("/process", name="admin_process_message")
     */
    public function process(Request $request, MessageRepository $messageRepository): JsonResponse 
    {
        // get message by id
        $id = $request->request->get('id');
        $messageToProcess = $messageRepository->findOneBy(array('id' => $id));
        // toggle the processed boolean
        $messageToProcess->setProcessed(!$messageToProcess->getProcessed());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->json(true);
    }
}
