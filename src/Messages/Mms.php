<?php

namespace Techlove\FortySixElks\Notifications\Messages;

use Techlove\FortySixElks\Notifications\Services\MessageClient;

class Mms extends Message
{
    protected ?string $image = null;

    public function __construct(MessageClient $client)
    {
        parent::__construct($client);

        $this->client->getBaseClient()->bodyFormat('multipart');
        $this->client->getBaseClient()->contentType('multipart/form-data');
    }

    public function getEndpoint(): string
    {
        return '/mms';
    }

    /**
     * @param string|null $media Publicly accessible URL or data URI to a png/jpg/gif image
     * @return $this
     */
    public function image(?string $media): self
    {
        $this->image = $media;
        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        if ($this->image) {
            $array['image'] = $this->image;
        }

        return $array;
    }

    public function validationRules(): array
    {
        return [
            'from' => 'required',
            'to' => 'required',
            'image' => 'required_without:message|prohibits:message|string',
            'message' => 'required_without:image|prohibits:image|string',
        ];
    }
}