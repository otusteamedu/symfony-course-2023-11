<?php

namespace App\Services;

use App\Entity\User;
use App\Managers\SubscriptionManager;
use App\Managers\TweetManager;
use App\Managers\UserManager;

class UserBuilderService
{
    public function __construct(
        private readonly TweetManager $tweetManager,
        private readonly UserManager $userManager,
        private readonly SubscriptionManager $subscriptionManager,
    ) {
    }

    /**
     * @param string[] $texts
     */
    public function createUserWithTweets(string $login, array $texts): User
    {
        $user = $this->userManager->create($login);
        foreach ($texts as $text) {
            $this->tweetManager->postTweet($user, $text);
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function createUserWithFollower(string $login, string $followerLogin): array
    {
        $user = $this->userManager->create($login);
        $follower = $this->userManager->create($followerLogin);
        $this->userManager->subscribeUser($user, $follower);
        $this->subscriptionManager->addSubscription($user, $follower);

        return [$user, $follower];
    }
}