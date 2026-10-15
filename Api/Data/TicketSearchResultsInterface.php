<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Api\Data;

interface TicketSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \MagentoTickets\SupportTicketApi\Api\Data\TicketInterface[]
     */
    public function getItems();

    /**
     * @param \MagentoTickets\SupportTicketApi\Api\Data\TicketInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
