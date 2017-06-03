<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Blog;
use AppBundle\Form\Type\PageType;

class DefaultController extends Controller
{   

    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Blog::class);

        $blogs = $repository->findAllProcessedForList();

        return $this->render('default/homepage.html.twig', [
            'blogs' => $blogs,
        ]);
    }

     /**
     * @Route("/blogPost/newpage", name="new_page")
     */
    public function newPageAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(PageType::class, $blog);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setPublishedAt(new \DateTime());
            $blog->setIsProcessed(false);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($blog);// persist is used when the object is not yet in database
            $em->flush();// execute queries in database

            $contenu = new \Swift_Message();
            $contenu->setTo(['tmsdiallo@gmail.com' => 'Account Manager']);
            $contenu->setFrom('myblog@example.org');
            $contenu->setBody("
                Title: ".$blog->getTitle()."
                Contenu: ".$blog->getContenu()."
                Publised at: ".$blog->getPublishedAt()->format('Y-m-d H:i:s')."
            ");

            $this->get('mailer')->send($contenu);

            $flash = sprintf("Merci d'avoir posté le blog qui a pour titre %s.", $blog->getTitle());
            $this->addFlash('success', $flash);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('blogPost/newpage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/posts", name="page_list")
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Blog::class);

        $blogs = $repository->findAllForList();

        return $this->render('blogPost/list.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/admin/posts/{id}/", name="blog_show")
     */
    public function showAction(Blog $blog)
    {
        return $this->render('blogPost/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/admin/posts/{id}/mark-as-processed", name="blog_mark_as_processed")
     */
    public function markAsProcessedAction(Blog $blog)
    {
        if ($blog->isProcessed()) {
            $this->addFlash('error', 'This blog is already marked as processed.');
        } else {
            $blog->setIsProcessed(true);
            $this->addFlash('success', 'This blog has been marked as processed!');

            $em = $this->get('doctrine.orm.entity_manager');
            $em->flush();
        }

        return $this->redirectToRoute('blog_show', [
            'id' => $blog->getId(),
        ]);
    }

    /**
     * @Route("/posts/{id}/", name="blog_detail")
     */
    public function blogDetailAction(Blog $blog)
    {
        return $this->render('blogPost/blogDetail.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
