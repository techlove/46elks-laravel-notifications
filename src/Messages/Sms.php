<?php

namespace Techlove\FortySixElks\Notifications\Messages;

class Sms extends Message
{
    protected bool $flash = false;
    protected bool $dryRun = false;
    protected bool $dontLog = false;
    protected ?string $whenDelivered = null;

    /**
     * Enable when you want to verify your API request without actually sending an SMS.
     *
     * Note: No SMS message will be sent when this is enabled.
     *
     * @param bool $dryRun
     * @return $this
     */
    public function dryRun(bool $dryRun = true): static
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * Enable to avoid storing the message text in your history.
     *
     * NOTE: The other parameters will still be stored.
     *
     * @param bool $dontLog
     * @return $this
     */
    public function dontLog(bool $dontLog = true): static
    {
        $this->dontLog = $dontLog;
        return $this;
    }

    /**
     * This webhook URL will receive a POST request every time the delivery status changes.
     *
     * @param string $whenDelivered
     * @return $this
     */
    public function whenDelivered(string $whenDelivered): static
    {
        $this->whenDelivered = $whenDelivered;
        return $this;
    }

    /**
     * Send the message as a Flash SMS. The message will be displayed immediately upon arrival and may not be stored.
     *
     * @param bool $flash
     * @return $this
     */
    public function flash(bool $flash = true): static
    {
        $this->flash = $flash;
        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->flash) {
            $data['flashsms'] = 'yes';
        }

        if ($this->dryRun) {
            $data['dryrun'] = 'yes';
        }

        if ($this->dontLog) {
            $data['dontlog'] = 'message';
        }

        if ($this->whenDelivered) {
            $data['whendelivered'] = $this->whenDelivered;
        }

        return $data;
    }

    public function validationRules(): array
    {
        return [
            'from' => 'required',
            'to' => 'required',
            'message' => 'required|string',
            'dryrun' => 'sometimes|boolean',
            'dontlog' => 'sometimes|boolean',
            'whendelivered' => 'sometimes|string',
            'flashsms' => 'sometimes|string',
        ];
    }

    protected function getEndpoint(): string
    {
        return '/sms';
    }
}