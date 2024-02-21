<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use App\Repository\CrudRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(CrudRepository $repo): Response
    {   
        $data = $repo->findall();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'datas'=>$data,
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request): Response
    {   
        $crud = new Crud();  #entity
        $form = $this->createForm(CrudType::class, $crud); #form
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {
            $sendDatabase =  $this->getDoctrine()->getManager();
            $sendDatabase->persist($crud);
            $sendDatabase->flush();

            $this->addFlash('notice', 'Soumission réussie !!');

            return $this->redirectToRoute("main");
        }

        return $this->render('main/createForm.html.twig', [
            'controller_name' => 'MainController',
            'form'=>$form->createView(),
        ]);
    }
}

