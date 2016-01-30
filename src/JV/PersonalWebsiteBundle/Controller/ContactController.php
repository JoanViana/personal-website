<?php

namespace JV\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JV\PersonalWebsiteBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactController extends Controller
{
    public function indexAction()
    {
        return $this->render('JVPersonalWebsiteBundle:Contact:index.html.twig');
    }
    
	public function contactAction(Request $request)
	{
		$enquiry = new Contact();
		
		$form = $this->createFormBuilder($enquiry)
		    ->add('firstName', TextType::class, array(
                    'required'    => true,
                    'label' => 'First Name',
                    //'placeholder' => 'Enter your first name',
                    'empty_data'  => null
                ))
                
            ->add('lastName', TextType::class, array(
                    'required'    => false,
                    'label' => 'Last Name',
                    //'placeholder' => 'Enter your last name',
                    'empty_data'  => 'NULL'
                ))
            ->add('email', EmailType::class, array(
                    'required'    => false,
                    'label' => 'Email',
                    //'placeholder' => 'Enter your email',
                    'empty_data'  => null
                ))
            ->add('subject', TextType::class, array(
                    'required'    => true,
                    'label' => 'Subject',
                    //'placeholder' => 'Enter a subject',
                    'empty_data'  => null
                ))
            ->add('body', TextareaType::class, array(
                    'required'    => true,
                    'label' => 'Message',
                    //'placeholder' => 'Enter a subject',
                    'empty_data'  => null
                ))
            ->add('save', SubmitType::class, array('label' => 'Send Feedback'))
            ->getForm();
            
 if ($request->isMethod('POST')) {
        $form->bind($request);

        if ($form->isValid()) {

                /*
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact feedback from joanviana.hol.es')
                    ->setFrom('joanviana.webmail@gmail.com')
                    ->setTo($this->container->getParameter('jv_contact.emails.contact_email'))
                    ->setBody($this->renderView('JVPersonalWebsiteBundle::contactEmail.txt.twig', 
                    array('enquiry' => $enquiry)));
                $this->get('mailer')->send($message);
                */
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
        
            return $this->render('JVPersonalWebsiteBundle::contact.html.twig', array(
    				'form' => $form->createView()));
    	}
		
	
		return $this->render('JVPersonalWebsiteBundle::contact.html.twig', array(
				'form' => $form->createView()));
	}
	
}