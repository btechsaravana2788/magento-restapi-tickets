<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model;

use Magento\Framework\Model\AbstractModel;

class Ticket extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Ticket::class);
    }
}
