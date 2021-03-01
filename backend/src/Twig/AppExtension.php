<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    private ContainerInterface $container;
    private string $publicDir;
    private string $projectDir;

    public function __construct(ContainerInterface $container, string $publicDir)
    {
        $this->container = $container;
        $this->publicDir = $publicDir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('encore_entry_css_source', fn (string $entryName): string => $this->getEncoreEntryCssSource($entryName)),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('custom_number', function (?float $entryValue, int $precision = 2): string {
                return $this->numberFormat($entryValue, $precision);
            }),
        ];
    }

    public function numberFormat(?float $entryValue, int $precision = 2): string
    {
        if (null === $entryValue) {
            return '';
        }

        return number_format($entryValue, $precision, '.', '\'');
    }

    public function getEncoreEntryCssSource(string $entryName): string
    {
        $entryPointLookup = $this->container->get(EntrypointLookupInterface::class);
        $files = $entryPointLookup->getCssFiles($entryName);

        $source = '';
        foreach ($files as $file) {
            $source .= file_get_contents($this->publicDir.'/'.$file);
        }

        $entryPointLookup->reset();

        return $source;
    }

    public static function getSubscribedServices(): array
    {
        return [
            EntrypointLookupInterface::class,
        ];
    }
}
