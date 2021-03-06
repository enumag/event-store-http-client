<?php

/**
 * This file is part of `prooph/event-store-http-client`.
 * (c) 2018-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2018-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventStoreHttpClient;

use Prooph\EventStoreHttpClient\Common\SystemConsumerStrategies;
use Prooph\EventStoreHttpClient\Exception\InvalidArgumentException;

class PersistentSubscriptionSettingsBuilder
{
    /**
     * Tells the subscription to resolve link events.
     * @var bool
     */
    private $resolveLinkTos;
    /**
     * Start the subscription from the position-of the event in the stream.
     * If the value is set to `-1` that the subscription should start from
     * where the stream is when the subscription is first connected.
     * @var int
     */
    private $startFrom;
    /**
     * Tells the backend to measure timings on the clients so statistics will contain histograms of them.
     * @var bool
     */
    private $extraStatistics;
    /**
     * The amount of time the system should try to checkpoint after.
     * @var int
     */
    private $checkPointAfterMilliseconds;
    /**
     * The size of the live buffer (in memory) before resorting to paging.
     * @var int
     */
    private $liveBufferSize;
    /**
     * The size of the read batch when in paging mode.
     * @var int
     */
    private $readBatchSize;
    /**
     * The number of messages that should be buffered when in paging mode.
     * @var int
     */
    private $bufferSize;
    /**
     * The maximum number of messages not checkpointed before forcing a checkpoint.
     * @var int
     */
    private $maxCheckPointCount;
    /**
     * Sets the number of times a message should be retried before considered a bad message.
     * @var int
     */
    private $maxRetryCount;
    /**
     * Sets the maximum number of allowed subscribers.
     * @var int
     */
    private $maxSubscriberCount;
    /**
     * Sets the timeout for a client before the message will be retried.
     * @var int
     */
    private $messageTimeoutMilliseconds;
    /**
     * The minimum number of messages to write a checkpoint for.
     * @var int
     */
    private $minCheckPointCount;
    /** @var string */
    private $namedConsumerStrategy;

    /** @internal */
    public function __construct(
        bool $resolveLinkTos,
        int $startFrom,
        bool $extraStatistics,
        int $messageTimeoutMilliseconds,
        int $bufferSize,
        int $liveBufferSize,
        int $maxRetryCount,
        int $readBatchSize,
        int $checkPointAfterMilliseconds,
        int $minCheckPointCount,
        int $maxCheckPointCount,
        int $maxSubscriberCount,
        string $namedConsumerStrategy
    ) {
        $this->resolveLinkTos = $resolveLinkTos;
        $this->startFrom = $startFrom;
        $this->extraStatistics = $extraStatistics;
        $this->messageTimeoutMilliseconds = $messageTimeoutMilliseconds;
        $this->bufferSize = $bufferSize;
        $this->liveBufferSize = $liveBufferSize;
        $this->maxRetryCount = $maxRetryCount;
        $this->readBatchSize = $readBatchSize;
        $this->checkPointAfterMilliseconds = $checkPointAfterMilliseconds;
        $this->minCheckPointCount = $minCheckPointCount;
        $this->maxCheckPointCount = $maxCheckPointCount;
        $this->maxSubscriberCount = $maxSubscriberCount;
        $this->namedConsumerStrategy = $namedConsumerStrategy;
    }

    public function withExtraStatistics(): self
    {
        $this->extraStatistics = true;

        return $this;
    }

    public function resolveLinkTos(): self
    {
        $this->resolveLinkTos = true;

        return $this;
    }

    public function doNotResolveLinkTos(): self
    {
        $this->resolveLinkTos = false;

        return $this;
    }

    public function preferRoundRobin(): self
    {
        $this->namedConsumerStrategy = SystemConsumerStrategies::ROUND_ROBIN;

        return $this;
    }

    public function preferDispatchToSingle(): self
    {
        $this->namedConsumerStrategy = SystemConsumerStrategies::DISPATCH_TO_SINGLE;

        return $this;
    }

    public function startFromBeginning(): self
    {
        $this->startFrom = 0;

        return $this;
    }

    public function startFrom(int $position): self
    {
        $this->startFrom = $position;

        return $this;
    }

    public function withMessageTimeoutOf(int $timeout): self
    {
        $this->messageTimeoutMilliseconds = $timeout;

        return $this;
    }

    public function dontTimeoutMessages(): self
    {
        $this->messageTimeoutMilliseconds = 0;

        return $this;
    }

    public function checkPointAfterMilliseconds(int $time): self
    {
        $this->checkPointAfterMilliseconds = $time;

        return $this;
    }

    public function minimumCheckPointCountOf(int $count): self
    {
        $this->minCheckPointCount = $count;

        return $this;
    }

    public function maximumCheckPointCountOf(int $count): self
    {
        $this->maxCheckPointCount = $count;

        return $this;
    }

    public function withMaxRetriesOf(int $count): self
    {
        if ($count < 0) {
            throw new InvalidArgumentException('MaxRetries cannot be negative');
        }

        $this->maxRetryCount = $count;

        return $this;
    }

    public function withLiveBufferSizeOf(int $count): self
    {
        if ($count < 0) {
            throw new InvalidArgumentException('LiveBufferSize cannot be negative');
        }

        $this->liveBufferSize = $count;

        return $this;
    }

    public function withReadBatchOf(int $count): self
    {
        if ($count < 0) {
            throw new InvalidArgumentException('ReadBatchSize cannot be negative');
        }

        $this->readBatchSize = $count;

        return $this;
    }

    public function withBufferSizeOf(int $count): self
    {
        if ($count < 0) {
            throw new InvalidArgumentException('BufferSize cannot be negative');
        }

        $this->bufferSize = $count;

        return $this;
    }

    public function startFromCurrent(): self
    {
        $this->startFrom = -1;

        return $this;
    }

    public function withMaxSubscriberCountOf(int $count): self
    {
        if ($count < 0) {
            throw new InvalidArgumentException('Max subscriber count cannot be negative');
        }

        $this->maxSubscriberCount = $count;

        return $this;
    }

    public function withNamedConsumerStrategy(string $namedConsumerStrategy): self
    {
        $this->namedConsumerStrategy = $namedConsumerStrategy;

        return $this;
    }

    public function build(): PersistentSubscriptionSettings
    {
        return new PersistentSubscriptionSettings(
            $this->resolveLinkTos,
            $this->startFrom,
            $this->extraStatistics,
            $this->messageTimeoutMilliseconds,
            $this->bufferSize,
            $this->liveBufferSize,
            $this->maxRetryCount,
            $this->readBatchSize,
            $this->checkPointAfterMilliseconds,
            $this->minCheckPointCount,
            $this->maxCheckPointCount,
            $this->maxSubscriberCount,
            $this->namedConsumerStrategy
        );
    }
}
