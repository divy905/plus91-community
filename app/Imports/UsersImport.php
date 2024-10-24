<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    function excelSerialDateToDate($serialDate)
    {
        // Base date for Excel serial date
        $baseDate = Carbon::createFromFormat('Y-m-d', '1900-01-01');

        // Excel serial date is 1-based, so we subtract 1
        return $baseDate->addDays($serialDate - 2)->format('Y-m-d');
    }

    public function model(array $row)
    {    
        if (isset($row['is_head']) && ($row['is_head'] == 1 || $row['is_head'] == 0)) {
            $head = null;
            
            if ($row['is_head'] == 0) {
                // Get the latest 'head_of_family' from the User model
                $headdata = User::whereNull('head_of_family')->orderBy('id', 'desc')->first();
                $head = $headdata ? $headdata->id : null;
            }

            // Fetch the 'gotra' from the database
            $gotra = DB::table('goatra')->where('name', $row['gotra'])->first();
            $gotra_id = $gotra ? $gotra->id : null;
            $village = DB::table('native_villags') // Use uppercase table name
            ->where('name', 'LIKE', '%' . $row['village'] . '%') // LIKE query
            ->first();

            $village_id = $village ? $village->id : null; 
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
		 'native_village_id'            => $village_id,
                'firm_address'        => $row['firm_address'],
                'residence_address'        => $row['residence_address'],
		'native_full_address'        => $row['native_full_address'],
                'member_id'        => $row['member_id'],
                'cmpny_name'        => $row['business_name'],
                'designation'        => $row['industry'],
            ]);
        }

        return null; // Return null if the condition is not met
        
    }
}
