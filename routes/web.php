<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityRoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FacultySimulationController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FloorPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportingAnalyticsController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SectionInvitationController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSimulationController;
use App\Http\Controllers\UserController;
use App\Livewire\Discount\DiscountCreate;
use App\Livewire\Discount\DiscountEdit;
use App\Livewire\Discount\DiscountIndex;
use App\Livewire\FloorPlan\FloorPlanCreate;
use App\Livewire\FloorPlan\ViewFloorPlan;
use App\Livewire\Inventory\Create;
use App\Livewire\Inventory\InventoryEdit;
use App\Livewire\Inventory\InventoryList;
use App\Livewire\Inventory\InventoryRestock;
use App\Livewire\Menu\MenuCreate;
use App\Livewire\Menu\MenuEdit;
use App\Livewire\Menu\MenuList;
use App\Livewire\Order\Create as OrderCreate;
use App\Livewire\Kitchen\KitchenDashboard;
use App\Livewire\Order\Checkout;
use App\Livewire\Order\CreateCombined;
use App\Livewire\Order\Payment;
use App\View\Components\Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulationSubmissionController;
use App\Livewire\ReportingAnalytics\ReportingAnalytics;

// Public routes
Route::get('/', function () {

    // Check if user is authenticated and redirect based on role
    if (Auth::check()) {
        if (Auth::user()->role === 'faculty') {
            return redirect()->route('faculty.dashboard');
        } elseif (Auth::user()->role === 'student') {
            return redirect()->route('student.section');
        }
    }

    // If not authenticated, show landing page
    return view('index');
})->name('landing');


Route::get('/auth', function () {
    return view('auth.auth');
})->name('auth.auth');

Route::get('auth/google', [GoogleController::class, "redirecttogoogle"])
    ->name('redirect.google');

Route::get('auth/google/callback', [GoogleController::class, "handleGoogleCallback"]);

Route::get('/register', function() {
    return view('auth.auth', ['autoShowRegister' => true]);
})->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/invite/accept/{token}', [SectionInvitationController::class, 'acceptInvite'])
    ->name('invite.accept');
    
Route::post('/send-otp',[AuthController::class,'sendOTP']);

Route::post('/verify-otp',[AuthController::class,'verifyOTP']);

