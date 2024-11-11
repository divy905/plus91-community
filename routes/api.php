<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\RazorpayController;

Route::prefix('v1/')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/send-otp', [UserController::class, 'send_otp']);
    Route::post('/verify-otp', [UserController::class, 'verify_otp']);
    Route::get('/banner-list', [APIController::class, 'banner']);
    Route::get('/home', [APIController::class, 'home']);
    Route::post('/products', [APIController::class, 'productList']);
    Route::get('/native-village-list', [APIController::class, 'nativeVillageList']);
    Route::post('/productDetails', [APIController::class, 'productDetails']);
    Route::get('/privacy-policy', [APIController::class, 'privacyPolicy']);
    Route::get('/settings', [APIController::class, 'settings']);
    Route::get('/gallery-list', [APIController::class, 'galleryList']);
    Route::get('/news-list', [APIController::class, 'newsList']);
    
    // All Auth Routes 
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::get('/events-list/{id?}', [APIController::class, 'eventsList']);
        Route::post('/update-avatar', [UserController::class, 'updateProfileAvatar']);
        Route::post('/addAddress', [APIController::class, 'addAddress']);
        Route::post('/updateAddress', [APIController::class, 'updateAddress']);
        Route::get('/logout', [UserController::class, 'logout']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::post('/update-user-details', [UserController::class, 'updateUserDetails']);
        Route::post('/help-and-support', [APIController::class, 'contactUsQuerySend']);
        Route::get('/help-and-support-queries-list', [APIController::class, 'helpAndSupportQueriesList']);
        Route::get('/goatra-list', [UserController::class, 'goatraList']);
        Route::post('/book-events', [UserController::class, 'bookEvents']);
        Route::get('/booking-event-list', [UserController::class, 'bookEventList']);
        Route::post('/add-member', [UserController::class, 'addMember']);
        Route::post('/update-member-profile', [UserController::class, 'updateMember']);
        Route::get('/member-list/{id?}', [UserController::class, 'memberList']);
        Route::get('/delete-member/{id}', [UserController::class, 'memberDelete']);
        Route::get('/community-info', [APIController::class, 'communityInfo']);
        Route::get('/matrimony-list/{id?}', [APIController::class, 'matrimonyList']);
        Route::get('/matrimony-family-member-list/{id}', [APIController::class, 'matrimonyFamilyMemberList']);
        Route::get('/directory-list/{id?}', [APIController::class, 'directoryList']);
        Route::get('/directory-family-member-list/{id}', [APIController::class, 'directoryFamilyMemberList']);
        Route::get('/group-list', [APIController::class, 'groupList']);
        Route::get('/group-details/{id}', [APIController::class, 'groupDetails']);
        Route::get('/get-all-wishlist', [APIController::class, 'getAllWishlist']);
        Route::post('/add-wishlist', [APIController::class, 'addWishlist']);
        Route::post('/remove-wishlist', [APIController::class, 'removeWishlist']);
        Route::get('/trustee-list/{id?}', [APIController::class, 'trusteeList']);
        Route::get('/commitee-list/{id?}', [APIController::class, 'commiteeList']);
        Route::get('/head-of-family-details/{id?}', [APIController::class, 'headOfFamilyDetails']);
        Route::get('/notification-list', [APIController::class, 'notificationList']);
        Route::post('/pay-to-book-event', [RazorpayController::class, 'payToBookEvent']); 
        Route::post('/update-payment-response', [RazorpayController::class, 'updatePaymentResponse']);
        Route::get('/is-approved', [UserController::class, 'isApproved']);
    });
});
