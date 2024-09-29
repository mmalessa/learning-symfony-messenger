<?php

declare(strict_types=1);

namespace App\MessengerIntegration\Transport\Kafka;

use RdKafka\Conf as KafkaConf;

final readonly class KafkaSenderProperties
{

    public function __construct(
        public KafkaConf $kafkaConf,
        public string $topicName,
        public int $flushTimeoutMs,
        public int $flushRetries,
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
    public function getFlushTimeoutMs(): int
    {
        return $this->flushTimeoutMs;
    }

    /** @deprecated  */
    public function getFlushRetries(): int
    {
        return $this->flushRetries;
    }
}
