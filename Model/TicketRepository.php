<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterfaceFactory;
use MagentoTickets\SupportTicketApi\Api\Data\TicketMessageInterfaceFactory;
use MagentoTickets\SupportTicketApi\Api\TicketRepositoryInterface;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket as TicketResource;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\TicketMessage\CollectionFactory as MessageCollectionFactory;

class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        private readonly TicketFactory $ticketFactory,
        private readonly TicketResource $ticketResource,
        private readonly TicketInterfaceFactory $ticketDataFactory,
        private readonly TicketMessageInterfaceFactory $messageDataFactory,
        private readonly MessageCollectionFactory $messageCollectionFactory
    ) {
    }

    public function getById(int $ticketId): TicketInterface
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResource->load($ticket, $ticketId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('Ticket with ID "%1" does not exist.', $ticketId));
        }

        $messages = $this->messageCollectionFactory->create()
            ->addFieldToFilter('ticket_id', $ticketId)
            ->setOrder('created_at', 'ASC');
        $messageData = [];
        foreach ($messages as $message) {
            $messageData[] = $this->messageDataFactory->create(['data' => $message->getData()]);
        }

        return $this->ticketDataFactory->create([
            'data' => array_merge($ticket->getData(), ['messages' => $messageData])
        ]);
    }
}
