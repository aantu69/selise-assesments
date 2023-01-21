<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $results = DB::select("SELECT u.name, u.roll_number, u.email, p.fathers_name, p.mothers_name, p.birth_date, p.gender, p.blood_group, p.nationality, p.national_identity, p.identity_number, p.mobile,
        t1.SSCInstitute, t1.SSCPassingYear, t1.SSCResult, t1.HSCInstitute, t1.HSCPassingYear, t1.HSCResult, t1.BachelorInstitute, t1.BachelorPassingYear, t1.BachelorResult, t1.MastersInstitute, t1.MastersPassingYear, t1.MastersResult,
        t2.Preference1, t2.Preference2, t2.Preference3, t2.Preference4 FROM users u LEFT JOIN user_profiles p ON u.id=p.user_id LEFT JOIN (SELECT
      user_id,
      MAX(IF(examination = 'SSC/O-level/Equivalent *', institute, NULL)) AS 'SSCInstitute',
      MAX(IF(examination = 'SSC/O-level/Equivalent *', passing_year, NULL)) AS 'SSCPassingYear',
      MAX(IF(examination = 'SSC/O-level/Equivalent *', result, NULL)) AS 'SSCResult',
      MAX(IF(examination = 'HSC/A-level/Equivalent *', institute, NULL)) AS 'HSCInstitute',
      MAX(IF(examination = 'HSC/A-level/Equivalent *', passing_year, NULL)) AS 'HSCPassingYear',
      MAX(IF(examination = 'HSC/A-level/Equivalent *', result, NULL)) AS 'HSCResult',
      MAX(IF(examination = 'Bachelor *', institute, NULL)) AS 'BachelorInstitute',
      MAX(IF(examination = 'Bachelor *', passing_year, NULL)) AS 'BachelorPassingYear',
      MAX(IF(examination = 'Bachelor *', result, NULL)) AS 'BachelorResult',
      MAX(IF(examination = 'Masters', institute, NULL)) AS 'MastersInstitute',
      MAX(IF(examination = 'Masters', passing_year, NULL)) AS 'MastersPassingYear',
      MAX(IF(examination = 'Masters', result, NULL)) AS 'MastersResult'
FROM academic_qualifications
GROUP BY user_id) AS t1 ON u.id = t1.user_id LEFT JOIN (SELECT user_id,
MAX(IF(position = 1, subject, NULL)) AS 'Preference1',
MAX(IF(position = 2, subject, NULL)) AS 'Preference2',
MAX(IF(position = 3, subject, NULL)) AS 'Preference3',
MAX(IF(position = 4, subject, NULL)) AS 'Preference4'
FROM preferences GROUP BY user_id) AS t2 ON u.id = t2.user_id ORDER BY u.id");
        // dd($results);

        return collect($results);
    }

    public function headings(): array
    {
        return [
            "Name",
            "Roll Number",
            "Email",
            "Fathers Name",
            "Mothers Name",
            "Birth Date",
            "Gender",
            "Blood Group",
            "Nationality",
            "National Identity",
            "Identity Number",
            "Mobile",
            "SSCInstitute",
            "SSCPassingYear",
            "SSCResult",
            "HSCInstitute",
            "HSCPassingYear",
            "HSCResult",
            "BachelorInstitute",
            "BachelorPassingYear",
            "BachelorResult",
            "MastersInstitute",
            "MastersPassingYear",
            "MastersResult",
            "Preference1",
            "Preference2",
            "Preference3",
            "Preference4"
        ];
    }
}
