<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Api\Data;

interface TicketInterface
{
    public const TICKET_ID = 'ticket_id';
    public const CUSTOMER_ID = 'customer_id';
    public const ORDER_ID = 'order_id';
    public const STORE_ID = 'store_id';
    public const SUBJECT = 'subject';
    public const STATUS = 'status';
    public const PRIORITY = 'priority';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const MESSAGES = 'messages';

    public function getTicketId(): ?int;

    public function setTicketId(int $ticketId): self;

    public function getCustomerId(): ?int;

    public function setCustomerId(int $customerId): self;

    public function getOrderId(): ?int;

    public function setOrderId(int $orderId): self;

    public function getStoreId(): ?int;

    public function setStoreId(int $storeId): self;

    public function getSubject(): ?string;

    public function setSubject(string $subject): self;

    public function getStatus(): ?string;

    public function setStatus(string $status): self;

    public function getPriority(): ?string;

    public function setPriority(string $priority): self;

    public function getCreatedAt(): ?string;

    public function setCreatedAt(string $createdAt): self;

    public function getUpdatedAt(): ?string;

    public function setUpdatedAt(string $updatedAt): self;

    /**
     * @return \MagentoTickets\SupportTicketApi\Api\Data\TicketMessageInterface[]
     */
    public function getMessages(): array;

    /**
     * @param \MagentoTickets\SupportTicketApi\Api\Data\TicketMessageInterface[] $messages
     */
    public function setMessages(array $messages): self;
}
