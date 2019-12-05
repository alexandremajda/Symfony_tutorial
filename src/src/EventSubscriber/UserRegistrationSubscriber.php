<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Allows you to hash your password
 */
class UserRegistrationSubscriber implements EventSubscriberInterface {

    private $passwordEncoder;
    private $tokenGenerator;
    private $allowedMethods = array(Request::METHOD_POST);
    private $mailer;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        \Swift_Mailer $mailer) 
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }
    
    public static function getSubscribedEvents(){
        
        // Define a priority in the kernel on the function defined below
        return [
            KernelEvents::VIEW => ['userRegistered', EventPriorities::PRE_WRITE]
        ];
    }


    /**
     * Check if the event launcher is a user entity and if it's a post method
     * Set his password as encrypted 
     * 
     * The "GetResponseForControllerResultEvent" has been renamed as "ViewEvent"
     */
    public function userRegistered(ViewEvent $event) {

        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || !in_array($method, $this->allowedMethods))
            return;

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        // Create the confirmation Token
        $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken());

        // Send the mail
        $message = (new \Swift_Message("Hello from api platform"))
            ->setFrom('alex.mjd123@gmail.com')
            ->setTo('alex.mjd123@gmail.com')
            ->setBody("toutou you tou");

        $this->mailer->send($message);

        // Create the Transport
        // $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465))
        // ->setUsername('alex.mjd123@gmail.com')
        // ->setPassword('qwerty23#')
        // ;

        // Create the Mailer using your created Transport
        // $mailing = new \Swift_Mailer($transport);

        // // Create a message
        // $message = (new \Swift_Message('Wonderful Subject'))
        // ->setFrom(['john@doe.com' => 'John Doe'])
        // ->setTo(['alex.mjd123@gmail.com' => 'A name'])
        // ->setBody('Here is the message itself')
        // ;

        // Send the message
        // $mailing->send($message);
    }
}

?>