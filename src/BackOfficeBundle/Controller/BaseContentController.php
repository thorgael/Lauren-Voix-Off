<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\BaseContent;
use AppBundle\Entity\Image;
use AppBundle\Entity\Logo;
use AppBundle\Form\BaseContentType;
use AppBundle\Form\LogoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/base-content")
 */
class BaseContentController extends Controller
{
    /**
     * @Route("/create/{category}", name="admin_base_create")
     */
    public function createAction(Request $request, $category)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:BaseContent');
        $content = $repository->findByCategory($category);

        if($content)
            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_base_read', array(
                                                    'id' => $content[0]->getId()
                                                )));
        $content = new BaseContent;

        $form = $this->createForm(BaseContentType::class, $content);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $content->setCategory($category);
            $em->persist($content);
            $em->flush();

            $this->addFlash(
                'success',
                 'Contenu modifiée avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_base_read', array(
                                                    'id' => $content->getId()
                                                )));
        }

        return $this->render('BackOfficeBundle:BaseContent:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/read/{id}", name="admin_base_read")
     * @ParamConverter("content", class="AppBundle:BaseContent")
     */
    public function readAction(BaseContent $content)
    {
        return $this->render('BackOfficeBundle:BaseContent:read.html.twig', array(
            'content' => $content
        ));
    }

    /**
     * @Route("/list", name="admin_base_list")
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:BaseContent');
        $content = $repository->findAll();

        return $this->render('BackOfficeBundle:BaseContent:list.html.twig', array(
            'content' => $content
        ));
    }

    /**
     * @Route("/logo", name="admin_base_logo")
     */
    public function logoAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Logo');
        $logo = $repository->findAll();

        if(!$logo)
            $logo = new Logo;
        else
            $logo = $logo[0];

        $form = $this->createForm(LogoType::class, $logo);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($logo);
            $em->flush();

            $this->addFlash(
                'success',
                 'Contenu modifié avec succes !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_base_logo'));
        }

        return $this->render('BackOfficeBundle:BaseContent:logo.html.twig', array(
            'logo' => $logo,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/update/{id}", name="admin_base_update")
     * @ParamConverter("content", class="AppBundle:BaseContent")
     */
    public function updateAction(BaseContent $baseContent, Request $request)
    {
        $form = $this->createForm(BaseContentType::class, $baseContent);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'success',
                 'Contenu modifié avec succes !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_base_read', array(
                                                    'id' => $baseContent->getId()
                                                )));
        }

        return $this->render('BackOfficeBundle:BaseContent:update.html.twig', array(
            "form" => $form->createView()
        ));
    }

}
