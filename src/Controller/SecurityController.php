<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function index()
    {
        return $this->render('security/login_test.html.twig');
    }
    /**
     * @Route ("/logout", name="security_logout")
     */
    public function logout()
    { }
}
