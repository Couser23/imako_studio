<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $packages = \App\Models\Package::where('is_active', true)->take(6)->get();
    $reviews = \App\Models\Review::with(['booking.user', 'booking.package'])
                    ->whereNotNull('comment')
                    ->where('comment', '!=', '')
                    ->where('rating', '>=', 4)
                    ->latest()
                    ->take(5)
                    ->get();
    $settings = \App\Models\StudioSetting::first();
    
    // Top 3 most booked packages
    $hotSalePackages = \App\Models\Package::where('is_active', true)
                        ->withCount('bookings')
                        ->orderBy('bookings_count', 'desc')
                        ->take(3)
                        ->get();
                        
    // Categories
    $categories = \App\Models\Category::withCount('packages')->get();
                        
    // Portfolio images
    $portfolioImages = [];
    if (\Illuminate\Support\Facades\File::exists(public_path('images/dokumentasi'))) {
        $portfolioImages = array_map(function($file) {
            return $file->getFilename();
        }, \Illuminate\Support\Facades\File::files(public_path('images/dokumentasi')));
    }
    
    // Cek status buka/tutup
    $now = \Carbon\Carbon::now();
    $isOpen = false;
    $closeReason = 'Studio sedang tutup';
    
    $closedDate = \App\Models\ClosedDate::where('start_date', '<=', $now->toDateString())
                    ->where('end_date', '>=', $now->toDateString())
                    ->first();
                    
    if ($closedDate) {
        $isOpen = false;
        $closeReason = 'Tutup: ' . $closedDate->reason;
    } else {
        $dayMap = [
            'Monday' => 'Sen', 'Tuesday' => 'Sel', 'Wednesday' => 'Rab',
            'Thursday' => 'Kam', 'Friday' => 'Jum', 'Saturday' => 'Sab', 'Sunday' => 'Min'
        ];
        $currentDayNameIndo = $dayMap[$now->englishDayOfWeek];
        
        $operationalDays = $settings ? $settings->operational_days : ['Sen','Sel','Rab','Kam','Jum','Sab'];
        if (!is_array($operationalDays)) $operationalDays = ['Sen','Sel','Rab','Kam','Jum','Sab'];
        
        if (!in_array($currentDayNameIndo, $operationalDays)) {
            $isOpen = false;
            $closeReason = 'Tutup pada hari ' . $now->locale('id')->dayName;
        } else {
            $openTime = $settings ? \Carbon\Carbon::parse($settings->open_time) : \Carbon\Carbon::parse('09:00:00');
            $closeTime = $settings ? \Carbon\Carbon::parse($settings->close_time) : \Carbon\Carbon::parse('18:00:00');
            $currentTime = \Carbon\Carbon::parse($now->format('H:i:s'));
            
            if ($currentTime->between($openTime, $closeTime)) {
                $isOpen = true;
                $closeReason = '';
            } else {
                $isOpen = false;
                if ($currentTime->lt($openTime)) {
                    $closeReason = 'Tutup. Buka jam ' . $openTime->format('H:i') . ' WIB';
                } else {
                    $closeReason = 'Tutup. Buka besok jam ' . $openTime->format('H:i') . ' WIB';
                }
            }
        }
    }

    return view('welcome', compact('packages', 'categories', 'reviews', 'settings', 'isOpen', 'closeReason', 'hotSalePackages', 'portfolioImages'));
});

// DEV ONLY: Fast switch to employee role
Route::get('/switch-to-employee', function () {
    $employee = \App\Models\User::where('email', 'krisnaaldi@gmail.com')->first();
    if (!$employee) {
        $employee = \App\Models\User::where('role', 'pegawai')->first();
    }
    
    if($employee) {
        auth()->login($employee);
        session()->put('2fa_passed', true); // Bypass 2FA if enabled
        return redirect()->route('employee.dashboard');
    }
    return 'Belum ada akun pegawai di database!';
});

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\PaymentValidationController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EmployeeScheduleController;
use App\Http\Controllers\Admin\LeaveRequestController;

