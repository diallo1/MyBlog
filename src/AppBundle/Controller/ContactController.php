<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Contact;
use AppBundle\Form\Type\ContactType;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setSentAt(new \DateTime());
            $contact->setIsProcessed(false);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($contact);// persist is used when the object is not yet in database
            $em->flush();// execute queries in database

            $message = new \Swift_Message();
            $message->setTo(['jeremy.romey@sensiolabs.com' => 'Account Manager']);
            $message->setFrom('site@example.org');
            $message->setSubject('Contact from '. $contact->getName());
            $message->setBody("
                Name: ".$contact->getName()."
                Email: ".$contact->getEmail()."
                Sent at: ".$contact->getSentAt()->format('Y-m-d H:i:s')."
                Subject: ".$contact->getSubject()."
                Message: ".$contact->getMessage()."
            ");

            $this->get('mailer')->send($message);

            // $flash = 'Merci '.$contact->getName().' de nous avoir contacté pour le sujet '.$contact->getSubject().'.';
            $flash = sprintf('Merci %s de nous avoir contacté pour le sujet %s.', $contact->getName(), $contact->getSubject());
            $this->addFlash('success', $flash);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/contact/list", name="contact_list")
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Contact::class);

        // $contacts = $repository->findAll();
        $contacts = $repository->findAllForList();

        return $this->render('contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/admin/contact/{id}/", name="contact_show")
     */
    public function showAction(Contact $contact)
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/admin/contact/{id}/mark-as-processed", name="contact_mark_as_processed")
     */
    public function markAsProcessedAction(Contact $contact)
    {
        if ($contact->isProcessed()) {
            $this->addFlash('error', 'This contact is already marked as processed.');
        } else {
            $contact->setIsProcessed(true);
            $this->addFlash('success', 'This contact has been marked as processed!');

            $em = $this->get('doctrine.orm.entity_manager');
            $em->flush();
        }

        return $this->redirectToRoute('contact_show', [
            'id' => $contact->getId(),
        ]);
    }
}
