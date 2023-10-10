<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction("notificationAvailable", [TemplateRuntime::class, 'notificationsOn']),
            new TwigFunction("notificationsCount", [TemplateRuntime::class, 'notificationsCount']),
            new TwigFunction("themeActive", [TemplateRuntime::class, 'themeActive']),
            new TwigFunction("loadTheme", [TemplateRuntime::class, 'loadTheme']),
            new TwigFunction("getEmailData", [TemplateRuntime::class, 'getEmailData']),
        ];
    }
}
