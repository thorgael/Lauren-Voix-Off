<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\BaseContent;
use AppBundle\Entity\Footer;
use AppBundle\Entity\Biography;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:PortFolio');
        $portfolios = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository('AppBundle:BaseContent');
        $baseContent = $repository->findAll();

        for($i=0; $i <count($baseContent); $i++)
        {
            if($baseContent[$i]->getCategory() == BaseContent::TITRE)
            {
                $title = $baseContent[$i];
            }
            elseif($baseContent[$i]->getCategory() == BaseContent::DESCRIPTION)
            {
                $descript = $baseContent[$i];
            }
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:Biography');
        $biography = $repository->findAll();
        if($biography[0])
            $biography = $biography[0];

        $repository = $this->getDoctrine()->getRepository('AppBundle:Reference');
        $references = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository('AppBundle:Concept');
        $concept = $repository->findAll();
        if($concept[0])
            $concept = $concept[0];


        $repository = $this->getDoctrine()->getRepository('AppBundle:Logo');
        $logo = $repository->findAll();
        if($logo[0])
            $logo = $logo[0];

        $repository = $this->getDoctrine()->getRepository('AppBundle:Footer');
        $footers = $repository->findAll();

        $footerLeft = null;
        // $footerCenter = null;
        $footerRight = null;

        for ($i=0; $i < count($footers); $i++)
        {
            if($footers[$i]->getType() == Footer::LEFT)
            {
                $footerLeft = $footers[$i];
            }
            // elseif($footers[$i]->getType() == Footer::CENTER)
            // {
            //     $footerCenter = $footers[$i];
            // }
            elseif($footers[$i]->getType() == Footer::RIGHT)
            {
                $footerRight = $footers[$i];
            }
        }



        return $this->container
			    	->get('templating')
		    		->renderResponse('AppBundle:Default:index.html.twig', array(
                            'portfolios' => $portfolios,
                            'logo' => $logo,
                            'title' => $title,
                            'description' => $descript,
                            'biography' => $biography,
                            'references' => $references,
                            'concept' => $concept,
                            'footerLeft' => $footerLeft,
                            // 'footerCenter' => $footerCenter,
                            'footerRight' => $footerRight
                        ));
    }
}
