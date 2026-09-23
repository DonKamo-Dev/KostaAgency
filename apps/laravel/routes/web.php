<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ServiceController;
use App\Livewire\CaseStudies\Form as CaseStudiesForm;
use App\Livewire\CaseStudies\Index as CaseStudiesIndex;
use App\Livewire\Landing\Bento as LandingBento;
use App\Livewire\Landing\Contact as LandingContact;
use App\Livewire\Landing\Index as LandingIndex;
use App\Livewire\Landing\Portfolio as LandingPortfolio;
use App\Livewire\Landing\ServiceDetail as LandingServiceDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Herramientas de desarrollo local ─────────────────────────────────────────
if (app()->isLocal()) {
    $localAuthHandler = function () {
        $email = config('kamo.local_access_email');
        abort_unless(filled($email), 404);

        $user = User::where('email', $email)->firstOrFail();
        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    };
    Route::get('/entrar-kamo', $localAuthHandler);
    Route::get('/entrar-kosta', $localAuthHandler);

    Route::get('/react-test', function () {
        return Inertia::render('ReactTest', [
            'message' => 'React + Inertia.js está funcionando!',
        ]);
    })->name('react.test');
}

// ── Pública ──────────────────────────────────────────────────────────────────
Route::get('/', LandingIndex::class)->name('landing');
Route::get('/portafolio', LandingPortfolio::class)->name('portfolio');
Route::get('/contacto', LandingContact::class)->name('contact');
Route::get('/cv', LandingBento::class)->name('cv');
Route::get('/kamo', LandingBento::class)->name('kamo');
Route::get('/perfil', LandingBento::class)->name('perfil');
Route::get('/servicios/{slug}', LandingServiceDetail::class)->name('services.show');
Route::redirect('/servicios', '/#servicios')->name('services.landing');
Route::redirect('/bento', '/kamo');
Route::view('/privacidad', 'privacy')->name('privacy');

// ── Archivos estáticos en /storage/... (resiliente para entornos serverless) ──
Route::get('/storage/{path}', function (string $path) {
    $publicFile = public_path('storage/' . $path);
    if (file_exists($publicFile)) {
        return response()->file($publicFile);
    }

    $storageFile = storage_path('app/public/' . $path);
    if (file_exists($storageFile)) {
        return response()->file($storageFile);
    }

    abort(404);
})->where('path', '.*')->name('storage.file');

// ── Autenticadas ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('case-studies', CaseStudiesIndex::class)->name('case-studies.index');
    Route::get('case-studies/create', CaseStudiesForm::class)->name('case-studies.create');
    Route::get('case-studies/{caseStudy}/edit', CaseStudiesForm::class)->name('case-studies.edit');
    Route::view('profile', 'profile')->name('profile');

    // PDF (compartido por todos los documentos)
    Route::get('/documents/{document}/pdf', [DocumentController::class, 'pdf'])->name('documents.pdf');

    // ── Cotizaciones ──────────────────────────────────────────────────────────
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
    Route::get('/quotes/{document}', [QuoteController::class, 'show'])->whereNumber('document')->name('quotes.show');
    Route::get('/quotes/{document}/edit', [QuoteController::class, 'edit'])->whereNumber('document')->name('quotes.edit');
    Route::get('/quotes/form-data', [QuoteController::class, 'formData'])->name('quotes.form-data');
    Route::get('/quotes/{document}/edit-data', [QuoteController::class, 'editData'])->name('quotes.edit-data');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::put('/quotes/{document}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::delete('/quotes/{document}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
    Route::post('/quotes/{document}/convert', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert');
    Route::post('/quotes/{document}/duplicate', [QuoteController::class, 'duplicate'])->name('quotes.duplicate');
    Route::post('/quotes/{document}/cancel', [QuoteController::class, 'cancel'])->name('quotes.cancel');

    // ── Cuentas de Cobro ──────────────────────────────────────────────────────
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/form-data', [BillController::class, 'formData'])->name('bills.form-data');
    Route::get('/bills/{document}/edit-data', [BillController::class, 'editData'])->name('bills.edit-data');
    Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
    Route::put('/bills/{document}', [BillController::class, 'update'])->name('bills.update');
    Route::delete('/bills/{document}', [BillController::class, 'destroy'])->name('bills.destroy');
    Route::post('/bills/{document}/payment', [BillController::class, 'registerPayment'])->name('bills.payment');

    // ── Facturas ──────────────────────────────────────────────────────────────
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/{document}', [InvoiceController::class, 'show'])->whereNumber('document')->name('invoices.show');
    Route::get('/invoices/{document}/edit', [InvoiceController::class, 'edit'])->whereNumber('document')->name('invoices.edit');
    Route::get('/invoices/form-data', [InvoiceController::class, 'formData'])->name('invoices.form-data');
    Route::get('/invoices/{document}/edit-data', [InvoiceController::class, 'editData'])->name('invoices.edit-data');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::put('/invoices/{document}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{document}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::post('/invoices/{document}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::post('/invoices/{document}/payment', [InvoiceController::class, 'registerPayment'])->name('invoices.payment');

    // ── Clientes ──────────────────────────────────────────────────────────────
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // ── Servicios ─────────────────────────────────────────────────────────────
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // ── Gastos ────────────────────────────────────────────────────────────────
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});

// ── Logout ────────────────────────────────────────────────────────────────────
Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
