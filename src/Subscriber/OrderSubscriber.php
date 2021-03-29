<?php

namespace App\Subscriber;

use App\Event\OrderEvent;
use App\Service\Cart\CartService;
use App\Service\Mailer\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mime\Address;

class OrderSubscriber implements EventSubscriberInterface
{

    private CartService $cartService;
    private MailerService $mailerService;

    public function __construct(
        CartService $cartService,
        MailerService $mailerService
    )
    {
        $this->cartService = $cartService;
        $this->mailerService = $mailerService;
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderEvent::class => 'onOrderCreated'
        ];
    }

    public function onOrderCreated(OrderEvent $event)
    {
        foreach ($event->getArray() as $item) {
            $product[] = $item->getProduct();
            $quantity[] = $item->getQuantity();
            $price[] = $item->getProduct()->getPrice();
        }
        $order_id = $item->getOrderId();
        $last_name = $item->getLastName();
        $first_name = $item->getFirstName();
        $phone = $item->getPhone();
        $address = $item->getAddress();
        $city = $item->getCity();
        $zip = $item->getZip();
        $email = $item->getEmail();
        $total = $this->cartService->getTotal();
        $toAddresses = [new Address($email), new Address('mailcentre@mail.com')];

        $this->mailerService->sendAdmin($email, $toAddresses, 'Nouvelle commande depuis votre site !', 'emails/contact.mjml.twig', 'emails/contact.txt.twig', [
            'product' => $product,
            'quantity' => $quantity,
            'price' => $price,
            'order_id' => $order_id,
            'last_name' => $last_name,
            'first_name' => $first_name,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'zip' => $zip,
            'mail' => $email,
            'total' => $total,
        ]);

        $this->mailerService->sendUser($email, $toAddresses, 'Récapitulatif de votre commande', 'emails/contact.mjml.twig', 'emails/contact.txt.twig', [
            'product' => $product,
            'quantity' => $quantity,
            'price' => $price,
            'order_id' => $order_id,
            'last_name' => $last_name,
            'first_name' => $first_name,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'zip' => $zip,
            'mail' => $email,
            'total' => $total,
        ]);

        return new RedirectResponse('app_home');
    }


}