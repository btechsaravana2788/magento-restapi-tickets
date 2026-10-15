<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use MagentoTickets\SupportTicketApi\Api\TicketManagementInterface;
use MagentoTickets\SupportTicketApi\Model\TicketFactory;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket as TicketResource;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'MagentoTickets_SupportTicketApi::manage';

    public function __construct(
        Action\Context $context,
        private readonly TicketManagementInterface $ticketManagement,
        private readonly TicketFactory $ticketFactory,
        private readonly TicketResource $ticketResource,
        private readonly RedirectFactory $resultRedirectFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $ticketId = (int)$this->getRequest()->getParam('ticket_id');
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/view', ['ticket_id' => $ticketId]);
        if (!$this->getRequest()->isPost()) {
            $this->messageManager->addErrorMessage(__('Invalid request.'));
            return $redirect;
        }

        try {
            $ticket = $this->ticketFactory->create();
            $this->ticketResource->load($ticket, $ticketId);
            if (!$ticket->getId()) {
                throw new LocalizedException(__('The requested support ticket does not exist.'));
            }

            $status = (string)$this->getRequest()->getParam('status', $ticket->getData('status'));
            if ($status !== $ticket->getData('status')) {
                $this->ticketManagement->updateStatus($ticketId, $status);
            }
            $priority = (string)$this->getRequest()->getParam('priority', $ticket->getData('priority'));
            if ($priority !== $ticket->getData('priority')) {
                $this->ticketManagement->updatePriority($ticketId, $priority);
            }
            $reply = trim((string)$this->getRequest()->getParam('reply', ''));
            if ($reply !== '') {
                $this->ticketManagement->addStaffReply($ticketId, $reply);
            }
            $this->messageManager->addSuccessMessage(__('The ticket was updated.'));
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $redirect;
    }
}
