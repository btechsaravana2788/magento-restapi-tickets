<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketSearchResultsInterface;

interface TicketManagementInterface
{
    /**
     * @param int $customerId Authenticated customer ID, injected by the REST framework.
     */
    public function create(
        int $customerId,
        int $orderId,
        string $subject,
        string $message
    ): TicketInterface;

    /**
     * @param int $customerId Authenticated customer ID, injected by the REST framework.
     */
    public function getList(
        int $customerId,
        SearchCriteriaInterface $searchCriteria
    ): TicketSearchResultsInterface;

    /**
     * @param int $customerId Authenticated customer ID, injected by the REST framework.
     */
    public function get(int $customerId, int $ticketId): TicketInterface;

    /**
     * @param int $customerId Authenticated customer ID, injected by the REST framework.
     */
    public function addCustomerReply(
        int $customerId,
        int $ticketId,
        string $message
    ): TicketInterface;

    public function updateStatus(int $ticketId, string $status): TicketInterface;

    public function updatePriority(int $ticketId, string $priority): TicketInterface;

    public function addStaffReply(int $ticketId, string $message): TicketInterface;
}
