<?php

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

// Route::get('/',function(){
// echo "working";
// })->middleware('APIWare');

//routes for admin login

Route::post('nigol','UserControllerAPI@login');

Route::group(['prefix' => 'auth','middleware' => 'APIAdminWare'],function(){
//Route::post('login')
Route::post('/addcat','AdminControllerAPI@addCategory');

Route::get('/getCategories','AdminControllerAPI@getCategories');
Route::get('/getproducts','AdminControllerAPI@getProducts');
Route::get('/getproduct','AdminControllerAPI@getProduct');
Route::get('/deleteproduct','AdminControllerAPI@deleteproduct');

Route::post('addproduct','AdminControllerAPI@addproduct');
Route::post('updateproduct','AdminControllerAPI@updateProduct');

Route::get('getneworders', 'AdminControllerAPI@getNewOrders');
Route::get('getoldorders', 'AdminControllerAPI@getOlderOrders');
Route::get('getcheckout','AdminControllerAPI@getcheckout');
Route::get('getorderproducts','AdminControllerAPI@getOrderProducts');
Route::get('shiporder','AdminControllerAPI@shipOrder');
Route::get('getmem','AdminControllerAPI@getmembers');
Route::get('getmemcheckouts','AdminControllerAPI@getMemberCheckouts');

//profile
Route::get('getprofile','AdminControllerAPI@getprofile');
Route::post('updateprofile','AdminControllerAPI@updateProfile');



//gallery

Route::post('uploadimage','APIAdminGalleryController@uploadimage');
Route::get('getgallery','APIAdminGalleryController@getGallery');

//apointments
Route::get('getmonthappointments','AdminControllerAPI@getappointmentsofmonth');
Route::get('getdayappointments','AdminControllerAPI@getappointmentsforday');
Route::get('getuserappointments','AdminControllerAPI@getuserappointments');

//services
Route::get('getservices','ServiceController@getServices');
Route::get('addservice','ServiceController@addService');
Route::get('updateservice','ServiceController@updateservice');
Route::get('deleteservice','ServiceController@deleteservice');

//participants

Route::get('getparticipants','ParticipantsControllerAPI@getParticipants');

//messenger
Route::get('getmessages','MessengerControllerAPI@getMessages');
Route::get('sendmessage','MessengerControllerAPI@sendMessage');


//notifications for admin
Route::get('notifications','NotificationController@getBookingNotification');
Route::get('confirmapt','NotificationController@confirmAppointment');
Route::get('declineapt','NotificationController@declineAppointment');

//counts
Route::get('getcounts','CountController@getCountForNotificationsAndChat');



});


//frontend app routes

Route::get('login','FrontendAPIsController@loginPost');
Route::get('register','FrontendAPIsController@register');


Route::get('getproducts','FrontendAPIsController@getProducts');
Route::get('product/{id}','FrontendAPIsController@product');
Route::get('getcats','FrontendAPIsController@getcats');
Route::get('getcatproducts/{id}','FrontendAPIsController@getcatproducts');

Route::get('s','AdminControllerAPI@sendEmail');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//user app routes - login required
// Route::group(['prefix' => 'user','middleware' => 'UserAPIWare'], function () {
// Route::get('updateprofile','FrontendAPIsController@updateProfileDetails');
// Route::get('getproducts','FrontendAPIsController@getLoggedInUserProducts');

// Route::get('getuser','FrontendAPIsController@getuser');
// Route::get('changepass','FrontendAPIsController@changepass');

// Route::get('addtowishlist','FrontendAPIsController@addToWishList');

// Route::get('addtowishlisttab','FrontendAPIsController@addToWishListProductsTab');

// Route::get('wishlist','FrontendAPIsController@getWishList');

// Route::post('cart','FrontendAPIsController@returnCart');
// Route::get('paycart/{token}/{id}','PaymentController@payAPIforcart');

// //unpaid checkouts.
// Route::get('unpaid','FrontendAPIsController@getUserUnPaidCheckouts');
// Route::get('paid','FrontendAPIsController@getUserUnPaidCheckouts');

// Route::get('unpaidpro','FrontendAPIsController@getUnPaidCheckoutProducts');
// Route::get('paidpro','FrontendAPIsController@getPaidCheckoutProducts');
// });

// Route::group(['prefix' => 'man'],function(){
//     Route::get('cartpaid','PaymentController@getPaymentStatusForPaidCartAPI');
//     Route::get('/', function () {
//         echo "You have cancelled the payment method process. Please click the Done button in bar to get back into the app.";
//     });
// });



///////////////////////// User APP APIS
Route::get('gallery','APIUserAppController@getGallery');

Route::group(['prefix' => 'user'], function () {
Route::get('bookappointment','APIFrontAppointmentController@bookappointment');

//Appointments
Route::get('getappointmentsfortheday','APIFrontAppointmentController@getappointmentsfortheday');
Route::get('getcurrentmonthappointments','APIFrontAppointmentController@getcurrentmonthappointments');

// services
Route::get('getservices','ServiceController@getServices');

});

