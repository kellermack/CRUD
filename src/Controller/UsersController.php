<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\CrudType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(): Response
    {   
        $data = $this->getDoctrine()->getRepository(Users::class)->findAll();
        return $this->render('users/index.html.twig', [
            'list' => $data
        ]);
    }

    /**
     * @Route("create", name = "create")
     */
    public function create(Request $request){
        $users = new Users();
        $form = $this->createForm(CrudType::class, $users);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($users);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully');

            return $this->redirectToRoute('users');
        }
        return $this->render('users/create.html.twig',['form' => $form->createView()]);
    }

    /**
     * @Route("/update/{id}", name = "update")
     */
    public function update(Request $request, $id){
        $users = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $form = $this->createForm(CrudType::class, $users);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($users);
            $em->flush();

            $this->addFlash('notice','Updated Successfully');

            return $this->redirectToRoute('users');
        }
        return $this->render('users/update.html.twig',['form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name = "delete")
     */
    public function delete($id){
        $data = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('users');

    }


}
