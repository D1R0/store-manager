<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Settings;
use Symfony\Component\HttpFoundation\JsonResponse;
use Automattic\WooCommerce\Client;

class AdminController extends AbstractController
{

    #[Route('/', name: 'dashboard')]
    function main()
    {
        $stores = [];
        // $woocommerce = new Client(
        //     'https://dev1.eltand.com',
        //     'ck_32c73ecb4685e1defece30851fadca8c34f22544',
        //     'cs_05623f2e9decf8556dc7a675a68695a04d71b78c',
        //     [
        //         'version' => 'wc/v3',
        //     ]
        // );

        // // Get all orders
        // $orders = $woocommerce->get('orders');
        // print_r($orders);

        // Get a specific order
        // $order = $woocommerce->get('orders/ORDER_ID');
        // print_r($order);

        // // Update an order
        // $data = [
        //     'status' => 'completed'
        // ];
        // $woocommerce->put('orders/5454', $data);

        // Delete an order
        // $woocommerce->delete('orders/ORDER_ID', ['force' => true]);

        return $this->render(
            '/Dashboard/dashboard.html.twig',
            ["stores" => $stores]

        );
    }




    #[Route('/install', name: 'install')]
    function apiInstall(ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, Security $security)
    {
        $allSettings = Settings::SETTINGS_INSTALL;
        foreach ($allSettings as $setting) {
            $em = $doctrine->getManager();
            foreach ($setting as $name => $format) {
                $check = $doctrine->getRepository(Settings::class)->findBy(["settingName" => $name]);
                if (!(count($check) > 0)) {
                    $settings = new Settings;
                    $settings->setSettingName($name);
                    $settings->setDetails($format);
                    $em->persist($settings);
                    $em->flush();
                }
            }
        }
        (new ServicesController($doctrine, $security))->createUser("Eltand", "official@eltand.com", "X81I4e*ogqL$", "ROLE_ADMIN", $userPasswordHasher);
        (new ServicesController($doctrine, $security))->createUser("demo", "demo@eltand.com", "demo", "ROLE_ADMIN", $userPasswordHasher);
        return new JsonResponse(1);
    }
}
