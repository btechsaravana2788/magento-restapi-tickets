<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterface;

class Ticket extends AbstractExtensibleObject implements TicketInterface
{
    public function getTicketId(): ?int
    {
        $value = $this->_get(self::TICKET_ID);
        return $value === null ? null : (int)$value;
    }

    public function setTicketId(int $ticketId): TicketInterface
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    public function getCustomerId(): ?int
    {
        $value = $this->_get(self::CUSTOMER_ID);
        return $value === null ? null : (int)$value;
    }

    public function setCustomerId(int $customerId): TicketInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getOrderId(): ?int
    {
        $value = $this->_get(self::ORDER_ID);
        return $value === null ? null : (int)$value;
    }

    public function setOrderId(int $orderId): TicketInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getStoreId(): ?int
    {
        $value = $this->_get(self::STORE_ID);
        return $value === null ? null : (int)$value;
    }

    public function setStoreId(int $storeId): TicketInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    public function getSubject(): ?string
    {
        return $this->_get(self::SUBJECT);
    }

    public function setSubject(string $subject): TicketInterface
    {
        return $this->setData(self::SUBJECT, $subject);
    }

    public function getStatus(): ?string
    {
        return $this->_get(self::STATUS);
    }

    public function setStatus(string $status): TicketInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getPriority(): ?string
    {
        return $this->_get(self::PRIORITY);
    }

    public function setPriority(string $priority): TicketInterface
    {
        return $this->setData(self::PRIORITY, $priority);
    }

    public function getCreatedAt(): ?string
    {
        return $this->_get(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): TicketInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->_get(self::UPDATED_AT);
    }

    public function setUpdatedAt(string $updatedAt): TicketInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    public function getMessages(): array
    {
        return $this->_get(self::MESSAGES) ?? [];
    }

    public function setMessages(array $messages): TicketInterface
    {
        return $this->setData(self::MESSAGES, $messages);
    }
}
