<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Settings;
use App\Entity\Store;
use App\Form\Type\StoreType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Automattic\WooCommerce\Client;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{

    #[Route('/', name: 'dashboard')]
    function main(ManagerRegistry $doctrine, Security $security)
    {
        $stores = $doctrine->getRepository(Store::class)->findBy(["owner" => $security->getUser()]);
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

    #[Route('/add/store', name: 'addStore')]

    function addStore(ManagerRegistry $doctrine, Request $request, Security $security)
    {
        $store = new Store();
        $storeForm = $this->createForm(StoreType::class, $store);
        $storeForm->handleRequest($request);

        if ($storeForm->isSubmitted() && $storeForm->isValid()) {
            $type = $storeForm->get("type")->getData();
            switch ($type) {
                case Store::WooCommerce_TYPE:
                    $details = Store::WooCommerce_Details;
                    $details['domain'] = $storeForm->get("domain")->getData();
                    $details['customer_key'] = $storeForm->get("ck")->getData();
                    $details['customer_secret'] = $storeForm->get("cs")->getData();
                    // $store = $this->checkStore($details['domain'], $details['customer_key'], $details['customer_key']);
                    $store->setDetails($details);
                    $store->setOwner($security->getUser());
                    $em = $doctrine->getManager();
                    $em->persist($store);
                    $em->flush();
                    return $this->redirectToRoute('dashboard');

                    break;
                default:
                    return false;
            }
        }

        return $this->render(
            '/Dashboard/addStore.html.twig',
            ['storeForm' => $storeForm]
        );
    }
    #[Route('/store/{id}', name: 'singleStore')]

    function singleStore(ManagerRegistry $doctrine, Request $request, Security $security, $id)
    {
        $store = $doctrine->getRepository(Store::class)->findOneBy(['id' => $id]);
        $details = $store->getDetails();
        $woocommerce = new Client(
            $details['domain'],
            $details['customer_key'],
            $details['customer_secret'],
            [
                'version' => 'wc/v3',
            ]
        );

        // // Get all orders
        $orders = $woocommerce->get('orders');
        return $this->render(
            '/Dashboard/singleStore.html.twig',
            [
                'store' => $store,
                'orders' => $orders
            ]
        );
    }
    #[Route('/order/{store}/{id}', name: 'singleOrder')]

    function singleOrder(ManagerRegistry $doctrine, Request $request, Security $security, $store, $id)
    {

        $store = $doctrine->getRepository(Store::class)->findOneBy(['id' => $store]);
        $details = $store->getDetails();
        $woocommerce = new Client(
            $details['domain'],
            $details['customer_key'],
            $details['customer_secret'],
            [
                'version' => 'wc/v3',
            ]
        );
        $order = $woocommerce->get('orders/' . $id);
        return $this->render(
            '/Dashboard/order.html.twig',
            [
                'store' => $store,
                'order' => $order
            ]
        );
    }
    private function checkStore($dns, $ck, $cs)
    {
        $store = new Client(
            $dns,
            $ck,
            $cs,
            [
                'version' => 'wc/v3',
            ]
        );
        return $store;
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
