<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category  module
 * @package   afterpay
 * @author    OXID Professional services
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace OxidProfessionalServices\ArvatoAfterpayModule\Core;

/**
 * Class Service
 */
class Service
{
    /**
     * @var CaptureResponseEntity result entity.
     */
    protected $_entity;

    /**
     * @return CaptureShippingResponseEntity
     */
    protected function getEntity()
    {
        return $this->_entity;
    }

    /**
     * @var AfterpayOrder
     */
    protected $_afterpayOrder;

    /**
     * @var oxOrder
     */
    protected $_oxOrder;

    /**
     * @var oxLang Current language.
     */
    protected $_lang;

    /**
     * @var oxSession Current session.
     */
    protected $_session;

    /**
     * @var int Last error code, eg. OrderController::ARVATO_ORDER_STATE_CHECKADDRESS
     */
    protected $_iLastErrorNo = 0;

    /**
     * Resturns the error messages from a request.
     *
     * @return string
     */
    public function getErrorMessages()
    {
        if ($this->getEntity() && $this->getEntity()->getCustomerFacingMessage()) {
            return $this->getEntity()->getCustomerFacingMessage();
        }

        $errorMessages = [];

        if ($this->getEntity() && is_array($this->getEntity()->getErrors()) && count($this->getEntity()->getErrors())) {
            $businessErrors = $this->getEntity()->getErrors();

            foreach ($businessErrors as $businessError) {
                if (is_array($businessError)) {
                    $businessError = reset($businessError);
                }

                if ($businessError instanceof ResponseMessageEntity) {
                    $errorMessages[] = $businessError->exportData()->customerFacingMessage ?: $businessError->exportData()->message;
                } elseif ($businessError instanceof stdClass) {
                    $errorMessages[] = $businessError->customerFacingMessage ?: $businessError->message;
                }
            }
        }

        return join('; ', $errorMessages);
    }

    /**
     * @return int
     */
    public function getLastErrorNo()
    {
        if ($this->getEntity() && $this->getEntity()->getCustomerFacingMessage()) {
            return \OxidEsales\Eshop\Application\Model\Order::ORDER_STATE_PAYMENTERROR;
        }

        return $this->_iLastErrorNo;
    }

    /**
     * Gets an entity from the service result.
     *
     * @param stdClass|array $response
     *
     * @return Entity
     * @throws CurlException
     */
    protected function parseResponse($response)
    {
        $base = $this->getBaseClassName();
        if (is_array($response)) {
            $entity = oxNew('\\OxidProfessionalServices\\ArvatoAfterpayModule\\Application\\Model\\Entity\\' . $base . 'ResponseEntity');
            $messages = [];
            foreach ($response as $item) {
                $messages[] = oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser\ResponseMessageParser::class)->parse($item);
            }
            $entity->setErrors($messages);
        } elseif (is_object($response)) {
            $entity = oxNew('\\OxidProfessionalServices\\ArvatoAfterpayModule\\Application\\Model\\Parser\\' . $base . 'ResponseParser')->parse($response);
        } else {
            throw new \OxidProfessionalServices\ArvatoAfterpayModule\Core\Exception\CurlException('Cannot parse non-StdClass response ' . serialize($response));
        }
        return $entity;
    }

    protected function getBaseClassName()
    {
        $sClassName = str_replace(__NAMESPACE__ . '\\', '', get_class($this));
        if (0 === strpos($sClassName, 'Mock_')) {
            // Unit Test helper: Turn mocked Mock_someClass_a1b2c3d into someClass
            $sClassName = substr($sClassName, 5);
            $sClassName = substr($sClassName, 0, -9);
        }

        $sClassName = str_replace('Service', '', $sClassName);

        return $sClassName;
    }
}
