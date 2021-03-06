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

class ConnectionSettings
{
    /** @var EndPoint */
    private $endPoint;
    /** @var bool */
    private $useSslConnection;
    /** @var UserCredentials|null */
    private $defaultUserCredentials;
    /** @var bool */
    private $requireMaster;

    public static function default(): ConnectionSettings
    {
        return new self(
            new EndPoint('localhost', 2113),
            false,
            null,
            true
        );
    }

    public function __construct(
        EndPoint $endpoint,
        bool $useSslConnection,
        ?UserCredentials $defaultUserCredentials = null,
        bool $requireMaster = true
    ) {
        $this->endPoint = $endpoint;
        $this->useSslConnection = $useSslConnection;
        $this->defaultUserCredentials = $defaultUserCredentials;
        $this->requireMaster = $requireMaster;
    }

    public function defaultUserCredentials(): ?UserCredentials
    {
        return $this->defaultUserCredentials;
    }

    public function useSslConnection(): bool
    {
        return $this->useSslConnection;
    }

    public function endPoint(): EndPoint
    {
        return $this->endPoint;
    }

    public function requireMaster(): bool
    {
        return $this->requireMaster;
    }
}
