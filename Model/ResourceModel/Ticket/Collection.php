<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MagentoTickets\SupportTicketApi\Model\Ticket as TicketModel;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket as TicketResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(TicketModel::class, TicketResource::class);
    }
}
