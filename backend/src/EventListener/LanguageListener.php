<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LanguageListener implements EventSubscriberInterface
{
    private TranslatableListener $translatableListener;
    private array $locales;
    private string $defaultLocale;
    private string $currentLocale;

    public function __construct(TranslatableListener $translatableListener, string $locales, string $defaultLocale)
    {
        $this->translatableListener = $translatableListener;
        $this->defaultLocale = $defaultLocale;
        $this->locales = explode('|', trim($locales));

        if (empty($this->locales)) {
            throw new \UnexpectedValueException('The list of supported locales must not be empty.');
        }
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // Persist DefaultLocale in translation table
        $this->translatableListener->setPersistDefaultLocaleTranslation(true);

        $request = $event->getRequest();
        $preferredLanguage = $request->getPreferredLanguage($this->locales);

        if (null !== $preferredLanguage && \in_array($preferredLanguage, $this->locales, false)) {
            $request->setLocale($preferredLanguage);
        } else {
            $request->setLocale($this->defaultLocale);
        }

        $this->translatableListener->setTranslatableLocale($request->getLocale());
        $this->currentLocale = $request->getLocale();
    }

    public function setContentLanguage(ResponseEvent $event): Response
    {
        $response = $event->getResponse();
        $response->headers->add(['Content-Language' => $this->currentLocale]);

        return $response;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 4096]],
            KernelEvents::RESPONSE => 'setContentLanguage',
        ];
    }
}
