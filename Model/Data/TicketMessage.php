<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use MagentoTickets\SupportTicketApi\Api\Data\TicketMessageInterface;

class TicketMessage extends AbstractExtensibleObject implements TicketMessageInterface
{
    public function getMessageId(): ?int
    {
        $value = $this->_get(self::MESSAGE_ID);
        return $value === null ? null : (int)$value;
    }

    public function setMessageId(int $messageId): TicketMessageInterface
    {
        return $this->setData(self::MESSAGE_ID, $messageId);
    }

    public function getTicketId(): ?int
    {
        $value = $this->_get(self::TICKET_ID);
        return $value === null ? null : (int)$value;
    }

    public function setTicketId(int $ticketId): TicketMessageInterface
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    public function getAuthorType(): ?string
    {
        return $this->_get(self::AUTHOR_TYPE);
    }

    public function setAuthorType(string $authorType): TicketMessageInterface
    {
        return $this->setData(self::AUTHOR_TYPE, $authorType);
    }

    public function getAuthorId(): ?int
    {
        $value = $this->_get(self::AUTHOR_ID);
        return $value === null ? null : (int)$value;
    }

    public function setAuthorId(int $authorId): TicketMessageInterface
    {
        return $this->setData(self::AUTHOR_ID, $authorId);
    }

    public function getMessage(): ?string
    {
        return $this->_get(self::MESSAGE);
    }

    public function setMessage(string $message): TicketMessageInterface
    {
        return $this->setData(self::MESSAGE, $message);
    }

    public function getCreatedAt(): ?string
    {
        return $this->_get(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): TicketMessageInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
