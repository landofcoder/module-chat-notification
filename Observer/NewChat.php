<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ChatNotification
 * @copyright  Copyright (c) 2022 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\ChatNotification\Observer;
use Magento\Framework\Event\ObserverInterface;

class NewContact implements ObserverInterface
{

    /**
     * @var Slack
     */
    protected $slack;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        Slack $slack,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->slack = $slack;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $data = $event->getData();
        $isNew = $event->getIsNew();
        if ($isNew && !empty($data)) {
            $chatData = [];
            $chatData['customerName'] = $data['customer_name'];
            $chatData['email'] = $data['customer_email'];
            $chatData['comment'] = $data['body_msg'];
            $chatData['store'] = $this->storeManager->getStore()->getName();
            $this->slack->sendMessage("new_chat", $chatData);
        }
    }
}
