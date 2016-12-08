<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Reference;
use AppBundle\Form\ReferenceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/reference")
 */
class ReferenceController extends Controller
{
    /**
     * @Route("/create", name="admin_reference_create")
     */
    public function createAction(Request $request)
    {
        $reference = new Reference;

        $form = $this->createForm(ReferenceType::class, $reference);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $reference->getImage()->setUrl('reference/');
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($reference);
            $em->flush();

            $this->addFlash(
                'success',
                 'Réference ajoutée avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_reference_list'));
        }

        return $this->container
                    ->get('templating')
                    ->renderResponse('BackOfficeBundle:Reference:create.html.twig', array(
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/delete/{id}", name="admin_reference_delete")
     * @ParamConverter("reference", class="AppBundle:Reference")
     */
    public function deleteAction(Reference $reference)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($reference);
        $em->flush();

        $this->addFlash(
                'success',
                 'Réference supprimée avec succés !'
             );

        return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_reference_list'));
    }

    /**
     * @Route("/list", name="admin_reference_list")
     */
    public function listAction()
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Reference');
        $references = $repository->findAll();

        return $this->render('BackOfficeBundle:Reference:list.html.twig', array(
            'references' => $references
        ));
    }

}
