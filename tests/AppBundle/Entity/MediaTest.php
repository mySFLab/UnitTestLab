<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Entity\Vote;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    /**
     * public function getNewAverageAfterVote()
        {
            if (0 < $count = count($this->votes)) {
            $total = 0;

            foreach ($this->votes as $vote) {
            $total += $vote->getScore();
            }

            $this->average = $total / $count;
            }
        }
     */
    public function testGetNewAverageAfterVoteIfMediaHasNoVotes()
    {
        $media = new Media();
        $media->getNewAverageAfterVote();
        $this->assertEquals(0, $media->getAverage());
    }

    public function testGetNewAverageAfterVoteIfMediaHasVotes()
    {
        $media = new Media();
        $vote = new Vote();
        $vote2 = new Vote();
        $vote3 = new Vote();
        $vote4 = new Vote();
        $vote->setScore(1);
        $vote2->setScore(5);
        $vote3->setScore(8);
        $vote4->setScore(9);
        $media->addVote($vote);
        $media->addVote($vote2);
        $media->addVote($vote3);
        $media->addVote($vote4);
        $media->getNewAverageAfterVote();

        $this->assertGreaterThan(0, $media->getAverage());
        $voteAverage = 5.75;
        $this->assertEquals($voteAverage, $media->getAverage());
    }


    /**
     * @dataProvider getDisplayedAverageProvider
     * @param int|null $actual
     * @param string $expected
     */
    public function testGetDisplayedAverage($actual, $expected)
    {
        $media = new Media();
        $media->setAverage($actual);
        $this->assertEquals($expected, $media->getDisplayedAverage());
    }

    /**
     * @return array
     */
    public function getDisplayedAverageProvider()
    {
        return [
            [5, '5.0'],
            [5.5, '5.5'],
            [5.74, '5.7'],
            [(23/4), '5.8'],
            [(16/3), '5.3'],
            [null, '-'],
        ];
    }

    /**
     * public function hasUserAlreadyVoted(User $user)
        {
            foreach ($this->votes as $vote) {
                if ($vote->getUser() === $user) {
                return true;
            }
        }
        return false;
        }
     */
    public function testhasUserAlreadyVoted()
    {
        $media = new Media();

        $user = new User();
        $user1 = new User();
        $user2 = new User();

        $vote = new Vote();
        $vote1 = new Vote();
        $vote2 = new Vote();

        $vote->setUser($user);
        $vote1->setUser($user1);
        $vote2->setUser($user1);

        $media->addVote($vote);
        $media->addVote($vote1);
        $media->addVote($vote2);

        $this->assertTrue($media->hasUserAlreadyVoted($user));
        $this->assertTrue($media->hasUserAlreadyVoted($user1));
        $this->assertFalse($media->hasUserAlreadyVoted($user2), "Tu n'as pas voté :)");
    }


}
