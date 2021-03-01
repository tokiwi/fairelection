<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Service\Mailer;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    private Mailer $mailer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(Mailer $mailer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $plainPassword = $event->getPassword();

        if (null === $plainPassword) {
            return;
        }

        /** @var User $user */
        $user = $passwordToken->getUser();

        $user->setUpdatedAt(new \DateTime()); // force doctrine see changes
        $user->setPlainPassword($plainPassword);

        try {
            $this->validator->validate($user, ['groups' => ['Default', 'userRegister']]);
        } catch (\Exception $e) {
            // validation exceptions are not returned in json+ld format, so we throw a standard one
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $this->mailer->sendUserResetPasswordRequest($user, $passwordToken->getToken());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateTokenEvent::class => 'onCreateToken',
            UpdatePasswordEvent::class => 'onUpdatePassword',
        ];
    }
}
