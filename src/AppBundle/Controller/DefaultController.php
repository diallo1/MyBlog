<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        return $this->render('default/homepage.html.twig');
    }

    /**
     * @Route("/{_locale}/hello/{name}", name="hello_world",
     *  defaults = { "name": "world" }
     * )
     */
    public function helloAction($name)
    {
        $users = [
            [
                'name' => 'Jérémy',
                'email' => 'jeremy.romey@sensiolabs.com',
            ],
            [
                'name' => 'Carlota',
                'email' => 'carlota@sensiolabs.com',
            ],
            [
                'name' => 'Aurélia',
                'email' => 'aurelia@sensiolabs.com',
            ],
        ];

        return $this->render('hello/hello_world.html.twig', [
            'name' => $name,
            'users' => $users,
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
