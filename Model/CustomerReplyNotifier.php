<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterface;

class CustomerReplyNotifier
{
    public function __construct(
        private readonly TransportBuilder $transportBuilder,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly StoreManagerInterface $storeManager
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function notify(TicketInterface $ticket, string $message): void
    {
        $order = $this->orderRepository->get((int)$ticket->getOrderId());
        $storeId = (int)$ticket->getStoreId();
        $store = $this->storeManager->getStore($storeId);
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('magento_tickets_support_ticket_reply')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars([
                'ticket_id' => $ticket->getTicketId(),
                'ticket_subject' => $ticket->getSubject(),
                'message' => $message,
                'store' => $store
            ])
            ->setFromByScope('support', $storeId)
            ->addTo(
                (string)$order->getCustomerEmail(),
                trim((string)$order->getCustomerFirstname() . ' ' . (string)$order->getCustomerLastname())
            )
            ->getTransport();
        $transport->sendMessage();
    }
}
