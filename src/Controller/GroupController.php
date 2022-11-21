<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GroupController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/create-group", name="create_group")
     */
    public function index(Request $request)
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $group = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }

        return $this->render('group/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/{id}", name="group_details", requirements={"id"="\d+"})
     */
    public function dealsOfGroup(int $id){
        $group = $this->getDoctrine()
                ->getRepository(Group::class)
                ->find($id);

            return $this->render('group/details.html.twig', [
                'group' => $group,
            ]);
    }

    public function groups(){
        $groups = $this->getDoctrine()
                ->getRepository(Group::class)
                ->findAll();

        return $groups;
    }
}
