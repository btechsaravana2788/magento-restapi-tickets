<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Api;

use MagentoTickets\SupportTicketApi\Api\Data\TicketInterface;

interface TicketRepositoryInterface
{
    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $ticketId): TicketInterface;
}
