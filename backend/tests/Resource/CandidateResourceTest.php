<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Resource;

use App\Factory\AssignmentFactory;
use App\Factory\ElectionCriteriaFactory;
use App\Factory\ElectionFactory;
use App\Tests\CustomApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\Test\Factories;

class CandidateResourceTest extends CustomApiTestCase
{
    use Factories;

    /**
     * @group functional
     */
    public function testSendCandidates(): void
    {
        $election = ElectionFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
        ]);

        $electionCriteriaAge = ElectionCriteriaFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
            'election' => $election,
        ]);
        AssignmentFactory::new()->create([
            'ulid' => Ulid::fromString('01EWJ7M84010NWQJEYX1GP2YK8'),
            'electionCriteria' => $electionCriteriaAge,
        ]);
        AssignmentFactory::new()->create([
            'ulid' => Ulid::fromString('01EWJNMG1B440HJ66QWF5NEW2V'),
            'electionCriteria' => $electionCriteriaAge,
        ]);

        $client = static::createClient();
        $client->request('POST', '/candidate_resources', [
            'json' => [
                'election' => '/elections/01EVXT7ZW43FKMTX52VB03R4MY',
                'candidates' => [
                    [
                        '@id' => null,
                        'name' => 'C1',
                        'electionCriterias' => [
                            [
                                '@id' => '/election_criterias/01EVXT7ZW43FKMTX52VB03R4MY',
                                'name' => 'Age',
                                'pictogram' => 'age',
                                'selectedValue' => '/assignments/01EWJNMG1B440HJ66QWF5NEW2V',
                                'choices' => [
                                    ['@id' => '/assignments/01EWJ7M84010NWQJEYX1GP2YK8', 'acronym' => '<18', 'name' => 'Below 18 years old'],
                                    ['@id' => '/assignments/01EWJNMG1B440HJ66QWF5NEW2V', 'acronym' => '>= 18', 'name' => '18 years old or above'],
                                ],
                            ],
                        ],
                    ],
                    [
                        '@id' => null,
                        'name' => 'C2',
                        'electionCriterias' => [
                            [
                                '@id' => '/election_criterias/01EVXT7ZW43FKMTX52VB03R4MY',
                                'name' => 'Age',
                                'pictogram' => 'age',
                                'selectedValue' => '/assignments/01EWJ7M84010NWQJEYX1GP2YK8',
                                'choices' => [
                                    ['@id' => '/assignments/01EWJ7M84010NWQJEYX1GP2YK8', 'acronym' => '<18', 'name' => 'Below 18 years old'],
                                    ['@id' => '/assignments/01EWJNMG1B440HJ66QWF5NEW2V', 'acronym' => '>= 18', 'name' => '18 years old or above'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
