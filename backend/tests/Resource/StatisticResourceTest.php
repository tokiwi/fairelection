<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Resource;

use App\Factory\AssignmentFactory;
use App\Factory\CandidateFactory;
use App\Factory\CriteriaFactory;
use App\Factory\CriteriaItemFactory;
use App\Factory\ElectionCriteriaFactory;
use App\Factory\ElectionFactory;
use App\Factory\UserFactory;
use App\Tests\CustomApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\Test\Factories;

class StatisticResourceTest extends CustomApiTestCase
{
    use Factories;

    /**
     * @group functional
     */
    public function testStatisticByElection(): void
    {
        $this->createElection();

        $client = static::createAuthenticatedClient('john.doe@gmail.com');
        $client->request('GET', '/statistic_resources/01EVXT7ZW43FKMTX52VB03R4MY');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonContains([
            '@context' => '/contexts/StatisticResource',
            '@type' => 'StatisticResource',
            'rows' => [
                [
                    '@type' => 'StatisticRowResource',
                    'categoryName' => 'Age',
                    'categoryPictogram' => 'age',
                    'items' => [
                        [
                            '@type' => 'StatisticItemResource',
                            'category' => '<18',
                            'candidateNumber' => 0,
                            'percent' => 0,
                            'sufficient' => true,
                            'red' => 0,
                            'green' => 100,
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createElection(): void
    {
        $owner = UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        $election = ElectionFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
            'owner' => $owner,
        ]);

        $item1 = CriteriaItemFactory::new()->create([
            'name' => 'Below 18 yeara old',
            'acronym' => '<18',
        ]);

        $criteriaAge = CriteriaFactory::new()->create([
            'name' => 'Age',
            'pictogram' => 'age',
            'items' => [$item1],
        ]);

        $electionCriteriaAge = ElectionCriteriaFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
            'election' => $election,
            'criteria' => $criteriaAge,
        ]);
        $a1 = AssignmentFactory::new()->create([
            'ulid' => Ulid::fromString('01EWJ7M84010NWQJEYX1GP2YK8'),
            'electionCriteria' => $electionCriteriaAge,
        ]);
        $a2 = AssignmentFactory::new()->create([
            'ulid' => Ulid::fromString('01EWJNMG1B440HJ66QWF5NEW2V'),
            'electionCriteria' => $electionCriteriaAge,
        ]);

        CandidateFactory::new()->create([
            'name' => 'Candidate A',
            'election' => $election,
            'assignments' => [$a1, $a2],
        ]);
    }
}
