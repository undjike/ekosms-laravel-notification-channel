<?php

namespace Undjike\EkoSmsNotificationChannel;

class EkoSmsMessage
{
    /**
     * Body of the message
     *
     * @var string
     */
    protected string $body;

    /**
     * Brand name of the message
     *
     * @var string
     */
    protected string $brand = 'GooClean';

    /**
     * Encoding of the message
     *
     * @var string
     */
    protected string $encoding = 'UTF-8';

    /**
     * Unicode character allowed in the message
     *
     * @var bool
     */
    protected bool $acceptUnicode = false;

    /**
     * ID of the message in your system, it's used by the third
     * party when sending acknowledgment
     *
     * @var string
     */
    protected string $remoteId = '';

    /**
     * EkoSmsMessage constructor.
     *
     * @param string $body
     */
    public function __construct(string $body = '')
    {
        $this->body($body);
    }

    /**
     * CleanSmsMessage pseudo-constructor.
     *
     * @param string $body
     * @return EkoSmsMessage
     */
    public static function create(string $body = ''): EkoSmsMessage
    {
        return new static($body);
    }

    /**
     * Set message body
     *
     * @param string $body
     * @return EkoSmsMessage
     */
    public function body(string $body): EkoSmsMessage
    {
        $this->body = trim($body);
        return $this;
    }

    /**
     * Get message body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set message sender
     *
     * @param string $brand
     * @return EkoSmsMessage
     */
    public function sender(string $brand): EkoSmsMessage
    {
        $this->brand = trim($brand);
        return $this;
    }

    /**
     * Get message sender
     *
     * @return string
     */
    public function getSender(): string
    {
        return $this->brand;
    }

    /**
     * Set message ID
     *
     * @param string $id
     * @return EkoSmsMessage
     */
    public function remoteId(string $id): EkoSmsMessage
    {
        $this->remoteId = $id;
        return $this;
    }

    /**
     * Get remote ID
     *
     * @return string
     */
    public function getRemoteId(): string
    {
        return $this->remoteId;
    }

    /**
     * Set message encoding
     *
     * @param string $encoding
     * @return EkoSmsMessage
     */
    public function encoding(string $encoding): EkoSmsMessage
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Get messaging encoding
     *
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Set message encoding
     *
     * @param bool $acceptUnicode
     * @return EkoSmsMessage
     */
    public function allowUnicode(bool $acceptUnicode = true): EkoSmsMessage
    {
        $this->acceptUnicode = $acceptUnicode;
        return $this;
    }

    /**
     * Get messaging encoding
     *
     * @return bool
     */
    public function unicodeAllowed(): bool
    {
        return $this->acceptUnicode;
    }
}
