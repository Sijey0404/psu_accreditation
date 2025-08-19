<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GoogleDriveController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubtopicController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\QAController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\AccreditationFolderController;

Route::get('/google-drive/folder/{id}', [GoogleDriveController::class, 'getFolderContents']);

// Documents (Accreditor)
// No conflict with dynamic document routes
Route::view('/documents/view-page', 'documents.view')->name('documents.view.page');

// Evaluation Reports (Accreditor)
Route::view('/reports/evaluation', 'reports.evaluation')->name('reports.evaluation');

// Upload Area Documents (Area Chair)
Route::view('/area/upload', 'area.upload')->name('area.upload');

// Remove draft-upload route and keep the next route
Route::get('/reports/accreditation', function () {
    return view('reports.accreditation');
})->name('reports.accreditation');

Route::middleware(['auth'])->group(function () {
    Route::get('/faculty-management', [UserManagementController::class, 'manageFaculty'])
        ->name('faculty.management');
    Route::post('/faculty-management', [UserManagementController::class, 'storeFaculty'])->name('faculty.store');
    Route::get('/faculty-management/{user}/edit', [UserManagementController::class, 'edit'])->name('faculty.edit');
    Route::put('/faculty-management/{user}', [UserManagementController::class, 'updateFaculty'])->name('faculty.update');
    Route::delete('/faculty-management/{user}', [UserManagementController::class, 'destroy'])->name('faculty.destroy');

    // Area Chair and Area Member document routes
    Route::get('/my-documents/approved', [DocumentController::class, 'myApprovedDocuments'])->name('my.documents.approved');
    Route::get('/my-documents/rejected', [DocumentController::class, 'myRejectedDocuments'])->name('my.documents.rejected');
});


Route::resource('users', UserManagementController::class);
// User management routes
Route::get('/user-management', [UserManagementController::class, 'index'])->name('user.management');
Route::post('/user-management', [UserManagementController::class, 'store'])->name('user.store');
Route::get('/user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user.edit');
Route::put('/user-management/{user}', [UserManagementController::class, 'update'])->name('user.update');
Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user.destroy');


// Faculty Management (for Area Chair)



Route::get('/documents/pending', [DocumentController::class, 'pending'])->name('documents.pending');
Route::post('/documents/approve/{id}', [DocumentController::class, 'approve'])->name('documents.approve');
Route::post('/documents/reject/{id}', [DocumentController::class, 'reject'])->name('documents.reject');
Route::get('/approved-documents', [DocumentController::class, 'showApprovedDocuments'])->name('documents.approved');
Route::get('/documents/approved', [DocumentController::class, 'showApprovedDocuments'])->name('documents.approved');
// routes/web.php

