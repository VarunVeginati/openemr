<?php
/**
 * @package OpenEMR
 * @link      http://www.open-emr.org
 * @author    Ken Chapple <ken@mi-squared.com>
 * @copyright Copyright (c) 2021 Ken Chapple <ken@mi-squared.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU GeneralPublic License 3
 */

namespace OpenEMR\Cqm\Qdm;

/**
 * OpenEMR\Cqm\Qdm\InterventionPerformed
 *
 * This is a class generated with Laminas\Code\Generator.
 *
 * @QDM Version 5.6
 * @author Ken Chapple <ken@mi-squared.com>
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General
 * Public License 3
 */
class InterventionPerformed extends QDMBaseType
{
    /**
     * @property BaseTypes\DateTime $authorDatetime
     */
    public $authorDatetime = null;

    /**
     * @property BaseTypes\DateTime $relevantDatetime
     */
    public $relevantDatetime = null;

    /**
     * @property BaseTypes\Interval $relevantPeriod
     */
    public $relevantPeriod = null;

    /**
     * @property BaseTypes\Code $reason
     */
    public $reason = null;

    /**
     * @property BaseTypes\Any $result
     */
    public $result = null;

    /**
     * @property BaseTypes\Code $status
     */
    public $status = null;

    /**
     * @property BaseTypes\Code $negationRationale
     */
    public $negationRationale = null;

    /**
     * @property BaseTypes\Any $performer
     */
    public $performer = null;

    /**
     * @property array $relatedTo
     */
    public $relatedTo = [

    ];

    /**
     * @property string $qdmTitle
     */
    public $qdmTitle = 'Intervention, Performed';

    /**
     * @property string $hqmfOid
     */
    public $hqmfOid = '2.16.840.1.113883.10.20.28.4.36';

    /**
     * @property string $qrdaOid
     */
    public $qrdaOid = '';

    /**
     * @property string $qdmCategory
     */
    public $qdmCategory = 'intervention';

    /**
     * @property string $qdmStatus
     */
    public $qdmStatus = 'performed';

    public $_type = 'QDM::InterventionPerformed';
}

