<?php

namespace App\Game;

class MoveInfo
{
    /**
     * MoveInfo constructor.
     * @param string $type
     * @param array $from
     * @param array $to
     * @param bool $status
     * @param string $message
     */
    public function __construct(
        private string $type = '',
        private array $from = [],
        private array $to = [],
        private bool $status = true,
        private string $message = '',
    )
    {
    }

    /**
     * @return array
     */
    public function getArraySuccessful(): array
    {
        return [
            'status' => true,
            'type' => $this->type,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }

    /**
     * @param string $message
     * @return array
     */
    public function getArrayFailed(string $message = ''): array
    {
        return [
            'status' => false,
            'message' => $message === '' ? $this->message : $message,
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