Route::post('/documents/{id}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
Route::post('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
Route::get('/documents/rejected', [DocumentController::class, 'showRejectedDocuments'])->name('documents.rejected');
Route::get('documents/rejected', [DocumentController::class, 'showRejectedDocuments'])->name('documents.rejected');

Route::middleware(['auth'])->group(function () {
    Route::post('/documents/{id}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
});



Route::get('/documents/folder/{folder}', [DocumentController::class, 'getByFolder']);
Route::get('/folders/{id}/documents', [FolderController::class, 'getDocuments']);
Route::get('/folders/{id}/children', [FolderController::class, 'children']);

Route::get('/documents/folder/{folderId}', [DocumentController::class, 'getDocumentsByFolder']);

Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');


Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');

Route::post('/folders/{id}/upload-area', [FolderController::class, 'uploadToArea'])->name('folders.uploadToArea');

Route::get('/folders/{id}', [FolderController::class, 'show'])->name('folders.show');
Route::post('/folders/{id}/upload', [FolderController::class, 'upload'])->name('folders.upload');

Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
Route::delete('/folders/area/{subtopic}/{area}', [FolderController::class, 'destroyByArea'])->name('folders.destroyByArea');



Route::post('/subtopics/{id}/generate-folders', [SubtopicController::class, 'generateFolders'])->name('subtopics.generateFolders');
Route::post('/subtopics/{id}/add-folder', [App\Http\Controllers\SubtopicController::class, 'addFolder'])->name('subtopics.addFolder');

Route::get('/dcc/dashboard', [DocumentController::class, 'dccDashboard'])->name('dcc.dashboard');

Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::get('/qa/pending', [DocumentController::class, 'pending'])->name('qa.pending');

// Route to show the edit form
Route::get('subtopics/{id}/edit', [SubtopicController::class, 'edit'])->name('subtopics.edit');

// Route to update the subtopic after editing
Route::put('subtopics/{id}', [SubtopicController::class, 'update'])->name('subtopics.update');



//sub topic
Route::post('/subtopics/store', [SubtopicController::class, 'store'])->name('subtopics.store');
Route::get('/subtopics/{id}', [SubtopicController::class, 'show'])->name('subtopics.show');
Route::get('/subtopics/{id}/edit', [SubtopicController::class, 'edit'])->name('subtopics.edit');
Route::put('/subtopics/{id}', [SubtopicController::class, 'update'])->name('subtopics.update');
Route::delete('/subtopics/{id}', [SubtopicController::class, 'destroy'])->name('subtopics.destroy');

//department
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('department.view');
Route::get('departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');

// Department edit/delete
Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

// 🌍 Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 📌 Dashboard Route - Dynamically Loads Role-Based Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// 📌 Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 📌 Authentication Routes
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 📌 Document Management Routes (Protected)
Route::middleware('auth')->group(function () {
    
    // 📁 **DCC - Document Upload & Management**
    Route::get('/dcc/upload', [DocumentController::class, 'dccUpload'])->name('dcc.upload');
    Route::post('/upload-document', [DocumentController::class, 'upload'])->name('upload.document');
    Route::get('/dcc/submissions', [DocumentController::class, 'dccSubmissions'])->name('dcc.submissions');


    // 🛑 **Fixing Duplicate Reject Routes & Adding Rejection Reason Support**
    Route::post('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('document.reject'); // ✅ Fixed


    Route::group(['middleware' => ['auth']], function () {
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
        Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    });
    // Removed duplicate: Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('document.reject');

    Route::get('/qa/approved', [DocumentController::class, 'showApprovedDocuments'])->name('qa.approved');
    Route::get('/qa/rejected', [DocumentController::class, 'showRejectedDocuments'])->name('qa.rejected');
    Route::get('/qa/reports', [DocumentController::class, 'showReports'])->name('qa.reports');

    // 📌 **Google Drive Upload - After Approval**
    Route::get('/upload-to-google/{id}', [DocumentController::class, 'generateGoogleDriveLink'])->name('upload.to.google');
    Route::post('/save-google-drive-link/{id}', [DocumentController::class, 'saveGoogleDriveLink'])->name('save.google.drive.link');

    // 🔒 **Lock Document (QA & Accreditors Only)**
    Route::post('/document/{id}/lock', [DocumentController::class, 'lockDocument'])->name('document.lock');
});

// 📌 Google Drive Integration Routes (For QA)
Route::get('/qa/google-drive', [GoogleDriveController::class, 'index'])->name('qa.google-drive');
Route::get('/qa/google-drive/search', [GoogleDriveController::class, 'search'])->name('qa.google-drive.search');

// Notification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

// Import Laravel Authentication Routes
require __DIR__.'/auth.php';

// Evaluation Reports Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/evaluation', [EvaluationController::class, 'index'])->name('reports.evaluation');
    Route::post('/reports/evaluation', [EvaluationController::class, 'store'])->name('reports.evaluation.store');
    Route::get('/reports/evaluation/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('reports.evaluation.edit');
    Route::put('/reports/evaluation/{evaluation}', [EvaluationController::class, 'update'])->name('reports.evaluation.update');
    Route::delete('/reports/evaluation/{evaluation}', [EvaluationController::class, 'destroy'])->name('reports.evaluation.destroy');
});

Route::post('/accreditation-folders/store', [AccreditationFolderController::class, 'store'])->name('accreditation-folders.store');

// Accreditation Folder edit/delete
Route::get('/accreditation-folders/{id}/edit', [AccreditationFolderController::class, 'edit'])->name('accreditation-folders.edit');
Route::put('/accreditation-folders/{id}', [AccreditationFolderController::class, 'update'])->name('accreditation-folders.update');
Route::delete('/accreditation-folders/{id}', [AccreditationFolderController::class, 'destroy'])->name('accreditation-folders.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/maintenance', function () {
        return view('maintenance-test');
    })->name('maintenance.test');

    // Folder structure maintenance routes
    Route::get('/maintenance/folder-structures', [\App\Http\Controllers\FolderStructureController::class, 'index'])->name('folder-structures.index');
    Route::get('/maintenance/folder-structures/{id}/edit', [\App\Http\Controllers\FolderStructureController::class, 'edit'])->name('folder-structures.edit');
    Route::post('/maintenance/folder-structures/{id}', [\App\Http\Controllers\FolderStructureController::class, 'update'])->name('folder-structures.update');
});
