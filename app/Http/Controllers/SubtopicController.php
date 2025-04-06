<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtopic;
use App\Models\Folder;
use App\Services\GoogleDriveService;

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
        $subtopic = Subtopic::with(['department', 'folders'])->findOrFail($id);
        $areaFolders = self::getAreaFolders();

        return view('subtopics.show', compact('subtopic', 'areaFolders'));
    }

    public function generateFolders(Request $request, $subtopicId)
    {
        $request->validate([
            'area' => 'required|string',
        ]);

        $subtopic = Subtopic::findOrFail($subtopicId);
        $areaFolders = self::getAreaFolders();
        $selectedArea = $request->input('area');

        if (!array_key_exists($selectedArea, $areaFolders)) {
            return redirect()->back()->with('error', 'Invalid area selected.');
        }

        // Google Drive Integration
        $drive = new GoogleDriveService();
        $mainParentDriveId = env('GOOGLE_DRIVE_FOLDER_ID');
        $subtopicDriveFolderId = $drive->createFolder($subtopic->name, $mainParentDriveId);

        foreach ($areaFolders[$selectedArea] as $folderName) {
            Folder::firstOrCreate([
                'subtopic_id' => $subtopic->id,
                'name' => $folderName
            ]);

            // Create folder on Google Drive
            $drive->createFolder($folderName, $subtopicDriveFolderId);
        }

        return redirect()->route('subtopics.show', $subtopicId)->with('success', 'Folders generated successfully!');
    }

    /**
     * Centralized folder structure used by both generateFolders and show
     */
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
                'C.4. Data on employability of graduates.'
            ],
            'Area 2' => [
                'A. Academic Qualifications and Professional Experience',
                'A.1. Copy of Qualification Standards.',
                'A.2. The Faculty’s Personal Data Sheet.',
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
                'E.2. Profile of faculty who were granted scholarships, fellowships, etc.'
            ]
        ];
    }
}
