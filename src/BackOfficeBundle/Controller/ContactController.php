<?php

namespace BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 *@Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/list", name="admin_contact_list")
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Contact');
        $contactArchived = $repository->findByArchived(true);
        $contactNotArchived = $repository->findByArchived(false);

        return $this->render('BackOfficeBundle:Contact:list.html.twig', array(
            'contactArchived' => $contactArchived,
            'contactNotArchived' => $contactNotArchived
        ));
    }

    /**
     * @Route("/read/{id}", name="admin_contact_read")
     * @ParamConverter("contact", class="AppBundle:Contact")
     */
    public function readAction(Contact $contact)
    {
        return $this->render('BackOfficeBundle:Contact:read.html.twig', array(
            'contact' => $contact
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_contact_delete")
     * @ParamConverter("contact", class="AppBundle:Contact")
     */
    public function deleteAction(Contact $contact)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_contact_list'));
    }

    /**
     * @Route("/archive/{id}/{where}", name="admin_contact_archive")
     * @ParamConverter("contact", class="AppBundle:Contact")
     */
    public function archiveAction(Contact $contact, $where = null)
    {
        if($contact->getArchived())
            $contact->setArchived(false);
        else
            $contact->setArchived(true);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        if($where == "list")
            return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_contact_list'));

        return new RedirectResponse($this->container
                                             ->get('router')
                                             ->generate('admin_contact_read', array(
                                                    'id' => $contact->getId()
                                                )));
    }

}
