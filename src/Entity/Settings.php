<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
    const FORMAT_SETTINGS_EMAIL =
    [
        "username" => [
            "type" => "text",
            "value" => "",
        ],

        "password" => [
            "type" => "password",
            "value" => "",
        ],

        "server" => [
            "type" => "text",
            "value" => "",
        ],
        "port" => [
            "type" => "number",
            "value" => "",
        ],
    ]; //["username":"no-reply@eltand.com","password":"Noteazo123","server":"mail.eltand.com","port":"465"]


    const CATEGORY_SETTINGS_EMAIL = ["email" => self::FORMAT_SETTINGS_EMAIL];


    const SETTINGS_INSTALL = [
        self::CATEGORY_SETTINGS_EMAIL,
    ];


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $settingName = null;


    #[ORM\Column]
    private array $details = [];

    function setData(array $entry = null)
    {
        $this->settingName = $entry[1];
        $this->details = $entry[2];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of settingName
     */
    public function getSettingName()
    {
        return $this->settingName;
    }

    /**
     * Set the value of settingName
     *
     * @return  self
     */
    public function setSettingName($settingName)
    {
        $this->settingName = $settingName;

        return $this;
    }

    /**
     * Get the value of details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set the value of details
     *
     * @return  self
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }
}