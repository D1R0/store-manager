<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class AdminController extends AbstractController {

    #[Route('/', name: 'app_admin_index')]
    function main(){
        return $this->render(
            '/Dashboard/dashboard.html.twig',

        );
    }
}