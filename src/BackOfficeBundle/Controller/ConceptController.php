<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Concept;
use AppBundle\Form\ConceptType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/concept")
 */
class ConceptController extends Controller
{
    /**
     * @Route("/update", name="admin_concept_update")
     */
    public function updateAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Concept');
        $concept = $repository->findAll();

        if(!$concept)
            $concept = new Concept;
        else
            $concept = $concept[0];

        $form = $this->createForm(ConceptType::class, $concept);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($concept);
            $em->flush();

            $this->addFlash(
                'success',
                 'Concept modifié avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_concept_read'));
        }

        return $this->render('BackOfficeBundle:Concept:update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/read", name="admin_concept_read")
     */
    public function readAction()
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Concept');
        $concept = $repository->findAll();

        if(!$concept)
            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_concept_update'));

        return $this->render('BackOfficeBundle:Concept:read.html.twig', array(
            'concept' => $concept
        ));
    }

}
