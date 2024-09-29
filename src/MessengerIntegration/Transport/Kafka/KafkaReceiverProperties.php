<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Kafka;

use RdKafka\Conf as KafkaConf;

final readonly class KafkaReceiverProperties
{
    public function __construct(
        public KafkaConf $kafkaConf,
        public string $topicName,
        public int $receiveTimeoutMs,
        public bool $commitAsync,
    ) {
    }

    /** @deprecated  */
    public function getKafkaConf(): KafkaConf
    {
        return $this->kafkaConf;
    }

    /** @deprecated  */
    public function getTopicName(): string
    {
        return $this->topicName;
    }

    /** @deprecated  */
    public function getReceiveTimeoutMs(): int
    {
        return $this->receiveTimeoutMs;
    }

    /** @deprecated  */
    public function isCommitAsync(): bool
    {
        return $this->commitAsync;
    }
}
