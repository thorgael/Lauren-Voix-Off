<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Biography;
use AppBundle\Form\BiographyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
* @Route("/biography")
*/
class BiographyController extends Controller
{
    /**
     * @Route("/create", name="admin_biography_create")
     */
    public function createAction(Request $request)
    {
        $biography = new Biography;

        $form = $this->createForm(BiographyType::class, $biography);

        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $biography->getImage()->setUrl('biography/');

            $em = $this->getDoctrine()->getManager();
            $em->persist($biography);
            $em->flush();

            $this->addFlash(
                'success',
                 'Biographie crée avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_biography_read'));
        }

        return $this->container
                    ->get('templating')
                    ->renderResponse('BackOfficeBundle:Biography:create.html.twig', array(
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/update/{id}", name="admin_biography_update")
     * @ParamConverter("biography", class="AppBundle:Biography")
     */
    public function updateAction(Biography $biography, Request $request)
    {
        $form = $this->createForm(BiographyType::class, $biography);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                 'Biographie modifiée avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_biography_read'));
        }

        return $this->render('BackOfficeBundle:Biography:create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/read", name="admin_biography_read")
     */
    public function readAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Biography');
        $biography = $repository->findAll();

        if(!$biography)
            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_biography_create'));
        
        return $this->render('BackOfficeBundle:Biography:read.html.twig', array(
            'biography' => $biography[0]
        ));
    }

}
