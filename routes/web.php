<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ExamFieldController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\LearningFieldController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamSetController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizSetController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\TipsController;
use App\Http\Controllers\TrafficSignController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleExpenseController;
use App\Http\Controllers\ViolationController;
use App\Http\Requests\StoreCalendarRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//not login
//login&register
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//forgot password
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

//login
//dashboard
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    //users
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::get('/users-available', [UserController::class, 'getAvailableUsers']); //fetch user for add calendar
    Route::get('/sale', [UserController::class, 'indexForSale'])->name('sale.index');
    Route::get('/sale/create', [UserController::class, 'createSale'])->name('sale.create');
    Route::get('/sale/show/{user}', [UserController::class, 'showSale'])->name('sale.show');
    Route::get('/sale/edit/{user}', [UserController::class, 'editSale'])->name('sale.edit');
    Route::get('/instructor', [UserController::class, 'indexForInstructor'])->name('instructor.index');
    Route::get('/instructor/create', [UserController::class, 'createInstructor'])->name('instructor.create');
    Route::get('/instructor/show/{user}', [UserController::class, 'showInstructor'])->name('instructor.show');
    Route::get('/instructor/edit/{user}', [UserController::class, 'editInstructor'])->name('instructor.edit');
    Route::resource('users', UserController::class);
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    //student and lead
    Route::post('/leads/add-course', [LeadController::class, 'addCourse'])->name('leads.addCourse');
    Route::get('/students/{student}/available-courses', [StudentController::class, 'availableCourses']); //fetch course available for choice in list student
    Route::resource('leads', LeadController::class);
    Route::post('/students/{id}/update-card', [StudentController::class, 'updateCard'])->name('students.updateCard');
    Route::get('/students/moto', [StudentController::class, 'indexMoto'])->name('students.index-moto');
    Route::get('/students/car', [StudentController::class, 'indexCar'])->name('students.index-car');
    Route::resource('students', StudentController::class);

    //leadSource
    Route::resource('leadSource', LeadSourceController::class);

    //curriculum
    Route::resource('curriculums', CurriculumController::class);

    //courses
    Route::get('/courses-alls', [CourseController::class, 'courseAlls']);
    Route::post('/courses/add-student', [CourseController::class, 'addStudent'])->name('courses.addStudent');
    Route::post('/courses/add-students', [CourseController::class, 'addStudents'])->name('courses.addStudents');
    Route::delete('/courses/{course}/students/{student}', [CourseController::class, 'removeStudent'])->name('courses.removeStudent');
    Route::post('/courses/{course}', [CourseController::class, 'addStudent']);
    Route::resource('courses', CourseController::class);

    //lesson
    Route::resource('lessons', controller: LessonController::class);

    //exam fields
    Route::resource('exam_fields', ExamFieldController::class);

    //fee
    Route::resource('fees', FeeController::class);

    // Calendars
    Route::prefix('calendars')->group(function () {
        Route::get('study-lt', [CalendarController::class, 'index2'])->name('calendars.study-lt')->defaults('type', 'study')->defaults('level_filter', 3);
        Route::get('study-th', [CalendarController::class, 'index2'])->name('calendars.study-th')->defaults('type', 'study')->defaults('level_filter', 4);
        Route::get('exam-tn', [CalendarController::class, 'index2'])->name('calendars.exam-tn')->defaults('type', 'exam')->defaults('level_filter', 1);
        Route::get('exam-sh', [CalendarController::class, 'index2'])->name('calendars.exam-sh')->defaults('type', 'exam')->defaults('level_filter', 2);
        Route::get('exam-hmlt', [CalendarController::class, 'index2'])->name('calendars.exam-hmlt')->defaults('type', 'exam')->defaults('level_filter', 6);
        Route::get('exam-hmth', [CalendarController::class, 'index2'])->name('calendars.exam-hmth')->defaults('type', 'exam')->defaults('level_filter', 5);
        Route::get('work', [CalendarController::class, 'index2'])->name('calendars.work')->defaults('type', 'work');
        Route::get('meeting', [CalendarController::class, 'index2'])->name('calendars.meeting')->defaults('type', 'meeting');
        Route::get('call', [CalendarController::class, 'index2'])->name('calendars.call')->defaults('type', 'call');
    });

    Route::get('/calendars/infor', [CalendarController::class, 'getInforByVehicleType']);
    Route::post('/calendars/{calendar}/students', [CalendarController::class, 'addStudent'])->name('calendars.students.store');
    Route::delete('/calendars/{calendar}/students/{student}', [CalendarController::class, 'removeStudent'])->name('calendars.students.destroy');
    Route::get('/calendars/{calendar}/students/available', [CalendarController::class, 'getAvailableStudents']);
    Route::patch('calendars/{calendar}/update-status', [CalendarController::class, 'updateStatus'])->name('calendar.updateStatus');
    Route::get('/calendars/{calendar}/info', [CalendarController::class, 'getStudents']);
    Route::post('/calendars/{calendar}/results', [CalendarController::class, 'storeResults']);
    Route::get('/course-data/{type}/{course}', [CalendarController::class, 'inforCourse']);
    Route::get('/calendars/exam-sh-detail', [CalendarController::class, 'index2'])->name('calendars.exam-sh-detail')->defaults('type', 'exam')->defaults('level_filter', 2);
    Route::get('/calendars/exam-tn-detail', [CalendarController::class, 'index2'])->name('calendars.exam-tn-detail')->defaults('type', 'exam')->defaults('level_filter', 1);
    Route::get('/calendars/exam-hmlt-detail', [CalendarController::class, 'index2'])->name('calendars.exam-hmlt-detail')->defaults('type', 'exam')->defaults('level_filter', 6);
    Route::get('/calendars/exam-hmth-detail', [CalendarController::class, 'index2'])->name('calendars.exam-hmth-detail')->defaults('type', 'exam')->defaults('level_filter', 5);
    Route::get('/calendars/study-th-detail', [CalendarController::class, 'index2'])->name('calendars.study-th-detail')->defaults('type', 'study')->defaults('level_filter', 4);
    Route::get('/calendars/study-lt-detail', [CalendarController::class, 'index2'])->name('calendars.study-lt-detail')->defaults('type', 'study')->defaults('level_filter', 3);
    Route::resource('calendars', CalendarController::class);

    //learning_fields
    Route::resource('learning_fields', LearningFieldController::class);

    //exam_sets
    Route::get('/exam_sets/editor_data', [ExamSetController::class, 'editor_data']);

    Route::post('/exam_sets/{exam_set}/add_question', [ExamSetController::class, 'addQuestion'])->name('exam_sets.add_question');
    Route::delete('/exam_sets/{exam_set}/remove_question/{quiz}', [ExamSetController::class, 'removeQuestion'])->name('exam_sets.remove_question');
    Route::resource('exam_sets', ExamSetController::class);
    


    //ranking
    Route::resource('rankings', RankingController::class);

    //vehicles
    Route::get('/vehicles-available', [VehicleController::class, 'getAvailableVehicles']); // for add std: it add option on form select vehicles
    Route::resource('vehicles', VehicleController::class);

    //vehicle expenses
    Route::resource('vehicle-expenses', VehicleExpenseController::class);

    //traffic-signs
    Route::resource('traffic-signs', TrafficSignController::class);

    //stadiums
    Route::resource('stadiums', StadiumController::class);

    //exam-schedules
    Route::patch('/calendar/{id}/approve', [CalendarController::class, 'approveSchedule'])->name('calendar.approve');
    Route::get('/exam-schedules-available', [ExamScheduleController::class, 'getAvailableExamSchedules']); //Use it for fetch select option when add new exam calendar
    Route::resource('exam-schedules', ExamScheduleController::class);

    //student&course
    Route::get('/student/{student}/course/{course}/action', [StudentController::class, 'showAll'])->name('student.course.action');
    Route::get('/student/{student}/course/{course}/edit', [StudentController::class, 'editCouseInShowAll'])->name('student.course.edit');
    Route::get('/students/{id}/courses', [StudentController::class, 'getCourses']);
    Route::get('/students/{student}/exam-details/{course}/{examField}', [StudentController::class, 'examDetails']);
    Route::get('/students/{student}/study-details/{course}', [StudentController::class, 'studyDetails']);
    Route::get('/get-available-students', [CourseController::class, 'getAvailableStudents']);

    Route::post('/pages/ajax-create', [TipsController::class, 'ajaxCreate'])->name('pages.ajaxCreate');
    Route::resource('tips', TipsController::class)->except(['show']);

    Route::resource('violations', ViolationController::class);

    Route::get('/tips/content', [TipsController::class, 'updateContent'])->name('tips.content');

    Route::put('/quizzes/update-wrong', [QuizController::class, 'updateWrong'])->name('quizzes.update-wrong');
    Route::resource('quiz-sets', QuizSetController::class)->only(['store', 'update']);
    Route::resource('quizzes', QuizController::class);

    Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolePermissionController::class, 'create'])->name('roles.create1');
    Route::post('/roles', [RolePermissionController::class, 'store'])->name('roles.store1');
    Route::get('/roles/edit/{id}', [RolePermissionController::class, 'edit'])->name('roles.edit1');
    Route::post('/roles/{id}', [RolePermissionController::class, 'update'])->name('roles.update1');
    Route::post('/roles/{id}', [RolePermissionController::class, 'destroy'])->name('roles.destroy1');
});
    
Route::get('/listCourse', [CourseController::class, 'listCourse']);
//fake bộ đề
Route::get('/admin/fake-exam-sets', [\App\Http\Controllers\ExamSetController::class, 'fakeExamSets'])
    ->name('admin.exam_sets.fake');
