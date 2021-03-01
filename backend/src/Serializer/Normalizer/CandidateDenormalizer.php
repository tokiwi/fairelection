<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializer\Normalizer;

use ApiPlatform\Core\Api\OperationType;
use ApiPlatform\Core\Serializer\ItemNormalizer;
use App\Entity\Candidate;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CandidateDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    private ItemNormalizer $objectNormalizer;

    public function __construct(ItemNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (\is_array($data) &&
            OperationType::COLLECTION === $context['operation_type'] &&
            'candidate_collection_votes' === $context['collection_operation_name'] &&
            Candidate::class === $type) {
            // bulk operation must update on POST as PUT is only allowed for item and not collection
            // https://api-platform.com/docs/core/operations
            $context['api_allow_update'] = true;

            return array_map(function ($item) use ($type, $format, $context) {
                $item['id'] = $item['@id'];

                return $this->objectNormalizer->denormalize($item, $type, $format, $context);
            }, $data);
        }

        return $this->objectNormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return Candidate::class === $type;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
