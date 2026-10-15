<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class TicketMessage extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('magento_tickets_support_ticket_message', 'message_id');
    }
}
