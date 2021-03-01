<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(name="criteria_translations")
 * @ORM\Entity(repositoryClass="App\Repository\Translation\CriteriaTranslationRepository")
 */
class CriteriaTranslation extends AbstractTranslation
{
}
