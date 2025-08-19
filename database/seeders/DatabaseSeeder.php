<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FolderStructure;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Remove 'email_verified_at' from the user seeder
        /*
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);
        */
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed folder structures for all areas
        $areas = [
            [
                'area_key' => 'Area 1',
                'area_name' => 'Area I: Vision, Mission, Goals and Objectives',
                'folders' => [
                    'A. Statement of Vision, Mission, Goals and Objectives.',
                    'A.1. Vision Statement.',
                    'A.2. Mission Statement.',
                    'A.3. Statement of the Goals of the Academic Unit.',
                    'A.4. Statement of the Program Objectives.',
                    'A.5. Copy of the Charter of the Institution.',
                    'A.6. Minutes of Meetings on the formulation, review and revision of the VMGO.',
                    'A.7. File Copies of Letters of Invitation to Participants.',
                    'A.8. Attendance Record of Stakeholder-Participants.',
                    'A.9. Copies of CMOs relevant to VMGO formulation, if any.',
                    'B. Dissemination and Acceptability',
                    'B.1. Display boards on which the VMGO are posted.',
                    'B.2. Samples of dissemination materials (brochures, leaflets, flyers, etc.)',
                    'B.3. Evidence/s of awareness and acceptability of the VMGO.',
                    'C. Congruence and Implementation',
                    'C.1. Evidence/s of congruence between educational practices/activities and the VMGO.',
                    'C.2. Awards/citations received by the program under survey.',
                    'C.3. List of linkages, consortia and networking.',
                    'C.4. Data on employability of graduates.',
                ],
            ],
            [
                'area_key' => 'Area 2',
                'area_name' => 'AREA II: FACULTY',
                'folders' => [
                    'A. Academic Qualifications and Professional Experience',
                    'A.1. Copy of Qualification Standards.',
                    'A.2. The Faculty\'s Personal Data Sheet.',
                    'A.3. Profile of the faculty according to:',
                    'A.3.1. educational qualification,',
                    'A.3.2. length of academic experience,',
                    'A.3.3. field of specialization, if applicable.',
                    'A.4. List of Faculty who have received academic awards/recognition.',
                    'B. Recruitment, Selection and Orientation',
                    'B.1. Policies on hiring and selection',
                    'B.2. Criteria used in the selection process.',
                    'B.3. Composition of the Screening Committee.',
                    'B.4. Evidence of the selection process showing the names of applicants.',
                    'B.5. Evidence/s of the Orientation Program for newly-hired faculty.',
                    'B.6. Policies on inbreeding.',
                    'C. Faculty Adequacy and Loading',
                    'C.1. Roster of faculty with valid professional license, if applicable.',
                    'C.2. Faculty Manual',
                    'C.3. Copy of the loading system.',
                    'C.4. Report on faculty:student ratio.',
                    'C.5. Files of individual Faculty Load.',
                    'D. Rank and Tenure',
                    'D.1. Policies on rank and tenure, including pertinent board resolutions.',
                    'D.2. Copy of the Merit System and Promotion Plan.',
                    'D.3. Profile of the faculty according to:',
                    'D.3.1. appointment status,',
                    'D.3.2. academic rank.',
                    'D.4. List of faculty arranged according to academic rank.',
                    'E. Faculty Development',
                    'E.1. Copy of the Faculty Development Program.',
                    'E.2. Profile of faculty who were granted scholarships, fellowships, etc.',
                    'E.3. File copies of Scholarship/ Fellowship/Training Contract.',
                    'E.4. Summary of in-service training conducted in campus by the program under survey, including list of faculty-participants.',
                    'E.5. Budgetary allocation for faculty development.',
                    'F. Professional Performance and Scholarly Works',
                    'F.1. Updated course syllabi of individual faculty.',
                    'F.2. Samples of instructional materials developed and produced by the faculty (workbook, manual, module, ICT materials etc.)',
                    'F.3. Composition and Profile of the Instructional Materials Development Committee',
                    'F.4. Faculty who served as lecturer, resource person, consultant in his/her field of specialization as well as in allied disciplines.',
                    'F.5. List of publications where faculty outputs are published.',
                    'G. Salaries, Fringe Benefits and Incentives',
                    'G.1. Policies and guidelines on salaries, benefits and privileges including the system of avallment',
                    'G.2. List of privileges, tringe benefits as well as incentives.',
                    'G.3. Copy of the Plantilla',
                    'G.4. Evidence/s that tringe benefits and incentives are provided to the faculty.',
                    'G.5. Description of the Faculty Performance Evaluation System, including the instrument/s used.',
                    'G.6. List of faculty given recognition/ award/credits for outstanding performance and production of scholarly works.',
                    'H. Professionalism',
                    'H.1. Evidence/s on faculty attendance in class and other institutional activities.',
                    'H.2. Minutes of Meetings Conducted.',
                    'H.3. Evidence on Submission of Required Reports by the faculty.',
                    'H.4. Personnel Records on Administrative/Disciplinary Cases, if any',
                    'H.5. Records of termination cases, if any.',
                    'H.6. Evidence/s of professional growth (advanced studies and attendance to seminars and other in service training)',
                    'H.7. Code of Professional Ethics/RA 6713 and other pertinent CSC issuances.',
                    'H.8. Evidence/s of dissemination and observance of RA 6713, the Citizen\'s Charter and other pertinent legal issuances.',
                ],
            ],
            // ... Add all other areas similarly ...
        ];

        foreach ($areas as $area) {
            FolderStructure::create($area);
        }
    }
}
