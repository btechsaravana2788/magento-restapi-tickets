<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketInterfaceFactory;
use MagentoTickets\SupportTicketApi\Api\Data\TicketSearchResultsInterface;
use MagentoTickets\SupportTicketApi\Api\Data\TicketSearchResultsInterfaceFactory;
use MagentoTickets\SupportTicketApi\Api\TicketManagementInterface;
use MagentoTickets\SupportTicketApi\Api\TicketRepositoryInterface;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket as TicketResource;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\Ticket\CollectionFactory;
use MagentoTickets\SupportTicketApi\Model\ResourceModel\TicketMessage as TicketMessageResource;

class TicketManagement implements TicketManagementInterface
{
    private const MAX_SUBJECT_LENGTH = 255;
    private const MAX_MESSAGE_LENGTH = 10000;
    private const CUSTOMER_AUTHOR = 'customer';
    private const STAFF_AUTHOR = 'staff';

    public function __construct(
        private readonly UserContextInterface $userContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly TicketFactory $ticketFactory,
        private readonly TicketResource $ticketResource,
        private readonly TicketMessageFactory $messageFactory,
        private readonly TicketMessageResource $messageResource,
        private readonly TicketInterfaceFactory $ticketDataFactory,
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly CollectionFactory $ticketCollectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly TicketSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CustomerReplyNotifier $customerReplyNotifier
    ) {
    }

    public function create(int $customerId, int $orderId, string $subject, string $message): TicketInterface
    {
        $this->assertCustomer($customerId);
        $subject = $this->validateText($subject, self::MAX_SUBJECT_LENGTH, 'Subject');
        $message = $this->validateText($message, self::MAX_MESSAGE_LENGTH, 'Message');
        $order = $this->orderRepository->get($orderId);
        if ((int)$order->getCustomerId() !== $customerId || !$order->getCustomerId()) {
            throw new NoSuchEntityException(__('The order does not exist.'));
        }

        $ticket = $this->ticketFactory->create();
        $ticket->setData([
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'store_id' => (int)$order->getStoreId(),
            'subject' => $subject,
            'status' => 'open',
            'priority' => 'normal'
        ]);
        $connection = $this->ticketResource->getConnection();
        $connection->beginTransaction();
        try {
            $this->ticketResource->save($ticket);
            $this->saveMessage((int)$ticket->getId(), self::CUSTOMER_AUTHOR, $customerId, $message);
            $connection->commit();
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }

        return $this->ticketRepository->getById((int)$ticket->getId());
    }

    public function getList(
        int $customerId,
        SearchCriteriaInterface $searchCriteria
    ): TicketSearchResultsInterface {
        $this->assertCustomer($customerId);
        $collection = $this->ticketCollectionFactory->create();
        $collection->addFieldToFilter('customer_id', $customerId);
        $this->collectionProcessor->process($searchCriteria, $collection);

        $results = $this->searchResultsFactory->create();
        $results->setSearchCriteria($searchCriteria);
        $results->setItems(array_map(
            fn (Ticket $ticket): TicketInterface => $this->ticketDataFactory->create(['data' => $ticket->getData()]),
            $collection->getItems()
        ));
        $results->setTotalCount((int)$collection->getSize());

        return $results;
    }

    public function get(int $customerId, int $ticketId): TicketInterface
    {
        $this->assertCustomer($customerId);
        $ticket = $this->ticketRepository->getById($ticketId);
        if ($ticket->getCustomerId() !== $customerId) {
            throw new NoSuchEntityException(__('Ticket with ID "%1" does not exist.', $ticketId));
        }

        return $ticket;
    }

    public function addCustomerReply(int $customerId, int $ticketId, string $message): TicketInterface
    {
        $this->assertCustomer($customerId);
        $ticket = $this->get($customerId, $ticketId);
        $this->saveMessage(
            $ticketId,
            self::CUSTOMER_AUTHOR,
            $customerId,
            $this->validateText($message, self::MAX_MESSAGE_LENGTH, 'Message')
        );

        return $this->ticketRepository->getById($ticketId);
    }

    public function updateStatus(int $ticketId, string $status): TicketInterface
    {
        $this->assertStaff();
        $transitions = [
            'open' => 'in_progress',
            'in_progress' => 'resolved',
            'resolved' => 'closed'
        ];
        $ticket = $this->loadTicketModel($ticketId);
        if (!isset($transitions[$ticket->getData('status')])
            || $transitions[$ticket->getData('status')] !== $status
        ) {
            throw new LocalizedException(__('The requested ticket status transition is not allowed.'));
        }

        $ticket->setData('status', $status);
        $this->ticketResource->save($ticket);

        return $this->ticketRepository->getById($ticketId);
    }

    public function updatePriority(int $ticketId, string $priority): TicketInterface
    {
        $this->assertStaff();
        if (!in_array($priority, ['low', 'normal', 'high', 'urgent'], true)) {
            throw new LocalizedException(__('The selected ticket priority is invalid.'));
        }

        $ticket = $this->loadTicketModel($ticketId);
        $ticket->setData('priority', $priority);
        $this->ticketResource->save($ticket);

        return $this->ticketRepository->getById($ticketId);
    }

    public function addStaffReply(int $ticketId, string $message): TicketInterface
    {
        $this->assertStaff();
        $ticket = $this->ticketRepository->getById($ticketId);
        $this->saveMessage(
            $ticketId,
            self::STAFF_AUTHOR,
            (int)$this->userContext->getUserId(),
            $this->validateText($message, self::MAX_MESSAGE_LENGTH, 'Message')
        );
        $this->customerReplyNotifier->notify($ticket, $message);

        return $this->ticketRepository->getById($ticketId);
    }

    private function assertCustomer(int $customerId): void
    {
        if ($this->userContext->getUserType() !== UserContextInterface::USER_TYPE_CUSTOMER
            || $customerId <= 0
            || (int)$this->userContext->getUserId() !== $customerId
        ) {
            throw new NoSuchEntityException(__('The requested ticket does not exist.'));
        }
    }

    private function assertStaff(): void
    {
        if ($this->userContext->getUserType() !== UserContextInterface::USER_TYPE_ADMIN) {
            throw new LocalizedException(__('Only authorized staff can perform this action.'));
        }
    }

    private function validateText(string $value, int $maxLength, string $label): string
    {
        $value = trim($value);
        $length = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
        if ($value === '' || $length > $maxLength) {
            throw new LocalizedException(__('%1 must contain between 1 and %2 characters.', $label, $maxLength));
        }

        return $value;
    }

    private function saveMessage(int $ticketId, string $authorType, int $authorId, string $message): void
    {
        $model = $this->messageFactory->create();
        $model->setData([
            'ticket_id' => $ticketId,
            'author_type' => $authorType,
            'author_id' => $authorId,
            'message' => $message
        ]);
        $this->messageResource->save($model);
    }

    private function loadTicketModel(int $ticketId): Ticket
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResource->load($ticket, $ticketId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('Ticket with ID "%1" does not exist.', $ticketId));
        }

        return $ticket;
    }
}
