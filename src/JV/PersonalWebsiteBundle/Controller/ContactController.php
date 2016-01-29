<?php

namespace JV\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JV\PersonalWebsiteBundle\Form\ContactType;
use JV\PersonalWebsiteBundle\Entity\Contact;

class ContactController extends Controller
{
    public function indexAction()
    {
        return $this->render('JVPersonalWebsiteBundle:Contact:index.html.twig');
    }
    
	public function contactAction()
	{
		$enquiry = new Contact();
		$form = $this->createForm(new ContactType(), $enquiry);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->submit($request);
	
		if ($form->isValid()) {
	
        $message = \Swift_Message::newInstance()
            ->setSubject('Contact feedback from joanviana.hol.es')
            ->setFrom('joanviana.webmail@gmail.com')
            ->setTo($this->container->getParameter('jv_contact.emails.contact_email'))
            ->setBody($this->renderView('JVPersonalWebsiteBundle::contactEmail.txt.twig', 
            array('enquiry' => $enquiry)));
        $this->get('mailer')->send($message);
        $em = $this->getDoctrine()->getManager();
        $em->persist($enquiry);
        $em->flush();

        //$this->get('session')->setFlash('contact-notice', 'Your feedback was successfully sent. Thank you!');

        // Redirect - This is important to prevent users re-posting
        // the form if they refresh the page
        //return $this->redirect($this->generateUrl('jv_contact_index'));
        return $this->render('JVPersonalWebsiteBundle::confirmation.html.twig', array(
        		'form' => $this->createForm(new ContactType(), new Contact())->createView()));
    }
    else{
    	return $this->render('JVPersonalWebsiteBundle::error.html.twig', array(
    			'form' => $form->createView()
    	));
    }
		}
	
		return $this->render('JVPersonalWebsiteBundle::contact.html.twig', array(
				'form' => $form->createView()
		));
	}
}