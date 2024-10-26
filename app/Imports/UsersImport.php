<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Convert Excel serial date or string date to YYYY-MM-DD format.
     *
     * @param mixed $serialDate
     * @return string|null
     */
    function excelSerialDateToDate($serialDate)
    {
        if (is_numeric($serialDate)) {
            // Handle Excel serial date format
            $baseDate = Carbon::createFromFormat('Y-m-d', '1900-01-01');
            return $baseDate->addDays($serialDate - 2)->format('Y-m-d');
        } elseif (strtotime($serialDate)) {
            // Handle standard date strings
            return Carbon::parse($serialDate)->format('Y-m-d');
        }

        return null; // Return null if date format is invalid
    }

    /**
     * Map row data to User model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (isset($row['is_head']) && ($row['is_head'] == 1 || $row['is_head'] == 0)) {
            $head = null;

            if ($row['is_head'] == 0) {
                $headdata = User::whereNull('head_of_family')->orderBy('id', 'desc')->first();
                $head = $headdata ? $headdata->id : null;
            }

            // Fetch the 'gotra' and 'sasural_gotra' IDs
            $gotra = DB::table('goatra')->where('name', $row['gotra'])->first();
            $gotra_id = $gotra ? (int)$gotra->id : null;
            Log::info('Gotra ID Retrieved:', ['gotra_name' => $row['gotra'], 'gotra_id' => $gotra_id]);

            $sasural_gotra = DB::table('goatra')->where('name', $row['sasural_gotra'])->first();
            $sasural_gotra_id = $sasural_gotra ? (int)$sasural_gotra->id : null;
            Log::info('Sasural Gotra ID Retrieved:', ['sasural_gotra_name' => $row['sasural_gotra'], 'sasural_gotra_id' => $sasural_gotra_id]);

            // Fetch the 'village' data
            $village = DB::table('native_villags')
                ->where('name', 'LIKE', '%' . $row['village'] . '%')
                ->first();
            $village_id = $village ? $village->id : null;

            // Convert 'dob' date format
            $formattedDate = $this->excelSerialDateToDate($row['dob']);

            // Return a new User model instance with the data
            return new User([
                'name'                => $row['name'],
                'email'               => $row['email'],
                'phone'               => $row['phone'],
                'gender'              => $row['gender'] ?? 0,
                'head_of_family'      => $head,
                'relation_with_head'  => $row['relation_with_hof'],
                'dob'                 => $formattedDate,
                'bld_group'           => $row['blood_group'],
                'education'           => $row['education'],
                'gotra_id'            => $gotra_id,
                'native_village_id'   => $village_id,
                'firm_address'        => $row['firm_address'],
                'residence_address'   => $row['residence_address'],
                'native_full_address' => $row['native_full_address'],
                'member_id'           => $row['member_id'],
                'cmpny_name'          => $row['business_name'],
                'designation'         => $row['designation'],
                'maritl_status'       => $row['maritl_status'],
                'industry'            => $row['industry'],
                'sasural_gotra_id'    => $sasural_gotra_id,
            ]);
        }

        return null;
    }
}
