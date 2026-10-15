<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Block\Adminhtml\Ticket;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\TicketMessage\CollectionFactory;
use MagentoTickets\SupportTicketApi\Model\Ticket as TicketModel;

class View extends Template
{
    private const CURRENT_TICKET = 'current_support_ticket';

    public function __construct(
        Template\Context $context,
        private readonly Registry $registry,
        private readonly CollectionFactory $messageCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getTicket(): TicketModel
    {
        return $this->registry->registry(self::CURRENT_TICKET);
    }

    public function getMessages(): \MagentoTickets\SupportTicketApi\Model\ResourceModel\TicketMessage\Collection
    {
        return $this->messageCollectionFactory->create()
            ->addFieldToFilter('ticket_id', (int)$this->getTicket()->getId())
            ->setOrder('created_at', 'ASC');
    }

    public function getNextStatus(): ?string
    {
        return [
            'open' => 'in_progress',
            'in_progress' => 'resolved',
            'resolved' => 'closed'
        ][$this->getTicket()->getData('status')] ?? null;
    }
}
