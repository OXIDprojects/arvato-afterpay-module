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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity;

/**
 * Cert. Manual p.21: Classes that are pure data containers don’t include any logic
 * (only getters and setters), can be excluded from test coverage:
 * @codeCoverageIgnore
 */
class ValidateBankAccountResponseEntity extends \OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\Entity
{

    public function getIsValid()
    {
        return $this->getData('isValid');
    }

    public function setIsValid($isValid)
    {
        return $this->setData('isValid', $isValid);
    }
}
