<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Message;
use App\Form\ContactType;
use App\Form\MessageType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, ContactRepository $contactRepository): Response
    {
        $message = new Message();
        $contact = new Contact();
        $message->setAuthor($contact);
        $success = ""; // message displayed if success

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        $get_author = $contactRepository->findOneBy(array('mail' => $form->get('author')->get('mail')->getData()));

        
        if ($form->isSubmitted() && $form->isValid()) {

            // if email already exist
            if ($get_author) {
                $message->setAuthor($get_author);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message->getAuthor());
            $entityManager->persist($message);
            $entityManager->flush();

            $success = "Message envoyé !";

            // create json file
            $fs = new Filesystem();
            try {
                $filename = $message->getAuthor()->getName() . '-' . $message->getDate()->format('d-m-Y-h-m-s') . '.json';
                $fs->dumpFile($this->getParameter('message_dir') . $filename, json_encode($message->tojson()));
            } catch (\Throwable $th) {
                throw $th;
            }

        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'success' => $success
        ]);
    }
}
