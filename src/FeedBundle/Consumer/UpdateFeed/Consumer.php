<?php

namespace FeedBundle\Consumer\UpdateFeed;

use Doctrine\ORM\EntityManagerInterface;
use StatsdBundle\Client\StatsdAPIClient;
use FeedBundle\Consumer\UpdateFeed\Input\Message;
use FeedBundle\DTO\SendNotificationDTO;
use FeedBundle\Service\FeedService;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Consumer implements ConsumerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly FeedService $feedService,
        private MessageBusInterface $messageBus,
        private readonly StatsdAPIClient $statsdAPIClient,
        private readonly string $key,
    )
    {
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (JsonException $e) {
            return $this->reject($e->getMessage());
        }

        $tweetDTO = $message->getTweetDTO();

        $this->feedService->putTweet($tweetDTO, $message->getFollowerId());
        $notificationMessage = (new SendNotificationDTO($message->getFollowerId(), $tweetDTO->getText()));
        $this->messageBus->dispatch(new Envelope($notificationMessage, [new AmqpStamp($message->getPreferred())]));

        $this->statsdAPIClient->increment($this->key);
        $this->entityManager->clear();
        $this->entityManager->getConnection()->close();

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}
