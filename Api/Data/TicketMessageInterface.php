<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Api\Data;

interface TicketMessageInterface
{
    public const MESSAGE_ID = 'message_id';
    public const TICKET_ID = 'ticket_id';
    public const AUTHOR_TYPE = 'author_type';
    public const AUTHOR_ID = 'author_id';
    public const MESSAGE = 'message';
    public const CREATED_AT = 'created_at';

    public function getMessageId(): ?int;

    public function setMessageId(int $messageId): self;

    public function getTicketId(): ?int;

    public function setTicketId(int $ticketId): self;

    public function getAuthorType(): ?string;

    public function setAuthorType(string $authorType): self;

    public function getAuthorId(): ?int;

    public function setAuthorId(int $authorId): self;

    public function getMessage(): ?string;

    public function setMessage(string $message): self;

    public function getCreatedAt(): ?string;

    public function setCreatedAt(string $createdAt): self;
}
