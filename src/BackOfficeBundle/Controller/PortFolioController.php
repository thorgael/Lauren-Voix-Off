<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\PortFolio;
use AppBundle\Form\PortFolioType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

 /**
 * @Route("/port-folio")
 */
class PortFolioController extends Controller
{
    /**
     * @Route("/create", name="admin_portfolio_create")
     */
    public function createAction(Request $request)
    {
        $portfolio = new PortFolio;

        $form = $this->createForm(PortFolioType::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $portfolio->getImage()->setUrl('portfolio/');
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            $this->addFlash(
                'success',
                 'Ajouté au portfolio avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_portfolio_read', array(
                                                    'id' => $portfolio->getId()
                                                )));
        }

        return $this->container
                    ->get('templating')
                    ->renderResponse('BackOfficeBundle:PortFolio:create.html.twig', array(
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/list", name="admin_portfolio_list")
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:PortFolio');
        $portfolios = $repository->findAll();
    
        return $this->render('BackOfficeBundle:PortFolio:list.html.twig', array(
            'portfolios' => $portfolios
        ));
    }

    /**
     * @Route("/update/{id}", name="admin_portfolio_update")
     * @ParamConverter("portfolio", class="AppBundle:PortFolio")
     */
    public function updateAction(PortFolio $portfolio, Request $request)
    {
        $form = $this->createForm(PortFolioType::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                 'Projet modifiée avec succes !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_portfolio_read', array(
                                                    'id' => $portfolio->getId()
                                                )));
        }

        return $this->render('BackOfficeBundle:PortFolio:update.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_portfolio_delete")
     * @ParamConverter("portfolio", class="AppBundle:PortFolio")
     */
    public function deleteAction(PortFolio $portfolio)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($portfolio);
        $em->flush();

        $this->addFlash(
                'success',
                 'Le projet à bien été supprimé !'
             );

        return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_portfolio_list'));
    }

    /**
     * @Route("/read/{id}", name="admin_portfolio_read")
     * @ParamConverter("portfolio", class="AppBundle:PortFolio")
     */
    public function readAction(PortFolio $portfolio)
    {
        return $this->render('BackOfficeBundle:PortFolio:read.html.twig', array(
            'portfolio' => $portfolio
        ));
    }

}
