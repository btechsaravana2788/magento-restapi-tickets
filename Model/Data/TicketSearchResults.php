<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\Data;

use Magento\Framework\Api\SearchResults;
use MagentoTickets\SupportTicketApi\Api\Data\TicketSearchResultsInterface;

class TicketSearchResults extends SearchResults implements TicketSearchResultsInterface
{
}
