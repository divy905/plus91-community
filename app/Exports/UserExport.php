<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user= User::where('is_delete',0)->select('name','email','phone','gender','maritl_status','education')->get();

        foreach($user as $u)
        {
            if($u->gender==0)
            {
                $u->gender='Male';
            }
            if($u->gender==1)
            {
                $u->gender='Female';
            }
            if($u->gender==2)
            {
                $u->gender='Other';
            }
        }
        return $user;
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Phone', 'Gender', 'Marital Status', 'Education'
            
        ];
    }
}
