<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtopic;
use App\Models\Folder;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Auth;

class SubtopicController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'subtopic' => 'required|string|max:255',
        ]);

        Subtopic::create([
            'department_id' => $request->department_id,
            'name' => $request->subtopic,
        ]);

        return back()->with('success', 'Subtopic added successfully!');
    }

    public function edit($id)
    {
        $subtopic = Subtopic::findOrFail($id);
        return view('subtopics.edit', compact('subtopic'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $subtopic = Subtopic::findOrFail($id);
        $subtopic->update(['name' => $request->name]);

        return redirect()->route('subtopics.show', $id)->with('success', 'Subtopic updated successfully.');
    }

    public function destroy($id)
    {
        $subtopic = Subtopic::findOrFail($id);
        $subtopic->delete();

        return redirect()->route('dashboard')->with('success', 'Subtopic deleted successfully.');
    }

    public function show($id)
    {
        $subtopic = Subtopic::with(['department', 'folders' => function($query) {
            $query->withCount('documents');
        }])->findOrFail($id);
        $areaFolders = self::getAreaFolders();
        return view('subtopics.show', compact('subtopic', 'areaFolders'));
    }

    public function generateFolders(Request $request, $id)
    {
        ini_set('max_execution_time', 600);
        set_time_limit(600);

        $user = Auth::user();

        if (!in_array($user->role, ['QA', 'Accreditor'])) {
            return redirect()->back()->with('error', 'You do not have permission to generate folders.');
        }

        $request->validate([
            'area' => 'required|string',
        ]);

        $subtopic = Subtopic::findOrFail($id);

        // Check if folders have already been generated
        if ($subtopic->has_generated_folders) {
            return redirect()->back()->with('error', 'Folders have already been generated for this subtopic.');
        }

        // Map full area names to their corresponding keys
        $areaMapping = [
            'Area I: Vision, Mission, Goals and Objectives' => 'Area 1',
            'AREA II: FACULTY' => 'Area 2',
            'AREA III: CURRICULUM AND INSTRUCTIONS' => 'Area 3',
            'AREA IV: SUPPORT TO STUDENTS' => 'Area 4',
            'AREA V: RESEARCH' => 'Area 5',
            'AREA VI: EXTENSION AND COMMUNITY INVOLVEMENT' => 'Area 6',
            'Area VII: Research Agenda and Priorities' => 'Area 7',
            'Area VIII: Campus and Site' => 'Area 8',
            'Area IX: Laboratory Management and Safety' => 'Area 9',
            'Area X: Organizational Structure' => 'Area 10'
        ];

        $areaKey = $areaMapping[$request->input('area')] ?? null;
        
        if (!$areaKey) {
            return redirect()->back()->with('error', 'Invalid area selected.');
        }

        $areaFolders = self::getAreaFolders();

        if (!array_key_exists($areaKey, $areaFolders)) {
            return redirect()->back()->with('error', 'Invalid area selected.');
        }

        $drive = new GoogleDriveService();
        $mainParentDriveId = env('GOOGLE_DRIVE_FOLDER_ID');
        $subtopicDriveFolderId = $drive->createFolder($subtopic->name, $mainParentDriveId);

        if (!$subtopicDriveFolderId) {
            return redirect()->back()->with('error', 'Failed to create folder on Google Drive.');
        }

        $subtopic->update(['drive_id' => $subtopicDriveFolderId]);

        \Log::info('Subtopic Drive Folder ID:', ['drive_id' => $subtopicDriveFolderId]);

        $areaData = $areaFolders[$areaKey];
        $currentMainFolder = null;
        $currentMainDriveId = null;

        foreach ($areaData as $folderName) {
            if (preg_match('/^[A-Z]\. /', $folderName)) {
                $currentMainFolder = $folderName;
                $currentMainDriveId = $drive->createFolder($currentMainFolder, $subtopicDriveFolderId);

                if (!$currentMainDriveId) {
                    \Log::error('Failed to create main folder on Google Drive', ['folder' => $currentMainFolder]);
                }

                Folder::updateOrCreate(
                    ['subtopic_id' => $subtopic->id, 'name' => $currentMainFolder],
                    [
                        'path' => $currentMainFolder,
                        'drive_id' => $currentMainDriveId,
                    ]
                );
            } else {
                if ($currentMainFolder && $currentMainDriveId) {
                    $subDriveId = $drive->createFolder($folderName, $currentMainDriveId);

                    if (!$subDriveId) {
                        \Log::error('Failed to create subfolder on Google Drive', ['folder' => $folderName]);
                    }

                    Folder::updateOrCreate(
                        ['subtopic_id' => $subtopic->id, 'name' => $folderName],
                        [
                            'path' => $folderName,
                            'drive_id' => $subDriveId,
                        ]
                    );
                }
            }
        }

        // Mark folders as generated
        $subtopic->update(['has_generated_folders' => true]);

        return redirect()->route('subtopics.show', $id)->with('success', 'Folders generated successfully!');
    }

    private static function getAreaFolders()
    {
        return [
            'Area 1' => [
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
            'Area 2' => [
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
            // Add Area 3 and so on here as needed


            'Area 3' => [
                'A. Curriculum and Program of Studies',
                'A.1. Copy of the Curriculum (with prerequisite courses, where applicable).',
                'A.2. CHED Policies and Standards, CMOs, where applicable.',
                'A.3. Copies of MOA or MOU with agencies/institutions regarding Immersion, OJT, RLE, Practice Teaching and other related activities.',
                'A.4. Minutes of the Academic Council meetings.',
                'A.5. Polices on curriculum development/review.',
                'A.6. Policies on validation of subjects taken by transferees, and accommodation of students with special needs.',
                
                'B. Instructional Process Methodologies and Learning Opportunities.',
                'B.1. Compilation of updated course syllabi in all subjects.',
                'B.2. Evidence/s on remedial programs conducted.',
                'B.3. List of teaching strategies used in the different subject areas.',
                'B.4. Sample course requirements submitted by students.',
                'B.5. Record of class observations.',
                'B.6. List of academic linkages or consortia.',

                'C. Assessment of Academic Performance',
                'C.1. Sample copies of summative examination (midterm and final) with Table of Specifications.',
                'C.2. Samples of non-traditional assessment tools, e.g. rubric, portfolio, etc.',
                'C.3. Samples of assessment tools for individual differences and multiple intelligences.',
                'C.4. Sample class records.',
                'C.5. Copy of the grading system, including evidence that it has been approved.',

                'D. Classroom Management',
                'D.1. Policies on class attendance and discipline.',
                'D.2. Evidence that independent work/performance is encouraged and monitored, such as student outputs.',

                'E. Graduation Requirements',
                'E.1. Policies that apply to student returnees, transferees and students with academic deficiencies including residency.',
                'E.2. Sample copy of a Student\'s Clearance before graduation.',
                'E.4. Policies on OJT (Practice Teaching, RLE, Apprenticeship, Practicum, etc.), if applicable.',

                'F. Administrative Support for Effective Instruction',
                'F.1. Administrative Manual.',
                'F.2. Evidence/s of dialogues conducted among the administration, faculty, and students.',
                'F.3. Schedule of regular faculty consultation hours.',
                'F.4. A system of awards/recognition for graduating students with outstanding achievements.',
                'F.5. Results of a study on the licensure performance of graduates, if applicable.',
                'F.6. Evidence of administrative support to improve licensure performance of graduates, if applicable.',
                'F.7. Conduct of a tracer study on the employability of graduates.',
                'F.8. Feedback from employers regarding performance of graduates.',
],
        'Area 4' => [
                'A. Student Services Program',
                'A.1. A copy of the objectives of the SSP.',
                'A.2. Organizational Chart of the SSP.',
                'A.3. Functional Chart of the SSP.',
                'A.4. Profile of the SSP Staff.',
                'A.5. Copy of the SSP Master Plan.',
                'A.6. Evaluation program to assess the effectiveness of the SSP.',
                'A.7. Inventory of physical facilities, equipment, supplies and materials for the SSP.',

                'B. Admission and Retention',
                'B.1. Bulletin of Information.',
                'B.2. Student Handbook.',
                'B.3. Data on student admission (enrollment trends, drop-out rate, transferees, course shifters, etc.).',

                'C. Guidance Program',
                'C.1. Profile of the Guidance and Counseling Head.',
                'C.2. Updated Student Profiles.',
                'C.3. Policies on the confidentiality of student records.',
                'C.4. A copy of the Testing program.',
                'C.5. List of tests and evaluative tools used in Guidance and Counseling services.',
                'C.6. List of students who availed of the counseling service.',
                'C.7. Sample counseling referral form.',
                'C.8. List of prospective employers of graduates of a particular program.',
                'C.9. Sample letters of employers inviting graduates of a particular program to apply.',
                'C.10. Alumni Directory and officers of the Alumni Association.',
                'C.11. Linkage/s established with industries and prospective employers.',
                'C.12. Copy of the instrument to evaluate the guidance program.',

                'D. Other Student Services',
                'D.1. Copies of the Health Services Program.',
                'D.2. Profile of the Medical/Dental Staff.',
                'D.3. Records of students who availed of Medical/Dental services.',
                'D.4. Copy of sanitary permit for canteen operation.',
                'D.5. Health certificates of the canteen staff and handlers.',

                'Sports Development Program',
                'D.6. Policies on the selection of athletes.',
                'D.7. Budget allocation for sports development.',
                'D.8. Inventory of facilities, equipment, supplies and materials provided to the Sports Services Unit.',
                'D.9. Evidence of monitoring and evaluation of sports activities.',
],



'Area 5' => [
                'A. Priorities and Relevance',
                'A.1. Copy of the institution Research Agenda.',
                'A.2. Structure of the Research and Development Unit including the profile of the Research Head.',
                'A.3. A copy of the research program of the program under survey.',
                'A.4. Evidence of participation of different stakeholders in the formulation of the research agenda.',
                'A.5. Abstracts of researches conducted.',

                'B. Funding and Other Resources',
                'B.1. Copy of the budget allocation for research.',
                'B.2. List of linkages/networking with research funding agencies.',
                'B.3. Inventory of research facilities, equipment and amenities.',
                'B.4. Profile of the research personnel/staff.',
                'B.5. List of patents, licenses, copyrights and other research outputs, including income generated from each of them if any.',
                'B.6. Copy of the research personnel/staff.',
                'B.7. List of term/collaborative researches conducted.',

                'C. Implementation, Monitoring, Evaluation and Utilization of Research Results/Outputs',
                'C.1. Copy of the Research Manual.',
                'C.2. Summary of faculty researches conducted.',
                'C.3. List of in-service training conducted to enhance faculty research capabilities of faculty.',
                'C.4. Report on in-house reviews conducted.',
                'C.5. Evidence/s that research results have been utilized.',
                'C.6. Policies pertaining to Intellectual Property Rights (IPR).',

                'D. Publication and Dissemination',
                'D.1. Evidence of publication and dissemination of research results.',
                'D.2. List of dissemination activities conducted (forum, conference seminars, etc.).',
                'D.3. Copies of published articles.',
                'D.4. Linkage/s established for exchange of research publications.',
                'D.5. Composition of a Technical Committee to edit research manuscripts and technical reports.',
                'D.6. List of faculty who served as paper precentors, lecturers, external evaluators, dissertation/thesis advisers, critics, etc., including relevant information.',
],

'Area 6' => [
                'A. Priorities and Relevance',
                'A.1. Copy of the benchmark survey instrument.',
                'A.2. Evidence of complementation between the curriculum of the program under survey and its extension program.',
                'A.3. List of linkages established with extension-oriented agencies.',
                'A.4. Copies of MOA or MOU with partner or collaborating GA\'s, NGO\'s, and institutions.',

                'B. Planning, Implementation, Monitoring and Evaluation',
                'B.1. Evidence of extension planning sessions.',
                'B.2. Copy of the extension program, including relevant information.',

                '— Implementation —',
                'B.3. Organizational Structure of the Extension Unit.',
                'B.4. Profile of the Unit Head and his/her Staff.',
                'B.5. Operational Plan of the Extension Program, with focus on implementation strategies.',
                'B.6. Roster/Experts for extension projects, if necessary.',
                'B.7. Evidence of transfer of appropriate technology to the target clientele.',
                'B.8. Samples of packaged technologies/news/information disseminated to the clientele.',
                'B.9. Copy of the Extension Manual.',
                'B.10. Copy of the monitoring and evaluation instrument/s.',
                'B.11. Sample accomplishment and terminal reports.',

                '— Funding and Other Resources —',
                'B.12. Copy of the budgetary allocation for the extension program.',
                'B.13. Evidences of outsourcing for fund augmentation.',
                'B.14. Evidences of outsourcing for technical assistance and service inputs from other agencies.',

                'C. Community Involvement and Participation in the Extension Activities',
                'C.1. Evidence of community participation in the planning and implementation of extension projects/activities.',
                'C.2. Evidence of technology adoption, utilization and commercialization.',
                'C.3. Copy of a long-term sustainable extension program, e.g., community development projects, etc.',
                'C.4. List of collaborating agencies, including the nature of collaboration.',

],


'Area 7' => [
    'A. Research Agenda and Priorities',
    'A.1. Approved institutional research agenda.',
    'A.2. Alignment of research agenda with national and local priorities.',
    'A.3. Evidence of stakeholder consultation in the formulation of research priorities.',

    'B. Research Capability',
    'B.1. Profile of research personnel (training, experience, etc.).',
    'B.2. Faculty with completed research trainings.',
    'B.3. Number of research-active faculty.',
    'B.4. Faculty development programs related to research.',

    'C. Research Implementation',
    'C.1. List of completed and ongoing research projects.',
    'C.2. Research proposals with approval and funding.',
    'C.3. Reports and documentation of research activities.',
    'C.4. Monitoring and evaluation reports.',

    'D. Dissemination, Utilization, and Commercialization',
    'D.1. Research publications (journals, books, etc.).',
    'D.2. Participation in conferences, symposia.',
    'D.3. Utilization reports or evidence of use of research outputs.',
    'D.4. Patents, copyrights, utility models filed and approved.',

    'E. Funding and Other Resources',
    'E.1. Annual research budget.',
    'E.2. External and internal sources of research funding.',
    'E.3. Facilities and equipment used for research.',

    'F. Linkages and Networking',
    'F.1. MOAs/MOUs with research institutions.',
    'F.2. Joint research projects.',
    'F.3. Evidence of collaborative research activities.',
],

'Area 8' => [
    'A. Campus and Site',
    'A.1. Campus master plan.',
    'A.2. Vicinity map showing campus layout.',
    'A.3. Site development plan.',
    'A.4. Building and facility inventory.',

    'B. Buildings and Grounds',
    'B.1. Floor plan of buildings.',
    'B.2. Schedule and records of building maintenance.',
    'B.3. Pictures of existing buildings and grounds.',

    'C. Classrooms and Offices',
    'C.1. List of classrooms with seating capacity.',
    'C.2. Inventory of office spaces and users.',
    'C.3. Schedule of classroom utilization.',

    'D. Sanitation and Waste Disposal',
    'D.1. Restroom facility plan and maintenance reports.',
    'D.2. Solid waste management plan.',
    'D.3. Contracts with waste disposal service providers.',

    'E. Health, Safety and Security',
    'E.1. Health clinic equipment inventory.',
    'E.2. Fire safety inspection certificate.',
    'E.3. Emergency response plan and drills documentation.',
    'E.4. Security personnel records and incident reports.',

    'F. Equipment and Facilities',
    'F.1. Inventory of instructional facilities and equipment.',
    'F.2. Maintenance records of major equipment.',
    'F.3. Procurement and depreciation records.',
],

'Area 9' => [
    'A. Laboratory Management and Safety',
    'A.1. Laboratory operations manual.',
    'A.2. Laboratory safety guidelines and procedures.',
    'A.3. Safety inspection reports.',
    'A.4. MSDS (Material Safety Data Sheets) for chemicals.',

    'B. Laboratory Equipment and Supplies',
    'B.1. Inventory of laboratory tools, equipment, and consumables.',
    'B.2. Calibration and maintenance logs.',
    'B.3. Procurement plans and purchase orders.',

    'C. Laboratory Utilization',
    'C.1. Laboratory schedules and utilization reports.',
    'C.2. Records of student laboratory outputs.',
    'C.3. Feedback from students and faculty on lab adequacy.',

    'D. Technical Support and Staffing',
    'D.1. Personnel complement and job descriptions.',
    'D.2. Training records of lab technicians.',
    'D.3. Performance evaluation of lab staff.',
],

'Area 10' => [
    'A. Organizational Structure',
    'A.1. Organizational chart of the institution.',
    'A.2. Job descriptions and functions of administrators.',
    'A.3. Appointment papers and contracts.',

    'B. Administrative Services',
    'B.1. Operations manual for administrative units.',
    'B.2. Records management policies and procedures.',
    'B.3. Personnel records and 201 files.',

    'C. Planning and Development',
    'C.1. Strategic plan of the institution.',
    'C.2. Development plans (short-term, medium-term).',
    'C.3. Implementation and monitoring reports.',

    'D. Financial Management',
    'D.1. Annual budget and audited financial statements.',
    'D.2. Procurement plans and bidding documents.',
    'D.3. Reports on fund utilization.',

    'E. Human Resource Management',
    'E.1. HR manual and policies.',
    'E.2. Records of recruitment and promotions.',
    'E.3. Professional development activities.',

    'F. Transparency and Accountability',
    'F.1. Citizen\'s Charter.',
    'F.2. Anti-corruption and good governance initiatives.',
    'F.3. Internal audit reports.',
]

        ];
    }

    private static function getAreaNames()
    {
        return [
            'Area I: Vision, Mission, Goals and Objectives',
            'AREA II: FACULTY',
            'AREA III: CURRICULUM AND INSTRUCTIONS',
            'AREA IV: SUPPORT TO STUDENTS',
            'AREA V: RESEARCH',
            'AREA VI: EXTENSION AND COMMUNITY INVOLVEMENT',
            'Area VII: Research Agenda and Priorities',
            'Area VIII: Campus and Site',
            'Area IX: Laboratory Management and Safety',
            'Area X: Organizational Structure'
        ];
    }
}

