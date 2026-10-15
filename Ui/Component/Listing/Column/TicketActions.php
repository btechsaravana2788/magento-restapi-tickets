<?php
declare(strict_types=1);

namespace MagentoTickets\SupportTicketApi\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class TicketActions extends Column
{
    private const URL_PATH_VIEW = 'support_ticket/ticket/view';

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $ticketId = (int)$item['ticket_id'];
                $item[$this->getData('name')]['view'] = [
                    'href' => $this->urlBuilder->getUrl(self::URL_PATH_VIEW, ['ticket_id' => $ticketId]),
                    'label' => __('View')
                ];
            }
        }

        return $dataSource;
    }
}