// Protected routes (authentication required)
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/calendar', function (\Illuminate\Http\Request $request) {
        $month = $request->get('month', date('m'));
        $year  = $request->get('year', date('Y'));
        return (new Calendar($month, $year))->render();
    })->name('calendar.ajax');

    Route::post('/simulation/start', [SimulationController::class, 'startSession'])
        ->name('simulation.start');

    Route::post('/simulation/log-action', [SimulationController::class, 'logAction'])
        ->name('simulation.log');

    Route::post('/simulation/submit/{sessionId}', [SimulationController::class, 'submitSession'])
        ->name('simulation.submit');

    Route::get('/simulation/session/{sessionId}', [SimulationController::class, 'getSession'])
        ->name('simulation.session');

    Route::get('/profile', function () {
        return view('partials.includes.profile');
    })->name('profile');

    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])
        ->name('profile.change-password');

    Route::get('/profile/download-data', [ProfileController::class, 'downloadData'])
        ->name('profile.download-data');

    Route::post('/profile/delete-account', [ProfileController::class, 'deleteAccount'])
        ->name('profile.delete-account');

    Route::middleware(['lmsrole:superadmin'])->prefix('superadmin')->group(function(){

        Route::get('/dashboard', [UserController::class, 'index'])
            ->name('superadmin.dashboard');

        Route::get('/pre-test', [UserController::class, 'preTest'])
            ->name('superadmin.pretest');

        Route::get('/pretest/filter', [UserController::class, 'filter'])
            ->name('pretest.filter');
    });

    // Admin routes (role:admin)
    Route::middleware(['lmsrole:admin'])->prefix('admin')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/faculty', [AdminController::class, 'faculty'])
            ->name('admin.faculty');

        Route::post('/faculty', [AdminController::class, 'facultyCreateAccount'])
            ->name('admin.faculty.create');

        Route::put('/faculty', [AdminController::class, 'facultyUpdateDetail'])
            ->name('admin.faculty.update');

        Route::delete('/faculty', [AdminController::class, 'facultyDeleteDetail'])
            ->name('admin.faculty.delete');

        Route::get('/students', [AdminController::class, 'students'])
            ->name('admin.students');

        Route::get('/sections', [AdminController::class, 'sections'])
            ->name('admin.sections');
    });

    // Faculty routes (role: faculty)
    Route::middleware(['lmsrole:faculty'])->prefix('faculty')->group(function () {

        Route::get('/dashboard', [FacultyController::class, 'facultyDashboard'])
            ->name('faculty.dashboard');

        Route::get('/section', [SectionController::class, 'index'])
            ->name('faculty.section');

        Route::post('/section', [SectionController::class, 'store'])
            ->name('faculty.section.store');

        Route::put('/section/{section_id}', [SectionController::class, 'update']);

        Route::delete('/section/{section_id}', [SectionController::class, 'destroy']);

        Route::put('/section/{section_id}/archive', [SectionController::class, 'archive'])
            ->name('faculty.section.archive');

        Route::get('/section/{section_id}/copy-invite-link', [SectionController::class, 'copyInviteLink'])
            ->name('faculty.section.copy-link');

        Route::post('/section/{section_id}/regenerate-code', [SectionController::class, 'regenerateCode'])
            ->name('faculty.section.regenerate-code');

        Route::get('/activity/{section_id}', [ActivityController::class, 'show'])
            ->name('faculty.activity');

        Route::get('/section/{section_id}/activities', [SectionController::class, 'getActivities'])
            ->name('section.activities');

        Route::get('/activity/{activity_id}/submissions', [ActivityController::class, 'getActivitySubmissions'])
            ->name('faculty.activity.subbbmissions');

        Route::get('/activity/activity_details/{section_id}/{activity_id}', [ActivityController::class, 'activityShow'])
            ->name('faculty/activity/activity_details');

        Route::put('/activity/{section_id}/{activity_id}', [ActivityController::class, 'update'])
            ->name('faculty.activity.update');

        Route::delete('/activity/{section_id}/{activity_id}', [ActivityController::class, 'destroy'])
            ->name('faculty.activity.destroy');

        Route::get('/student/{sectionId}', [FacultyController::class, 'showStudents'])
            ->name('faculty.student');
            
        Route::get('archived/student/{sectionId}', [FacultyController::class, 'showStudentsArchived'])
            ->name('faculty.Archivedstudent');

        Route::get('/allGrades', [FacultyController::class, 'studentAllGrades'])
            ->name('faculty.allGrades');

        Route::post('/activities', [ActivityController::class, 'store'])
            ->name('activities.store');

        Route::post('/activity/assign-role', [ActivityRoleController::class, 'assignActivityRole'])
            ->name('faculty.activity.assign-role');

        Route::get('/section/{id}/students', [SectionController::class, 'getStudents']);

        Route::get('/announcements/{section_id}', [AnnouncementController::class, 'index'])
            ->name('faculty.announcements');

        Route::post('/announcements', [AnnouncementController::class, 'store'])
            ->name('announce.store');

        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])
            ->name('announce.update');

        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
            ->name('announce.destroy');

        Route::get('/students', [FacultyController::class, 'showAllStudents'])
            ->name('faculty.students');

        Route::get('/archivedsection', [ArchiveController::class, 'archivedSection'])
            ->name('faculty.archivedSections');

        Route::get('/archived/Activity/{section_id}', [ArchiveController::class, 'archivedActivities'])
            ->name('faculty.archivedActivity');

        Route::get('/archived/activity/activity_details/{section_id}/{activity_id}', [ArchiveController::class, 'activityShow'])
            ->name('faculty/archived/activity/activity_details');

        Route::get('/archived-section/{section_id}/activities', [ArchiveController::class, 'getArchivedActivities']);

        Route::get('/archived-section/{section_id}/students', [ArchiveController::class, 'getArchivedStudents']);

        Route::get('/archived-activity/{activity_id}/submissions', [ArchiveController::class, 'getArchivedSubmissions']);

        Route::get('/archived/student/{sectionId}/{activityId}', [ArchiveController::class, 'showStudents'])
            ->name('faculty.archivedStudentF');

        Route::get('/archived/announcements/{section_id}', [ArchiveController::class, 'archivedAnnouncements'])
            ->name('faculty.archivedAnnouncements');

        Route::post('/section/{section_id}/invite', [SectionInvitationController::class, 'sendInvite'])
            ->name('faculty.section.invite');

       Route::get('/simulation/submissions/{activityId}', [SimulationSubmissionController::class, 'facultyIndex'])
            ->name('faculty.simulation.submissions');

        Route::get('/simulation/review/{submissionId}', [SimulationSubmissionController::class, 'review'])
            ->name('faculty.simulation.review');
        
        Route::get('/archived/simulation/review/{submissionId}', [SimulationSubmissionController::class, 'review'])
            ->name('faculty.simulation.archived');
        
        Route::post('/simulation/grade/{submissionId}', [SimulationSubmissionController::class, 'grade'])
            ->name('faculty.simulation.grade');
        
        Route::get('/simulation/all-submissions/section/{sectionId}', [SimulationSubmissionController::class, 'facultyIndex'])
            ->name('faculty.simulation.all');
            
        Route::get('/archived/simulation/all-submissions/section/{sectionId}', [SimulationSubmissionController::class, 'facultyIndexArchive'])
            ->name('faculty.simulation.allarchived');

        Route::get('/archived/simulation/submissions/{activityId}', [ArchiveController::class, 'getSubmissions'])
        ->name('faculty.simulation.archivedSubmissions');

        Route::get('/archived/simulation/review/{sessionId}', [ArchiveController::class, 'reviewSubmission'])
            ->name('faculty.simulation.archivedReview');

        Route::get('/archived/simulation/all-submissions/{activityId}', [ArchiveController::class, 'allSubmissions'])
            ->name('faculty.simulation.archivedAll');
        
        Route::post('/simulation/rebuild/{submissionId}', [SimulationSubmissionController::class, 'rebuildSubmission'])
            ->name('faculty.simulation.rebuild');
    });

    // Student routes (role: student)
    Route::middleware(['lmsrole:student'])->prefix('student')->group(function () {

        Route::get('/section', [StudentController::class, 'index'])
            ->name('student.section');

        Route::get('/activity/{section_id}', [StudentController::class, 'show'])
            ->name('student.activity');

        Route::get('/activity/activity_details/{section_id}/{activity_id}', [StudentController::class, 'activityShow'])
            ->name('student/activity/activity_details');

        Route::get('/student/{section_id}', [StudentController::class, 'studentSection'])
            ->name('student.student');

        // Route::get('/grades/{sectionId}/{activityId}', [UserController::class, 'studentsInActivityGrades'])
        //     ->name('faculty.grades.activity');

        Route::get('/simulation/cashier/{activityId}', [StudentSimulationController::class, 'showCashierSimulation'])
            ->name('student.simulation.cashier');

        Route::get('/simulation/kitchen/{activityId}', [StudentSimulationController::class, 'showKitchenSimulation'])
            ->name('student.simulation.kitchen');

        Route::get('/simulation/manager/{activityId}', [StudentSimulationController::class, 'showManagerSimulation'])
            ->name('student.simulation.manager');

        Route::get('/simulation/waiter/{activityId}', [StudentSimulationController::class, 'showWaiterSimulation'])
            ->name('student.simulation.waiter');
            
        Route::get('/simulation/host/{activityId}', [StudentSimulationController::class, 'showHostSimulation'])
            ->name('student.simulation.host');

        Route::post('/simulation/submit-all', [SimulationSubmissionController::class, 'submitAllOpenSessions'])
            ->name('student.simulations.submit-all');
        
        Route::get('/my-submissions', [StudentSimulationController::class, 'mySubmissions'])
            ->name('student.my-submissions');

        Route::get('/grades', [StudentController::class, 'studentGrades'])
            ->name('student.grades');

        Route::get('/archivedsection', [ArchiveController::class, 'archivedStudentSection'])
            ->name('student.archivedSections');

        Route::get('/archived/Activity/{section_id}', [ArchiveController::class, 'archivedStudentActivities'])
            ->name('student.archivedActivity');

        Route::get('archived/student/{section_id}', [StudentController::class, 'studentSection'])
            ->name('student.archivedStudentS');

        Route::get('/archived/activity/activity_details/{section_id}/{activity_id}', [ArchiveController::class, 'activityArchivedShow'])
            ->name('student/archived/activity/activity_details');

        Route::post('/submit-quiz', [StudentController::class, 'submitQuiz'])
            ->name('student.submit-quiz');

        Route::get('/quiz-results', [StudentController::class, 'viewQuizResults'])
            ->name('student.quiz-results');

        Route::get('/join/{code}', [SectionController::class, 'showJoinPage'])
            ->name('section.join');

        Route::post('/join/{code}', [SectionController::class, 'joinSection'])
            ->name('section.join.submit');
        
        Route::post('/simulation/submit-all', [SimulationSubmissionController::class, 'submitAllOpenSessions'])
            ->name('student.simulations.submit-all');

    });
        // Route::get('/join/{code}', [SectionController::class, 'showJoinPage'])
        //     ->name('section.join');

        // Route::post('/join/{code}', [SectionController::class, 'joinSection'])
        //     ->name('section.join.submit');
});



