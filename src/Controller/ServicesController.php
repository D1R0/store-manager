<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Entity\Settings;
use App\Entity\Token;
use App\Entity\Tasks;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class ServicesController extends AbstractController
{
    /** @var User $user */
    private $user;
    private $doctrine;
    // private $sender;
    private $tokens;
    private $all;

    public function __construct(ManagerRegistry $doctrine, Security $security)
    {
        $this->doctrine = $doctrine;
        // $this->sender = new MailerController($this->doctrine);
        $this->user = $security->getUser() ? $security->getUser() : "";
        // $this->all = $this->doctrine->getRepository(Notifications::class)->findBy(["reader" => $this->user]);
    }
    public function notificationCreate(string $path, User $reader, string $type = "Activity Added")
    {
        if ($reader != $this->user) {
            $date = new DateTime("now", new DateTimeZone('Europe/Bucharest'));
            $notification = new Notifications;
            $notification->setDate($date);
            $notification->setPath($path);
            $notification->setReader($reader);
            $notification->setStatus("unread");
            $notification->setCreator($this->user);
            $notification->setType($type);
            $em = $this->doctrine->getManager();

            // $this->createNotificationDiscord($path, $reader, $this->user);
            if ($reader->getEmail() != "official@eltand.com") {
                $this->createNotificationEmail($path, $reader, $this->user, $type);
            }
        }
    }

    public function createNotificationEmail($path, $reader, $creator, $title)
    {

        $targetEmail = $reader->getEmail();
        $link = "https://" . $_SERVER['SERVER_NAME'] . $path;
        $content = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Email Notification</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        </head>
        <body>
        <div class="container">
            <h1 class="mt-4">Notification</h1>
            <p>Hello,</p>
            <p>This is a notification email.</p>
            <p>Sender: ' . $creator->getUsername() . '</p>
            <p>Here is the <a href="' . $link . '">link</a> you requested.</p>
            <p>Thank you!</p>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        </body>
        </html>';

        $this->sender->sendEmail($targetEmail, $content, $title);
    }
 

    public function notificationShow()
    {
        return $this->all;
    }
    public function notificationRead($id)
    {
        $em = $this->doctrine->getManager();
        $notifications = $this->doctrine->getRepository(Notifications::class)->findBy(["id" => $id]);
        foreach ($notifications as $notification) {
            $notification->setStatus("read");
            $em->persist($notification);
            $em->flush();
        }
        return $notification->getStatus();
    }
    public function createToken($type = "N/A", $dataPackages = "N/A", $tokenId = "N/A")
    {
        $newToken = new Token;
        $token = bin2hex(random_bytes(40));
        $tomorrowUnix = strtotime("+1 day");
        $datetime = date("Y-m-d h-m-s", $tomorrowUnix);
        $newToken->setType($type);
        $newToken->setTrakerId($tokenId);
        $newToken->setToken($token);
        $newToken->setDataPackages($dataPackages);
        $newToken->setExpire($datetime);
        $em = $this->doctrine->getManager();
        $em->persist($newToken);
        $em->flush();
        return $token;
    }

    public function validateToken($token, $type = '')
    {
        $tokenData = ['valid' => false];
        $this->tokens = $this->doctrine->getRepository(Token::class)->findAll();
        foreach ($this->tokens as $singleToken) {
            if ($token === $singleToken->getToken() && ($type === "*" || $type === $singleToken->getType())) {
                $tokenData = [
                    'valid' => true,
                    'dataPackages' => $singleToken->getDatapackages(),
                    'expire' => $singleToken->getExpire(),
                    'trackerId' => $singleToken->getTrakerId(),
                    'type' => $singleToken->getType(),
                ];
            }
        }
        return $tokenData;
    }

    public function burnToken($token)
    {
        $token = $this->doctrine->getRepository(Token::class)->findby(['token' => $token]);
        $em = $this->doctrine->getManager();
        $em->remove($token[0]);
        $em->flush();
    }
    public function logFile($data)
    {
        $dir = $this->getParameter('app_directory') . "/log";
        $myfile = fopen($dir, "a+") or die("Unable to open file!");
        $txt = $data . "\n";
        fwrite($myfile, $txt);

        fclose($myfile);
    }

    public function createUser($name, $email, $password, $role, $userPasswordHasher)
    {
        $exist = $this->doctrine->getRepository(User::class)->findOneBy(["username" => $name]);
        if ($exist == null) {
            $user = new User();
            $user->setUsername($name);
            $user->setEmail($email);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
            $user->setRoles([$role]);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return 1;
    }
}
