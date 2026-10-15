<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\PageFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'MagentoTickets_SupportTicketApi::manage';

    public function __construct(
        Action\Context $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $page = $this->resultPageFactory->create();
        $page->setActiveMenu(self::ADMIN_RESOURCE);
        $page->getConfig()->getTitle()->prepend(__('Support Tickets'));
        return $page;
    }
}
