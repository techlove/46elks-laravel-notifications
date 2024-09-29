<?php

namespace Techlove\FortySixElks\Notifications\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;

class MessageClient
{
    public const BASE_URL = 'https://api.46elks.com/a1';
    protected PendingRequest $client;

    protected bool $forceDryRun = false;

    public function __construct(
        protected ?string $username,
        protected ?string $password,
    ) {
        $this->buildClient();
    }

    /**
     * @throws ConnectionException
     */
    public function post(string $path, array $payload): \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
    {
        if ($this->forceDryRun) {
            $payload['dryrun'] = 'yes';
        }

        return $this->client->post($path, $payload);
    }

    /**
     * @param bool $dryRun
     * @return $this
     */
    public function forceDryRun(bool $dryRun = true): static
    {
        $this->forceDryRun = $dryRun;

        return $this;
    }

    public function fakeRequests(bool $fakeRequests = true): static
    {
        if ($fakeRequests) {
            $this->client->preventStrayRequests();
            $this->client->stub(
                fn () => Http::response(['status' => 'fake'], 200)
            );

            return $this;
        }

        // Rebuild the client to remove the stubs
        $this->buildClient();

        return $this;
    }

    public function getBaseClient(): PendingRequest
    {
        return $this->client;
    }

    protected function buildClient(): void
    {
        $this->client = Http::createPendingRequest()
            ->baseUrl(self::BASE_URL)
            ->withBasicAuth($this->username, $this->password)
            ->bodyFormat('form_params')
            ->contentType('application/x-www-form-urlencoded');
    }
}
