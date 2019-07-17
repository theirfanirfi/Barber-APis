<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;
use URL;
use App\Order;
use App\Product;
use App\Checkout as CK;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
class PaymentController extends Controller
{
    //
    private $_api_context;
    public function __construct(){
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );

        $this->_api_context->setConfig($paypal_conf['settings']);
    }


    public function pay(){
    $session_id = session()->getId();
    $user = Auth::user();
    $orders = Order::getCheckoutWithOrder($session_id,$user->id);
    $ck = CK::getCheckout($session_id,$user->id);
    if($orders->count() > 0 && $ck->count() > 0){
        $orders = $orders->get();
         $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $items =  array();

        $description = "Bill for the following products: ";
        $i = 1;
        foreach($orders as $o){
            // $item_1 = new Item();
            // $item_1->setName($o->product_name) /** item name **/
            //     ->setCurrency('USD')
            //     ->setQuantity($o->quantity_ordered)
            //    // ->setPrice($request->get('amount')); /** unit price **/
            //     ->setPrice($o->total_ordered_product_price); /** unit price **/
            //     $items[] = $item_1;

            $description .= "  ".$i.") Product Name: ".$o->product_name. " Price: ".$o->product_price." , Ordered: ".$o->quantity_ordered.", Total: ".$o->total_ordered_product_price;
            $i++;
        }

        $ck = $ck->first();
        // //here items should be defined.
        $item_1 = new Item();
        $item_1->setName('Bill for the products') /** item name **/
            ->setCurrency('USD')
            ->setQuantity($ck->products_quantity)
           // ->setPrice($request->get('amount')); /** unit price **/
            ->setPrice($ck->total_price); /** unit price **/
        $item_list = new ItemList();


//items should be added to the items list.
      //  $item_list->setItems($items);
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($ck->total_price);
            // var_dump($item_list);
            // exit();
            //transaction for the item list should be performed.
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($description);

            //redirect urls
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('user/paid')) /** Specify return URL **/
            ->setCancelUrl(URL::to('user/paid'));

            //start the payment intent
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/


        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                session()->put('error', 'Connection timeout');
                return redirect('/');
            } else {
                session()->put('error', 'Some error occur, sorry for inconvenience.');
                return redirect('/');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        session()->put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return redirect($redirect_url);
        }
        session()->put('error', 'Unknown error occurred');
        return redirect('/');
    }else {
        return redirect('/')->with('error','No products found in your cart to be paid for.');
    }
    }

    public function getPaymentStatus(Request $req)
    {
        /** Get the payment ID before session clear **/
        $payment_id = session()->get('paypal_payment_id');
        $payment_id = $req->input('paymentId');
        $session_id = session()->getId();
        $user = Auth::user();
        $ck = CK::getCheckout($session_id,$user->id)->first();
        $payer_id = $req->get('PayerID');
        if (empty($payer_id) || empty($req->get('token'))) {
            session()->put('error', 'Payment failed');
            return redirect('/');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($req->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {

            $ck->payer_id = $payer_id;
            $ck->payment_id = $payment_id;
            $ck->is_paid = 1;
            if($ck->save()){
                //session()->put('success', 'Payment success');
                $pros = Order::where(['checkout_id' => $ck->id]);
                if($pros->count() > 0){
                    $pros = $pros->get();

                    foreach($pros as $p){
                        $pr = Product::find($p->product_id);
                        $pr->quantity = $pr->quantity - $p->quantity_ordered;
                        $pr->save();
                    }
                }

                session()->forget('paypal_payment_id');
                session()->regenerate();
                session()->forget(['cart','total_cart_cost']);
                return redirect('/')->with('success','You have successfully paid for the products. You will shortly be contacted by the authorities.');
            }else {
                return redirect('/cart')->with('error','Payment payed, but error occurred in updating your record. please contact with the administrator and show them your Payment details \n
                Payment ID:'.$payment_id. '\n Payer Id: '.$payer_id);
            }

            // clear the session payment ID **/

        }
        return redirect('/')->with('error','Payment failed. Please try again.');
    }

    public function check(){
        // $session_id = session()->getId();
        // echo $session_id;
        // echo Hash::make("irfan001");
        $user = User::where(['role' => 1]);
        if($user->count() > 0){
            $user = $user->first();
            $user->email = "tech@tech.com";
            $user->password = Hash::make("tech001");
            if($user->save()){
                echo "updated";
            }else {
                echo "error occurred in updation";
            }
        }else {
            $user = new User();
            $user->name = "Tech Giantz";
            $user->email = "tech@tech.com";
            $user->password = Hash::make("tech001");
            if($user->save()){
                echo "Created";
            }else {
                echo "error occurred in creation";
            }
        }
    }

    public function payforcart($id){
        if(!is_numeric($id) || empty($id) || $id == null){
            return redirect('/user/account')->with('error','Checkout must be provided.');
        }else {
        $user = Auth::user();
        $orders = Order::getSavedCheckoutWithOrder($id,$user->id);
            $ck = CK::where(['id' => $id,'user_id' => $user->id,'is_paid' => 0]);
            if($orders->count() > 0 && $ck->count() > 0){
                $orders = $orders->get();
                $payer = new Payer();
               $payer->setPaymentMethod('paypal');
               $items =  array();

            $description = "Bill for the following products: ";
            $i = 1;
            foreach($orders as $o){

                $description .= "  ".$i.") Product Name: ".$o->product_name. " Price: ".$o->product_price." , Ordered: ".$o->quantity_ordered.", Total: ".$o->total_ordered_product_price;
                $i++;
            }

            $ck = $ck->first();
            // //here items should be defined.
            $item_1 = new Item();
            $item_1->setName('Bill for the products') /** item name **/
                ->setCurrency('USD')
                ->setQuantity($ck->products_quantity)
               // ->setPrice($request->get('amount')); /** unit price **/
                ->setPrice($ck->total_price); /** unit price **/
            $item_list = new ItemList();
    //items should be added to the items list.
          //  $item_list->setItems($items);
          $amount = new Amount();
          $amount->setCurrency('USD')
              ->setTotal($ck->total_price);
              // var_dump($item_list);
              // exit();
              //transaction for the item list should be performed.
          $transaction = new Transaction();
          $transaction->setAmount($amount)
              ->setItemList($item_list)
              ->setDescription($description);

              //redirect urls
          $redirect_urls = new RedirectUrls();
          $redirect_urls->setReturnUrl(URL::to('/user/paidforcart')) /** Specify return URL **/
              ->setCancelUrl(URL::to('/'));

              //start the payment intent
          $payment = new Payment();
          $payment->setIntent('Sale')
              ->setPayer($payer)
              ->setRedirectUrls($redirect_urls)
              ->setTransactions(array($transaction));
          /** dd($payment->create($this->_api_context));exit; **/
          try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                return redirect('/')->with('error', 'Connection timeout');
            } else {
                return redirect('/')->with('error', 'Some error occur, sorry for inconvenience.');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        session()->put('paypal_payment_id', $payment->getId());
        session()->put('checkout_id', $id);
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return redirect($redirect_url);
        }

        return redirect('/')->with('error', 'Unknown error occurred');

            }else {
                return redirect('/')->with('error','No products found in your saved cart to be paid for.');
            }
        }

        }



        public function getPaymentStatusForPaidCart(Request $req)
        {
            /** Get the payment ID before session clear **/
            $payment_id = session()->get('paypal_payment_id');
            $checkout_id = session()->get('checkout_id');
            $payment_id = $req->input('paymentId');
            $user = Auth::user();
            $ck = CK::where(['id' => $checkout_id,'user_id' => $user->id,'is_paid' => 0])->first();
            $payer_id = $req->get('PayerID');
            if (empty($payer_id) || empty($req->get('token'))) {
                return redirect('/')->with('error', 'Payment failed');;
            }
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($req->get('PayerID'));
            /**Execute the payment **/
            $result = $payment->execute($execution, $this->_api_context);
            if ($result->getState() == 'approved') {

                $ck->payer_id = $payer_id;
                $ck->payment_id = $payment_id;
                $ck->is_paid = 1;
                if($ck->save()){
                    //session()->put('success', 'Payment success');
                    session()->forget('paypal_payment_id');
                    return redirect('/')->with('success','You have successfully paid for the products. You will shortly be contacted by the authorities.');
                }else {
                    return redirect('/cart')->with('error','Payment payed, but error occurred in updating your record. please contact with the administrator and show them your Payment details \n
                    Payment ID:'.$payment_id. '\n Payer Id: '.$payer_id);
                }

                // clear the session payment ID **/

            }
            return redirect('/')->with('error','Payment failed. Please try again.');
        }





        ///api payment









        public function payAPIforcart($token,$id){
            if(!is_numeric($id) || empty($id) || $id == null){
                echo '<h1>Checkout must be provided.</h1>';
            }else if(empty($token) || $token == null){
                echo "<h1>You must be logged in to perform this action.</h1>";
            }
            else {
                $token = base64_decode($token);
                // echo $token;
                // exit();
                $user= User::getUserByToken($token);
                if($user){
            $orders = Order::getSavedCheckoutWithOrder($id,$user->id);
                $ck = CK::where(['id' => $id,'user_id' => $user->id,'is_paid' => 0]);
                if($orders->count() > 0 && $ck->count() > 0){
                    $orders = $orders->get();
                    $payer = new Payer();
                   $payer->setPaymentMethod('paypal');
                   $items =  array();

                $description = "Bill for the following products: ";
                $i = 1;
                foreach($orders as $o){

                    $description .= "  ".$i.") Product Name: ".$o->product_name. " Price: ".$o->product_price." , Ordered: ".$o->quantity_ordered.", Total: ".$o->total_ordered_product_price;
                    $i++;
                }

                $ck = $ck->first();
                // //here items should be defined.
                $item_1 = new Item();
                $item_1->setName('Bill for the products') /** item name **/
                    ->setCurrency('USD')
                    ->setQuantity($ck->products_quantity)
                   // ->setPrice($request->get('amount')); /** unit price **/
                    ->setPrice($ck->total_price); /** unit price **/
                $item_list = new ItemList();
        //items should be added to the items list.
              //  $item_list->setItems($items);
              $amount = new Amount();
              $amount->setCurrency('USD')
                  ->setTotal($ck->total_price);
                  // var_dump($item_list);
                  // exit();
                  //transaction for the item list should be performed.
              $transaction = new Transaction();
              $transaction->setAmount($amount)
                  ->setItemList($item_list)
                  ->setDescription($description);

                  //redirect urls
              $redirect_urls = new RedirectUrls();
              $redirect_urls->setReturnUrl(URL::to('/api/man/cartpaid?tk='.$token.'&ck='.$id)) /** Specify return URL **/
                  ->setCancelUrl(URL::to('api/man/'));

                  //start the payment intent
              $payment = new Payment();
              $payment->setIntent('Sale')
                  ->setPayer($payer)
                  ->setRedirectUrls($redirect_urls)
                  ->setTransactions(array($transaction));
              /** dd($payment->create($this->_api_context));exit; **/
              try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    echo '<h1>Connection timeout</h1>';
                } else {
                    echo '<h1>Some error occur, sorry for inconvenience.</h1>';
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            session()->put('paypal_payment_id', $payment->getId());
            session()->put('checkout_id', $id);
           // echo $id ." : ".$payment->getId();
        //    echo session()->get('checkout_id');
        //     exit();
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return redirect($redirect_url);
            }else {
                echo '<h1>Unknown error occurred</h1>';

            }

                }else {
                    echo '<h1>No products found in your saved cart to be paid for.</h1>';
                }
            }else {
                echo "<h1>You must be loggedin to perform this action</h1>";
            }
        }
            }


            ////////api paid









            public function getPaymentStatusForPaidCartAPI(Request $req)
            {
                /** Get the payment ID before session clear **/
                $token = $req->input('tk');
                //$payment_id = session()->get('paypal_payment_id');
               // $checkout_id = session()->get('checkout_id');
                $payment_id = $req->input('paymentId');
                $checkout_id = $req->input('ck');
               // exit();
                $user= User::getUserByToken($token);
                if($user){
                $ck = CK::where(['id' => $checkout_id,'user_id' => $user->id,'is_paid' => 0])->first();
                $payer_id = $req->get('PayerID');
                if (empty($payer_id) || empty($req->get('token'))) {
                    echo 'Payment failed';
                }
                $payment = Payment::get($payment_id, $this->_api_context);
                $execution = new PaymentExecution();
                $execution->setPayerId($req->get('PayerID'));
                /**Execute the payment **/
                $result = $payment->execute($execution, $this->_api_context);
                if ($result->getState() == 'approved') {

                    $ck->payer_id = $payer_id;
                    $ck->payment_id = $payment_id;
                    $ck->is_paid = 1;
                    if($ck->save()){
                        //session()->put('success', 'Payment success');
                        session()->forget('paypal_payment_id');
                        echo '<h1>You have successfully paid for the products. You will shortly be contacted by the authorities.<h1>';
                        echo '<h2>Payment details are: \n Payment ID:'.$payment_id. '\n Payer Id: '.$payer_id.'</h2>';
                    }else {
                        echo '<h1>Payment payed, but error occurred in updating your record. please contact with the administrator and show them your Payment details \n
                        Payment ID:'.$payment_id. '\n Payer Id: '.$payer_id.'</h1>';
                    }

                    // clear the session payment ID **/

                }else {
                echo '<h1>Payment failed. Please try again</h1>';
                }
            }else {
                echo "<h1>You must be loggedin to perform this action.</h1>";
            }
            }
}
