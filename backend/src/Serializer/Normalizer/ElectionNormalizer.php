<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializer\Normalizer;

use App\Entity\Election;
use App\Service\ElectionStepGuesser;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class ElectionNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'ELECTION_ALREADY_CALLED';

    private ElectionStepGuesser $stepGuesser;

    public function __construct(ElectionStepGuesser $stepGuesser)
    {
        $this->stepGuesser = $stepGuesser;
    }

    /**
     * @param Election $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;

        $data = (array) $this->normalizer->normalize($object, $format, $context);
        $data['stepperPosition'] = $this->stepGuesser->guessStep($object);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        // avoid recursion: only call once per object
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Election && 'get' === ($context['item_operation_name'] ?? null);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return false;
    }
}
