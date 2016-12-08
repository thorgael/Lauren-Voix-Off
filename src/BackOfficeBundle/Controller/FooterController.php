<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Footer;
use AppBundle\Entity\FooterImage;
use AppBundle\Form\FooterType;
use AppBundle\Entity\Image;
use AppBundle\Form\FooterImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
     * @Route("/footer")
     */
class FooterController extends Controller
{
    /**
     * @Route("/create/{type}", name="admin_footer_create")
     */
    public function createAction(Request $request, $type)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Footer');
        $footer = $repository->findOneByType($type);

        if($footer)
            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_footer_read', array(
                                                    'id' => $footer->getId()
                                                )));

        $footer = new Footer;
        $footer->setType($type);

        $form = $this->createForm(FooterType::class, $footer);

        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($footer);
            $em->flush();

            $this->addFlash(
                'success',
                'Contenu du pied de page ajouté avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_footer_list'));
        }

        return $this->container
                    ->get('templating')
                    ->renderResponse('BackOfficeBundle:Footer:create.html.twig', array(
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/update/{id}", name="admin_footer_update")
     * @ParamConverter("footer", class="AppBundle:Footer")
     */
    public function updateAction(Request $request, Footer $footer)
    {
        $form = $this->createForm(FooterType::class, $footer);

        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($footer);
            $em->flush();

            $this->addFlash(
                'success',
                'Contenu du pied de page modifié avec succés !'
             );

            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_footer_read', array(
                                                    'id' => $footer->getId()
                                                )));
        }

        return $this->container
                    ->get('templating')
                    ->renderResponse('BackOfficeBundle:Footer:create.html.twig', array(
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/read/{id}", name="admin_footer_read")
     * @ParamConverter("footer", class="AppBundle:Footer")
     */
    public function readAction(Footer $footer)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:FooterImage');

        $footerImages = $repository->findByFooter($footer);

        return $this->render('BackOfficeBundle:Footer:read.html.twig', array(
            'footer' => $footer,
            'footerImages' => $footerImages
        ));
    }

    // /**
    //  * @Route("/image/{id}", name="admin_footer_image")
    //  * @ParamConverter("footer", class="AppBundle:Footer")
    //  */
    // public function imageAction(Footer $footer)
    // {
    //     $repository = $this->getDoctrine()->getRepository('AppBundle:FooterImage');

    //     $footerImages = $repository->findByFooter($footer);

    //     return $this->render('BackOfficeBundle:Footer:image.html.twig', array(
    //         'footerImages' => $footerImages,
    //         'footer' => $footer
    //     ));
    // }

    // /**
    //  * @Route("/image/add/{id}", name="admin_footer_image_add")
    //  * @ParamConverter("footer", class="AppBundle:Footer")
    //  */
    // public function imageAddAction(Request $request, Footer $footer)
    // {
    //     $repository = $this->getDoctrine()->getRepository('AppBundle:FooterImage');

    //     $images = $repository->findByFooter($footer);
    //     if(count($images) >= 10)
    //     {
    //         $this->addFlash(
    //             'warning',
    //             'Cet élément possède déjà le nombre maximum d\'image !'
    //         );

    //         return new RedirectResponse($this->container
    //                                          ->get('router')
    //                                          ->generate('admin_footer_list'));
    //     }

    //     $footerImage = new FooterImage;
    //     $form = $this->createForm(FooterImageType::class, $footerImage);

    //     $form->handleRequest($request);

    //     if ($form->isValid()) {
            
    //         $em = $this->getDoctrine()->getManager();
            
    //         $footerImage->getImage()->setUrl('footer/');
    //         $footerImage->setFooter($footer);

    //         $em->persist($footerImage);
    //         $em->flush();

    //         $this->addFlash(
    //             'success',
    //             'Image ajouté avec succés !'
    //         );

    //         return new RedirectResponse($this->container
    //                                          ->get('router')
    //                                          ->generate('admin_footer_image', array(
    //                                             'id' => $footer->getId()
    //                                         )));
    //     }

    //     return $this->render('BackOfficeBundle:Footer:image-add.html.twig', array(
    //         "form" => $form->createView(),
    //         'footerId' => $footer->getId()
    //     ));
    // }

    // /**
    //  * @Route("/image/delete/{id}", name="admin_footer_image_delete")
    //  * @ParamConverter("footer", class="AppBundle:Footer")
    //  */
    // public function imageDeleteAction(Footer $footer)
    // {
        
    //     return $this->render('BackOfficeBundle:Footer:image.html.twig', array(
    //     ));
    // }

    /**
     * @Route("/list", name="admin_footer_list")
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Footer');
        $footers = $repository->findAll();

        return $this->render('BackOfficeBundle:Footer:list.html.twig', array(
            'footers' => $footers
        ));
    }

}
