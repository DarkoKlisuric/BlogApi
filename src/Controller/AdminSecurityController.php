<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class AdminSecurityController extends AppController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }
}