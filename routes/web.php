<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GoogleDriveController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubtopicController;

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
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('department.view');
Route::get('departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');

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

    // 📌 **QA - Pending, Approved, Rejected Documents & Reports**
    Route::get('/qa/pending', [DocumentController::class, 'showPending'])->name('qa.pending');
    Route::post('/document/{id}/approve', [DocumentController::class, 'approve'])->name('document.approve');

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

// Import Laravel Authentication Routes
require __DIR__.'/auth.php';
