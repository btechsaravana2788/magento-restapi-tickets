<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Ticket extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('magento_tickets_support_ticket', 'ticket_id');
    }
}
