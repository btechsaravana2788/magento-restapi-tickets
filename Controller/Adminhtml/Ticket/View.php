<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\PageFactory;
use MagentoTickets\SupportTicketApi\Model\TicketFactory;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket as TicketResource;

class View extends Action
{
    public const ADMIN_RESOURCE = 'MagentoTickets_SupportTicketApi::manage';
    private const CURRENT_TICKET = 'current_support_ticket';

    public function __construct(
        Action\Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly TicketFactory $ticketFactory,
        private readonly TicketResource $ticketResource,
        private readonly Registry $registry
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $ticketId = (int)$this->getRequest()->getParam('ticket_id');
        $ticket = $this->ticketFactory->create();
        $this->ticketResource->load($ticket, $ticketId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('The requested support ticket does not exist.'));
        }
        $this->registry->register(self::CURRENT_TICKET, $ticket);

        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('MagentoTickets_SupportTicketApi::tickets');
        $page->getConfig()->getTitle()->prepend(__('Ticket #%1', $ticketId));
        return $page;
    }
}