Route::get('/forgot-password', [PasswordResetController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'update'])
    ->name('password.update');


// for debugging and such

Route::get('/test', function () {
    return view('test');
});


Route::get('/landingpage', function () {
    return view('landingpage');
});

Route::get('/test-403', function () {
    return response()->view('errors.403', [
        'exception' => (object)['message' => 'Test error message']
    ], 403);
});


Route::post('/simulation/check-submission', [SimulationController::class, 'checkSubmission'])->name('simulation.check');

Route::post('/faculty/invite/{id}/resend', [SectionInvitationController::class, 'resendInvite'])
    ->name('faculty.section.invite.resend');

Route::delete('/faculty/invite/{id}/revoke', [SectionInvitationController::class, 'revokeInvite'])
    ->name('faculty.section.invite.revoke');

// Add this temporary debug route at the bottom of your web.php file
Route::get('/test-simulation', function() {
    return response()->json([
        'message' => 'Simulation routes are accessible',
        'user' => Auth::check() ? Auth::user()->name : 'Not logged in',
        'user_id' => Auth::id(),
        'csrf_token' => csrf_token(),
        'routes_exist' => [
            'start' => route('simulation.start'),
            'log' => route('simulation.log'),
        ]
    ]);
})->middleware('auth');



// simulations

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

