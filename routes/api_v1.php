<?php

use App\Http\Controllers\Api\V1\AccountVerificationController;
use App\Http\Controllers\Api\V1\ChatSearchController;
use App\Http\Controllers\Api\V1\StudentDepartmentController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\TimeRecordController;
use App\Http\Controllers\Api\V1\TaskBoardController;
use App\Http\Controllers\Api\V1\UserPoolController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('routes', function() {
    $routeCollection = Route::getRoutes();
    return response()->json($routeCollection->getRoutes());
});



// Login & Logout
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LogoutController::class, 'logout']);
// Register
Route::post('register/admins', [RegisterController::class, 'adminRegister']);
Route::post('register/students', [RegisterController::class, 'studentRegister']);
Route::post('register/coordinators', [RegisterController::class, 'coordinatorRegister']);
Route::post('register/supervisors', [RegisterController::class, 'supervisorRegister']);



// for all authenticated roles inside Auth using Auth:check();
Route::group(['middleware' => ['auth:api','isVerified']], function() { 
    Route::get('/departments/{department}/students', [StudentDepartmentController::class, 'getStudentDepartment']);
    Route::apiResources([
        'announcements' => AnnouncementController::class,
        'companies' => CompanyProfileController::class,
        'profiles/coordinators' => CoordinatorProfileController::class,
        'profiles/students' => StudentProfileController::class,
        'profiles/supervisors' => SupervisorProfileController::class,
        'profiles/admins' => AdminProfileController::class,
        'interns' => InternController::class,
    ]);
    Route::apiResource('jobs', JobController::class);

    // messages
    Route::get('messages',[MessageController::class,'index']);
    Route::get('messages/{messages}',[MessageController::class,'show']);
    Route::post('messages',[MessageController::class,'store']);

    // User pools for current active and inactive users
    Route::get('userpools',[UserPoolController::class,'index']);
    Route::post('userpools/connect',[UserPoolController::class,'connect']);
    

    // Time Record or the DTR Daily Time Record
    Route::get('dailytime/records', [TimeRecordController::class, 'index']);
    Route::get('dailytime/records/{id}', [TimeRecordController::class, 'show']);
    Route::put('dailytime/records/{id}', [TimeRecordController::class, 'update']);
    Route::delete('dailytime/records/{id}', [TimeRecordController::class, 'destroy']);
    Route::post('dailytime/records/timein', [TimeRecordController::class, 'storeByStudent']);
    Route::put('dailytime/records/timeout/{id}', [TimeRecordController::class, 'updateByStudent']);
    Route::post('dailytime/records/supervisorcreate', [TimeRecordController::class, 'storeBySupervisor']);

    // Trainee Schedules
    Route::apiResource('trainings/schedules',TraineeScheduleController::class)->only(['index','store','destroy']);

    // Trainee / Supervisor Boards
    Route::apiResource('projects/boards', BoardController::class)->only(['index','store','update','destroy']);
    Route::apiResource('projects/columns', BoardColumnController::class)->only(['store','destroy']);
    Route::apiResource('projects/cards', ColumnCardController::class)->only(['store','update','destroy']);

    // Trainee task list
    Route::get('dailywork/tasks', [TaskBoardController::class,'index']);

    // Email verified to now 
    Route::get('email/verify/{id}',[AccountVerificationController::class,'emailVerification']);
    
    // Activate account  
    Route::get('activate/account/{id}',[AccountVerificationController::class,'activate']);

    // All users for available for chat
    Route::get('search/all/chatusers',[ChatSearchController::class, 'index']);
});

// Disconnect to user pool
Route::delete('userpools/disconnect/{socketId}',[UserPoolController::class,'disconnect']);

// Account verification
Route::get('requests/verifications/{id}',[AccountVerificationController::class,'sendRequest']);

// Password reset
Route::get('requests/passwords/resets',[PasswordResetController::class, 'sendRequest']);

// Courses
Route::get('courses', [CourseController::class, 'getCourses']);

// Courses
Route::get('companies', [CompanyController::class, 'getCompanies']);

// Fallback route 
Route::fallback(function (Request $request) {
    return response()->json(['status'=>'failed','code'=>404,'error'=>'404 resource not found'],404);
});