<?php

namespace App\Twig;


use App\Controller\MailerController;
use App\Entity\Notifications;
use App\Entity\Themes;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\TwigFilter;

class TemplateRuntime implements RuntimeExtensionInterface
{
    /** @var User $user */
    private $user;
    private $request;
    private $doctrine;
    private $requestStack;
    private $settings;
    private $notifications;
    public function __construct(ManagerRegistry $doctrine, Security $security, RequestStack $request,)
    {
        $this->doctrine = $doctrine;
        $security && $this->user = $security->getUser() ?? null;
        $request && $this->request = $request->getCurrentRequest();
    }

    // public function themeActive()
    // {
    //     $this->activeTheme = "Lux";
    //     if (isset($this->themes)) {

    //         if (count($this->themes) > 0) {
    //             foreach ($this->themes as $theme) {
    //                 $this->activeTheme = $theme->getLink();
    //             }
    //         }
    //     }
    //     return $this->activeTheme;
    // }
    // public function loadTheme()
    // {
    //     $link = $this->swapServices->themeAvailable()[$this->themeActive()];
    //     return $link;
    // }

    public function getUser()
    {
        return $this->user ? $this->user->getUserIdentifier() : null;
    }
    public function getEmailData()
    {
        return $this->settings->getAll();
    }
}