Route::prefix('inventory')->middleware(['auth'])->group(function(){

        Route::get('create', Create::class)
            ->name('inventory.create')
            ->middleware('permission:create inventory');

        Route::get('/', InventoryList::class)
            ->name('inventory.index')
            ->middleware('permission:view inventory|edit inventory|create inventory|delete inventory|restock inventory');

        Route::get('/{inventory}/edit', InventoryEdit::class)
            ->name('inventory.edit')
            ->middleware('permission:edit inventory');

        Route::get('/restock', InventoryRestock::class)
            ->name('inventory.restock')
            ->middleware('permission:restock inventory');
});

Route::prefix('menu')->middleware(['auth'])->group(function() {

        Route::get('create', MenuCreate::class)
            ->name('menu.create')
            ->middleware('permission:create menu');

        Route::get('/', MenuList::class)
            ->name('menu.index')
            ->middleware('permission:create menu|edit menu|view menu|delete menu');

        Route::get('/{menu}/edit', MenuEdit::class)
            ->name('menu.edit')
            ->middleware('permission:edit menu');
    });

Route::prefix('floorplan')->middleware(['auth'])->group(function(){

        Route::get('/', ViewFloorPlan::class)
            ->name('floorplan.index')
            ->middleware('permission:view floorplan|create floorplan');

        Route::get('/create', FloorPlanCreate::class)
            ->name('floorplan.create')
            ->middleware('permission:create floorplan');
    });

Route::prefix('order')->middleware(['auth', 'web'])->group(function(){

        Route::get('/{table}/create', OrderCreate::class)
            ->name('order.create')
            ->middleware('permission:take orders');

        Route::get('/combined/{combinedTable}/create', CreateCombined::class)
            ->name('order.combined.create')
            ->middleware('permission:manage table');

        Route::get('/{order}/checkout', Checkout::class)
            ->name('order.checkout')
            ->middleware('permission:apply discount');

        Route::get('/{order}/payment', Payment::class)
            ->name('order.payment')
            ->middleware('permission:process payment');
    });

Route::get('kitchen', KitchenDashboard::class)->middleware(['auth', 'web'])->name('kitchen.dashboard');

Route::prefix('discount')->middleware(['auth', 'web'])->group(function(){
    Route::get('/', DiscountIndex::class)
        ->name('discount.index')
        ->middleware('permission:create discount|edit discount|view discount|delete discount');

    Route::get('/create', DiscountCreate::class)
        ->name('discount.create')
        ->middleware('permission:create discount');

    Route::get('/{discount}', DiscountEdit::class)
        ->name('discount.edit')
        ->middleware('permission:edit discount');
});
Route::prefix('reportsAnalytics')->middleware(['auth', 'permission:view reports and analytics'])->group(function(){

        Route::get('/', [ReportingAnalyticsController::class, 'index'])
            ->name('reportsAnalytics.index');

        Route::get('//analytics/data', [ReportingAnalyticsController::class, 'data'])
            ->name('reportingAnalytics.data');

        Route::get('/reports/inventory', [ReportingAnalyticsController::class, 'inventory'])
            ->name('reports.inventory');

        Route::get('/reports/inventory/data', [ReportingAnalyticsController::class, 'inventoryData'])
            ->name('reports.inventory.data');

        Route::get('/reports/items', [ReportingAnalyticsController::class, 'itemsReport'])
            ->name('reports.items');

        Route::get('/reports/payments-reports', [ReportingAnalyticsController::class, 'paymentsReports'])
            ->name('reports.payments-reports');

        Route::get('/inventory', [ReportingAnalyticsController::class, 'MostUsedInventoryPerCategory'])
            ->name('reports.inventoryUsage');


        // Route::get('/{order}/checkout', Checkout::class)
        //     ->name('order.checkout');

        // Route::get('/{order}/payment', Payment::class)
        //     ->name('order.payment');
    });
    
Route::get('/analytics/export/inventory', [ReportingAnalyticsController::class, 'exportInventory'])->name('analytics.export.inventory');
Route::get('/analytics/export/sales', [ReportingAnalyticsController::class, 'exportSales'])->name('analytics.export.sales');
Route::get('/reports/analytics', \App\Livewire\ReportingAnalytics\ReportingAnalytics::class)
    ->middleware(['auth'])
    ->name('reportsAnalytics.index');
