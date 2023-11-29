<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SuperAdmin\LevelCategoryController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LessonController;
use App\Mail\ReduceLevelMail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/logo', [SettingController::class, "GetLogoFile"])->name('logo');
Route::get('/side_image', [SettingController::class, "GetSideImageFile"])->name('side_image');


Route::prefix('guest')->group(function () {

    Route::get('/login', [UserController::class, "Login"])->name('login');
    Route::post('/login', [UserController::class, "DoLogin"])->name('do_login');

    Route::get('/register', [UserController::class, "register"])->name('register');
    Route::post('/register', [UserController::class, "DoRegister"])->name('do_register');

    Route::get('/get_level_logo_image/{key}', [LevelController::class, "GetLevelLogo"])->name('get_level_logo');

});

Route::middleware(['auth'])->group(function () {

    Route::prefix("user")->group(function () {
        Route::get("logout", [UserController::class, "Logout"])->name("logout");
    });

    Route::get('/dashboard', function () {
        return view('panel.dashboard.index');
    })->name('panel.dashboard');


    Route::prefix("category")->group(function () {
        Route::get("get_category_of_line/{line_id}", [CategoryController::class, "GetAllCategoriesByLineId"])->name("categories_by_line_id");
    });

    Route::middleware(['auth.super_admin'])->group(function () {

        Route::prefix('setting')->group(function () {
            Route::get('save', [SettingController::class, "Index"])->name('super_admin.setting');
            Route::post('save', [SettingController::class, "SaveSetting"])->name('super_admin.setting.save');
        });


        Route::prefix('master')->group(function () {
            Route::get('list', [MasterController::class, "MasterList"])->name('super_admin.master.list');
            Route::get('update_details/{user_level_category_id}', [MasterController::class, "MasterUpdateDetails"])->name('super_admin.master.update_details');
            Route::get('delete_file/{id}', [MasterController::class, "DeleteMasterFile"])->name('super_admin.master.files.delete');
            Route::post('save_file', [MasterController::class, "SaveMasterFile"])->name('super_admin.master.files.save');
        });

        Route::prefix('user_management')->group(function () {
            Route::get('add_new_user', [UserManagementController::class, "AddNewUser"])->name('super_admin.add_new_user');
            Route::post('add_new_user', [UserManagementController::class, "AddNewUserLevelCategoryAction"])->name('super_admin.add_new_user_level_category');
        });

        Route::prefix('level_category')->group(function () {
            Route::get('index', [LevelCategoryController::class, "GetAllLevelCategories"])->name('super_admin.level_category.index');
            Route::get('change_logo/{level_category_id}', [LevelCategoryController::class, "ChangeLogo"])->name('super_admin.level_category.logo.change');
            Route::post('change_logo/save', [LevelCategoryController::class, "SaveLogo"])->name('super_admin.level_category.logo.save');
            Route::get('thumb_logo/{level_category_id}', [LevelCategoryController::class, "GetLevelCategoryLogoAsThumbnail"])->name('super_admin.level_category.logo.thumb');
        });

        Route::prefix('lesson')->group(function () {
            Route::get('index/{level_category_id}', [\App\Http\Controllers\SuperAdmin\LessonController::class, "GetLessonsOfLevelCategoryId"])->name('super_admin.lesson.index');
            Route::get('details/{lesson_id}', [\App\Http\Controllers\SuperAdmin\LessonController::class, "GetLessonsDetails"])->name('super_admin.lesson.details');
            Route::get('files/{lesson_id}', [\App\Http\Controllers\SuperAdmin\LessonController::class, "LessonFile"])->name('super_admin.lesson.files');
            Route::get('delete_file/{id}', [\App\Http\Controllers\SuperAdmin\LessonController::class, "DeleteLessonFile"])->name('super_admin.lesson.files.delete');
            Route::post('save_file', [\App\Http\Controllers\SuperAdmin\LessonController::class, "SaveLessonFile"])->name('super_admin.lesson.files.save');
            Route::post('details/save', [\App\Http\Controllers\SuperAdmin\LessonController::class, "SaveLesson"])->name('super_admin.lesson.details.save');
        });

        Route::get('user_info/{email}', [UserManagementController::class, "GetUserInfo"])->name('user_info');

        Route::get('get_master_of_category/{categoryId}', [UserManagementController::class, "GetMasterOfCategory"])->name('get_master_of_category');

    });
    Route::middleware(['auth.master'])->prefix("master")->prefix('level_category')->group(function () {
        Route::get('my_levels/{userLevelCategoryParentId}', [LevelCategoryController::class, "GetCurrentMasterLevelCategories"])->name('master.level_category.current_master_level_categories');
    });

    Route::prefix("course")->group(function () {
        // Route::middleware(['auth.student'])->group(function () {
        //     Route::get("/{user_level_category_id}", [LessonController::class, "GetLessonList"])->name("user_level_category.lesson.list");
        //     Route::get("details/{userLevelCategoryId}/{lessonId}", [LessonController::class, "GetLessonDetails"])->name("user_level_category.lesson.details");
        //     Route::get("get_address/{key}/{userLevelCategoryId}/{private_key}", [LessonController::class, "GetLessonFileAddressBySecretKey"])->name("user_level_category.lesson.files.address");
        //     Route::post("send_sample_work", [LessonController::class, "SendSampleWork"])->name("user_level_category.lesson.sample_work.send");
        //     Route::get("sample_work/{lessonId}/{userLevelCategoryId}", [LessonController::class, "MySampleWorkList"])->name("user_level_category.lesson.sample_work");

        // });
        Route::middleware(['auth.master'])->prefix("master")->group(function () {
            Route::get('index/{level_category_id}', [\App\Http\Controllers\Master\CourseController::class, "GetCoursesOfLevelCategoryId"])->name('master.course.index');
            Route::get('details/{course_id}', [\App\Http\Controllers\Master\CourseController::class, "GetCourseDetails"])->name('master.course.details');
            Route::get('files/{course_id}', [\App\Http\Controllers\Master\CourseController::class, "CourseFiles"])->name('master.course.files');
            Route::get('delete_file/{id}', [\App\Http\Controllers\Master\CourseController::class, "DeleteCourseFile"])->name('master.course.files.delete');
            Route::post('save_file', [\App\Http\Controllers\Master\CourseController::class, "SaveCourseFile"])->name('master.course.files.save');
            Route::post('details/save', [\App\Http\Controllers\Master\CourseController::class, "SaveCourse"])->name('master.course.details.save');
        });

        // Route::get('sample_work_image/{id}/{isThumbnail}', [LessonController::class, "ShowSampleWorkImage"])->name("user_level_category.lesson.sample_work.image");
        // Route::prefix("master")->group(function () {
        //     Route::get('details/{user_level_category_id}', [MasterController::class, "MasterDetails"])->name("master.details");
        //     Route::get('details/download_file/{file_id}', [MasterController::class, "GetMasterFileForDownload"])->name("master.details.file.download");
        // });
    });


    Route::prefix("user_level_category")->group(function () {

        Route::prefix("lesson")->group(function () {
            Route::middleware(['auth.student'])->group(function () {
                Route::get("/{user_level_category_id}", [LessonController::class, "GetLessonList"])->name("user_level_category.lesson.list");
                Route::get("details/{userLevelCategoryId}/{lessonId}", [LessonController::class, "GetLessonDetails"])->name("user_level_category.lesson.details");
                Route::get("get_address/{key}/{userLevelCategoryId}/{private_key}", [LessonController::class, "GetLessonFileAddressBySecretKey"])->name("user_level_category.lesson.files.address");
                Route::post("send_sample_work", [LessonController::class, "SendSampleWork"])->name("user_level_category.lesson.sample_work.send");
                Route::get("sample_work/{lessonId}/{userLevelCategoryId}", [LessonController::class, "MySampleWorkList"])->name("user_level_category.lesson.sample_work");

            });
            Route::middleware(['auth.master'])->group(function () {
                Route::prefix("master")->group(function () {
                    Route::prefix("my_student")->group(function () {
                        Route::get("/{userLevelCategoryParentId}", [MasterController::class, "GetMyStudentWithUserLevelCategoryId"])->name("user_level_category.master.my_student");
                        Route::get("/sample_work/{lessonId}/{userLevelCategoryId}", [MasterController::class, "MyStudentSampleWorkList"])->name("user_level_category.master.my_student.sample_work.details");
                        Route::get("/sample_work_lesson/{userLevelCategoryId}", [MasterController::class, "GetMyStudentSampleWorkLessonList"])->name("user_level_category.master.my_student.sample_work_lesson_list");
                        Route::post("/sample_work_apply_comment", [MasterController::class, "ApplyCommentOnSampleWork"])->name("user_level_category.master.my_student.sample_work.apply_comment");
                        Route::post("/sample_work_set_user_level_without_category_id", [MasterController::class, "SetUserLevelCategoryWithoutCategoryId"])->name("user_level_category.master.my_student.sample_work.set_user_level_without_category_id");
                    });

                });
            });

            Route::get('sample_work_image/{id}/{isThumbnail}', [LessonController::class, "ShowSampleWorkImage"])->name("user_level_category.lesson.sample_work.image");
            Route::prefix("master")->group(function () {
                Route::get('details/{user_level_category_id}', [MasterController::class, "MasterDetails"])->name("master.details");
                Route::get('details/download_file/{file_id}', [MasterController::class, "GetMasterFileForDownload"])->name("master.details.file.download");
            });
        });
    });

    Route::prefix('message')->group(function () {
        Route::get('/details/{message_id}', [MessageController::class, 'GetMessageDetails'])->name('message.details');
    });

    Route::prefix('level_category')->group(function () {
        Route::get('thumb_logo/{level_category_id}', [LevelCategoryController::class, "GetLevelCategoryLogoAsThumbnail"])->name('super_admin.level_category.logo.thumb');
        Route::get('logo/{level_category_id}', [LevelCategoryController::class, "GetLevelCategoryLogo"])->name('super_admin.level_category.logo');
    });
});

