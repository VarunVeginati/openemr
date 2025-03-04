<?php

/**
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Ken Chapple <ken@mi-squared.com>
 * @copyright Copyright (c) 2021 Ken Chapple <ken@mi-squared.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU GeneralPublic License 3
 */

namespace OpenEMR\Services\Qdm\Services;

use OpenEMR\Cqm\Qdm\BaseTypes\DateTime;
use OpenEMR\Cqm\Qdm\BaseTypes\Interval;
use OpenEMR\Cqm\Qdm\BaseTypes\Quantity;
use OpenEMR\Cqm\Qdm\EncounterPerformed;
use OpenEMR\Services\Qdm\Interfaces\QdmServiceInterface;

class EncounterService extends AbstractQdmService implements QdmServiceInterface
{
    public function getSqlStatement()
    {
        // Get the encounter, and also collect the duration of the encounter using the appointment category
        $sql = "SELECT
                    FE.encounter,
                    FE.pid,
                    FE.date,
                    FE.encounter_type_code,
                    FE.discharge_disposition,
                    LISTS.diagnosis,
                    L.codes AS discharge_dispo_code,
                    C.pc_duration
                FROM form_encounter FE
                LEFT JOIN issue_encounter IE ON FE.encounter = IE.encounter
                LEFT JOIN lists LISTS ON IE.list_id = LISTS.id
                LEFT JOIN openemr_postcalendar_categories C ON FE.pc_catid = C.pc_catid
                LEFT JOIN list_options L ON FE.discharge_disposition = L.option_id AND L.list_id = 'discharge-disposition'
                ";

        return $sql;
    }

    public function getPatientIdColumn()
    {
        return 'FE.pid';
    }

    public function makeQdmModel(array $record)
    {
        // Convert the encounter datetime into a DateTime Object so we can calculate end time based on appt category duration
        $start_tmp = \DateTime::createFromFormat('Y-m-d H:i:s', $record['date']);
        // DateTime->modify() modifies the calling object, so we need to copy our start date
        $start = clone $start_tmp;
        $duration = 3600; // $record['pc_duration'];
        $end = $start_tmp->modify('+' . $duration . 'second');

        // Get the difference in days for the length of stay (will usually be 0)
        // Format string "%a" is literal days https://www.php.net/manual/en/dateinterval.format.php
        $days = $end->diff($start)->format("%a");

        $qdmRecord = new EncounterPerformed([
            'relevantPeriod' => new Interval([
                'low' =>  new DateTime([
                    'date' => $start->format('Y-m-d H:i:s')
                ]),
                'high' => new DateTime([
                    'date' => $end->format('Y-m-d H:i:s')
                ]),
                'lowClosed' => $record['date'] ? true : false,
                'highClosed' => $record['date'] ? true : false
            ]),
            'admissionSource' => null,
            'dischargeDisposition' => $this->makeQdmCode($record['discharge_dispo_code']) ?? null,
            'facilityLocations' => [],
            'lengthOfStay' => new Quantity([
                'value' => $days,
                'unit' => 'd'
                ]),
            'negationRationale' => null,
            'diagnoses' => $this->makeQdmCode($record['diagnosis']) ?? null
        ]);

        $codes = $this->explodeAndMakeCodeArray($record['encounter_type_code']);
        foreach ($codes as $code) {
            $qdmRecord->addCode($code);
        }

        return $qdmRecord;
    }
}
