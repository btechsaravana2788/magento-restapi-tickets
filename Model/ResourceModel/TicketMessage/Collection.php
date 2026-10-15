<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\ResourceModel\TicketMessage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MagentoTickets\SupportTicketApi\Model\TicketMessage as TicketMessageModel;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\TicketMessage as TicketMessageResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(TicketMessageModel::class, TicketMessageResource::class);
    }
}
