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
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\Test\Factories;

class AssignmentResourceTest extends CustomApiTestCase
{
    use Factories;

    /**
     * @group functional
     */
    public function testUpdateAssignmentPercent(): void
    {
        $election = ElectionFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
        ]);

        $electionCriteria = ElectionCriteriaFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
            'election' => $election,
        ]);

        AssignmentFactory::new()->create([
            'ulid' => Ulid::fromString('01EWJ7M84010NWQJEYX1GP2YK8'),
            'electionCriteria' => $electionCriteria,
        ]);
        AssignmentFactory::new()->create([
            'ulid' => Ulid::fromString('01EWJNMG1B440HJ66QWF5NEW2V'),
            'electionCriteria' => $electionCriteria,
        ]);

        $assignment = AssignmentFactory::repository()->findOneBy([
            'ulid' => '01EWJ7M84010NWQJEYX1GP2YK8',
        ]);

        self::assertSame(0, $assignment->getPercent()); // @phpstan-ignore-line

        $client = static::createClient();
        $client->request('POST', '/assignment_resources', [
            'json' => [
                'election' => '/elections/01EVXT7ZW43FKMTX52VB03R4MY',
                'numberOfPeopleToElect' => 5,
                'assignments' => [
                    '/assignments/01EWJ7M84010NWQJEYX1GP2YK8' => 10,
                    '/assignments/01EWJNMG1B440HJ66QWF5NEW2V' => 33,
                ],
            ],
        ]);

        $assignment = AssignmentFactory::repository()->findOneBy([
            'ulid' => '01EWJ7M84010NWQJEYX1GP2YK8',
        ]);
        self::assertSame(10, $assignment->getPercent()); // @phpstan-ignore-line

        $assignment = AssignmentFactory::repository()->findOneBy([
            'ulid' => '01EWJNMG1B440HJ66QWF5NEW2V',
        ]);
        self::assertSame(33, $assignment->getPercent()); // @phpstan-ignore-line
    }
}
