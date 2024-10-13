<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Service\MongoDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notes', name:'note')]
class NoteController extends AbstractController
{
    public function __construct(private MongoDBService $mongoDBService)
    {
        $this->mongoDBService = $mongoDBService;
    }

    
    #[Route('/new', name:'_new')]
    public function new(Request $request): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->mongoDBService->newNote($note);
            return $this->redirectToRoute('note_all');
        }

        return $this->render('note/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/all', name:'_all')]
    public function show()
    {
        $notes = $this->mongoDBService->getAllNotes();

        return $this->render('note/list.html.twig', [
            'notes' => $notes
        ]);
    }
}
