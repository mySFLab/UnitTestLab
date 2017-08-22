<?php

namespace tests\AppBundle\Entity\Manager;

use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Entity\Vote;
use AppBundle\Repository\VoteRepository;
use AppBundle\Entity\Manager\VoteManager;
use Doctrine\ORM\Repository\RepositoryFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class VoteManagerTest extends TestCase
{
    protected $token;

    protected $repository;
    protected $tokenStorage;
    protected $voteManager;

    public function setUp()
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->repository = $this->getMockBuilder(VoteRepository::class)
            ->setMethods(['save'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->voteManager = new VoteManager($this->repository, $this->tokenStorage);
    }

    /**
     *
     */
    public function testGetNewVote()
    {
        $user = new User();
        $media = new Media();
        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);
        $this->token->expects($this->once())->method('getUser')->willReturn($user);
        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($this->token);

        $result = $this->voteManager->getNewVote($media);
        $this->assertEquals($vote->getMedia(), $result->getMedia());
        $this->assertEquals($vote->getUser(), $result->getUser());

        //$this->assertEquals($vote, $this->voteManager->getNewVote($media));
    }

    /**
     *
     */
    public function testGetNewVoteReturnsNullIfNoConnectedUser()
    {
        $user = new User();
        $media = new Media();
        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);
        $this->token->expects($this->once())->method('getUser')->willReturn(null);
        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($this->token);

        $this->assertNull($this->voteManager->getNewVote($media));
    }

    /**
     * public function saveVote(Vote $vote)
        {
            $media = $vote->getMedia();
            $media->addVote($vote);

            $this->voteRepository->save($vote);
        }
     */
    public function testSaveVote()
    {
        $media = $this->createMock(Media::class);
        $vote = new Vote();
        $vote->setMedia($media);

        $media->expects($this->once())->method('addVote')->with($vote);
        $this->repository->expects($this->once())->method('save')->with($vote);
        //$this->repository->expects($this->any())->method('save')->withConsecutive($vote)->willReturnOnConsecutiveCalls();
        //$this->repository->expects($this->exactly(3))->method('save')->with($vote);
        //$this->repository->expects($this->never())->method('save')->with($vote);
        //$media->expects($this->once())->method('addVote')->with($this->isInstanceOf(Vote::class));

        $this->voteManager->saveVote($vote);




    }
}