Route::middleware(['auth', 'role:admin', 'require-2fa'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/packages', [PackageController::class, 'index'])->name('admin.packages.index');
    Route::get('/admin/packages/create', [PackageController::class, 'create'])->name('admin.packages.create');
    Route::post('/admin/packages', [PackageController::class, 'store'])->name('admin.packages.store');
    Route::get('/admin/packages/{package}/edit', [PackageController::class, 'edit'])->name('admin.packages.edit');
    Route::put('/admin/packages/{package}', [PackageController::class, 'update'])->name('admin.packages.update');
    Route::delete('/admin/packages/{package}', [PackageController::class, 'destroy'])->name('admin.packages.destroy');
    
    // Category Management
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // Addon Management
    Route::post('/admin/addons', [\App\Http\Controllers\Admin\AddonController::class, 'store'])->name('admin.addons.store');
    Route::put('/admin/addons/{addon}', [\App\Http\Controllers\Admin\AddonController::class, 'update'])->name('admin.addons.update');
    Route::delete('/admin/addons/{addon}', [\App\Http\Controllers\Admin\AddonController::class, 'destroy'])->name('admin.addons.destroy');

    Route::get('/admin/finance', [FinanceController::class, 'index'])->name('admin.finance.index');
    Route::get('/admin/finance/print', [FinanceController::class, 'print'])->name('admin.finance.print');
    Route::post('/admin/finance/target', [FinanceController::class, 'updateTarget'])->name('admin.finance.update-target');
    Route::post('/admin/finance/expenses', [FinanceController::class, 'storeExpense'])->name('admin.finance.store-expense');
    Route::get('/admin/payments', [PaymentValidationController::class, 'index'])->name('admin.payments.index');
    Route::put('/admin/payments/{payment}/status', [PaymentValidationController::class, 'updateStatus'])->name('admin.payments.update-status');
    Route::get('/admin/payment-methods', [PaymentMethodController::class, 'index'])->name('admin.payment-methods.index');
    Route::get('/admin/payment-methods/create', [PaymentMethodController::class, 'create'])->name('admin.payment-methods.create');
    Route::post('/admin/payment-methods', [PaymentMethodController::class, 'store'])->name('admin.payment-methods.store');
    Route::get('/admin/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('admin.payment-methods.edit');
    Route::put('/admin/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('admin.payment-methods.update');
    Route::delete('/admin/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('admin.payment-methods.destroy');
    Route::get('/admin/schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/admin/schedules/block', [ScheduleController::class, 'block'])->name('admin.schedules.block');
    Route::post('/admin/schedules/{booking}/assign', [ScheduleController::class, 'assign'])->name('admin.schedules.assign');
    Route::put('/admin/schedules/{booking}/status', [ScheduleController::class, 'updateStatus'])->name('admin.schedules.update-status');
    
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    Route::get('/admin/employee-schedules', [EmployeeScheduleController::class, 'index'])->name('admin.employee-schedules.index');
    

    Route::get('/admin/leave-requests', [LeaveRequestController::class, 'index'])->name('admin.leave-requests.index');
    Route::put('/admin/leave-requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('admin.leave-requests.update-status');
    Route::delete('/admin/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('admin.leave-requests.destroy');
});

Route::middleware(['auth', 'role:pegawai', 'require-2fa'])->prefix('pegawai')->name('employee.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Employee\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Employee\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/booking/{booking}/status', [\App\Http\Controllers\Employee\TaskController::class, 'updateStatus'])->name('update_status');
    Route::post('/booking/{booking}/result', [\App\Http\Controllers\Employee\TaskController::class, 'submitResult'])->name('submit_result');
    Route::get('/jadwal', [\App\Http\Controllers\Employee\DashboardController::class, 'jadwal'])->name('jadwal');
    Route::get('/tugas', [\App\Http\Controllers\Employee\DashboardController::class, 'tugas'])->name('tugas');
    Route::get('/kirim-hasil', [\App\Http\Controllers\Employee\DashboardController::class, 'kirimHasil'])->name('kirim_hasil');
    Route::get('/pengajuan-libur', [\App\Http\Controllers\Employee\DashboardController::class, 'pengajuanLibur'])->name('pengajuan_libur');
    Route::post('/pengajuan-libur', [\App\Http\Controllers\Employee\DashboardController::class, 'storePengajuanLibur'])->name('store_pengajuan_libur');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('catalog', [\App\Http\Controllers\User\CatalogController::class, 'index'])->name('catalog.index');
    Route::get('schedules', [\App\Http\Controllers\User\ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('bookings', [\App\Http\Controllers\User\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [\App\Http\Controllers\User\BookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings', [\App\Http\Controllers\User\BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [\App\Http\Controllers\User\BookingController::class, 'show'])->name('bookings.show');
    Route::post('payments/{booking}', [\App\Http\Controllers\User\PaymentController::class, 'store'])->name('payments.store');
    Route::get('results', [\App\Http\Controllers\User\ResultController::class, 'index'])->name('results.index');
    Route::post('reviews/{booking}', [\App\Http\Controllers\User\ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/sessions', [ProfileController::class, 'destroySessions'])->name('profile.sessions.destroy');
    Route::get('/profile/sessions/html', [ProfileController::class, 'sessionsHtml'])->name('profile.sessions.html');
    Route::delete('/profile/sessions/{id}', [ProfileController::class, 'destroySession'])->name('profile.session.destroy');
    Route::post('/admin/studio-settings', [\App\Http\Controllers\Admin\StudioSettingController::class, 'update'])->name('admin.studio-settings.update');
    Route::post('/admin/studio-settings/close-date', [\App\Http\Controllers\Admin\StudioSettingController::class, 'storeCloseDate'])->name('admin.studio-settings.close-date');
    Route::delete('/admin/studio-settings/close-date/{id}', [\App\Http\Controllers\Admin\StudioSettingController::class, 'destroyCloseDate'])->name('admin.studio-settings.destroy-close-date');
    Route::post('/admin/notification-settings', [\App\Http\Controllers\Admin\NotificationSettingController::class, 'update'])->name('admin.notification-settings.update');

    Route::get('/profile/2fa', [\App\Http\Controllers\TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/profile/2fa', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::delete('/profile/2fa', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
});

Route::get('/2fa-challenge', [\App\Http\Controllers\TwoFactorController::class, 'getChallenge'])->name('2fa.challenge');
Route::post('/2fa-challenge', [\App\Http\Controllers\TwoFactorController::class, 'verifyChallenge'])->name('2fa.verify');

require __DIR__.'/auth.php';
