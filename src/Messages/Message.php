<?php

namespace Techlove\FortySixElks\Notifications\Messages;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Techlove\FortySixElks\Notifications\Events\SendingMessage;
use Techlove\FortySixElks\Notifications\Events\SentMessage;
use Techlove\FortySixElks\Notifications\Exceptions\MessageValidationException;
use Techlove\FortySixElks\Notifications\Services\MessageClient;

abstract class Message implements MessageInterface
{
    protected string $from;
    protected ?string $to = null;

    /** @var string[] $lines */
    protected array $lines = [];
    protected ?string $routedTo = null;

    public function __construct(protected MessageClient $client)
    {
        $this->from = config('services.46elks.from');
        $this->whenDelivered = config('services.46elks.when_delivered');
    }

    public function send(): static
    {
        $payload = $this->toArray();

        try {
            Validator::validate($payload, $this->validationRules());
        } catch (ValidationException $exception) {
            throw new MessageValidationException($exception->validator, $exception->response, $exception->errorBag);
        }

        SendingMessage::dispatch($this);
        $response = $this->client->post($this->getEndpoint(), $this->toArray());
        SentMessage::dispatch($this, $response);

        return $this;
    }

    public function routedTo(?string $routedTo): static
    {
        $this->routedTo = $routedTo;

        return $this;
    }

    public function setLines(array $lines): static
    {
        $this->lines = $lines;

        return $this;
    }

    public function lines(array $lines): static
    {
        $this->lines = array_merge($this->lines, $lines);

        return $this;
    }

    public function line(string $line): static
    {
        $this->lines[] = $line;

        return $this;
    }

    public function getMessage(): ?string
    {
        if (empty($this->lines)) {
            return null;
        }
        return Arr::join($this->lines, PHP_EOL);
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to ?? $this->routedTo;
    }

    public function to(string $to): static
    {
        $this->to = $to;

        return $this;
    }

    public function from(string $from): static
    {
        $this->from = $from;

        return $this;
    }
    public function toArray(): array
    {
        $data = [
            'from' => $this->from,
            'to' => $this->getTo(),
        ];

        $message = $this->getMessage();

        if ($message) {
            $data['message'] = $message;
        }

        return $data;
    }

    abstract public function validationRules(): array;

    abstract protected function getEndpoint(): string;

}