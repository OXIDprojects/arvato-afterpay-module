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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser;

/**
 * Class VoidResponseParser: Parser for the capture response.
 */
class VoidResponseParser extends \OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Parser\Parser
{

    public function parse(\stdClass $object)
    {
        $this->aFields = [
            'totalAuthorizedAmount',
            'totalCapturedAmount',
        ];
        return parent::parse($object);
    }
}
