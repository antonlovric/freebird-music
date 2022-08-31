<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return Order::query()->paginate($pageSize);
    }

    public function sendInvoice($orderId, $orderDetails) {
        $personalDetails = $orderDetails["personalDetails"];
        
        $client = new Party([
            'name'          => 'Free Bird Music',
            'phone'         => '01 3821 870',
            'custom_fields' => [
                'adresa'        => 'Tratinska 50, 10000 Zagreb, Hrvatska',
                'IBAN'        => 'HR1723600001101234565',
                "Model plaćanja" => "HR00",
                "Poziv na broj" => date("Y") . "-" . $orderId
            ],
        ]);

        $customer = new Party([
            "name" => $personalDetails["firstName"] . " " . $personalDetails["lastName"],
            "custom_fields" => [
                "email" => $orderDetails["email"],
                "telefon" => $orderDetails["phone"],
                "adresa" => $orderDetails["billing_address"],
                "grad" =>$orderDetails["billing_city"],
                "postanski broj" => $orderDetails["billing_zipcode"],
                "drzava" => $orderDetails["billing_country"],
            ]
        ]);

        $items = [];

        if (isset($orderDetails["cart_items"])) {
            foreach($orderDetails["cart_items"] as $item) {
                $items[] = (new InvoiceItem())->title($item["title"])->pricePerUnit($item["price"])->quantity($item["quantity"]);
            }
        }
        else {
            $cart_items = CartItem::query()->with(["products"])->where("cart_id","=", $orderDetails["cart_id"])->get();
            foreach($cart_items as $item) {
                $items[] = (new InvoiceItem())->title($item["products"]["title"])->pricePerUnit($item["price"])->quantity($item["quantity"]);
            }
        }

        $invoice = Invoice::make("Račun" . " " . $orderId)
        ->seller($client)
        ->buyer($customer)
        ->date(now()->subWeeks(3))
        ->dateFormat('d/m/Y')
        ->payUntilDays(14)
        ->currencyCode('HRK')
        ->currencyFormat('{VALUE} HRK')
        ->filename("Racun" . "-" . $orderId)
        ->addItems($items)
        ->logo(storage_path("app/public/images/logo.png"))
        ->save('s3');

        $link = $invoice->url();
        if( Mail::to($orderDetails["email"])->cc(["freebird-music-anton@gmail.com"])->send(new OrderInvoiceMail($orderId, $link)) == null){
            return response("error while sending invoice", 500);
        }
        else return response("invoice successfully sent");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "total" => "numeric|between:0,100000.99",
            "order_status_id" => "required|exists:order_statuses,id",
            "billing_address" => "required|string",
            "shipping_address" => "string",
            "phone" => "nullable|string",
            "email" => "required|string",
            "billing_city" => "string",
            "shipping_city" => "string",
            "billing_country" => "string",
            "shipping_country" => "string",
            "billing_zipcode" => "string",
            "shipping_zipcode" => "string",
            "session_id" => "nullable|sometimes|exists:users,session_id",
            "cart_id" => "nullable|sometimes|exists:carts,id",
            "comment" => "nullable|string",
            "payment_type" => "string",
            "shipping_type" => "string",
        ]);
        $user_id = User::query()->where("session_id", "=", $request["session_id"])->first("id");
        if ($user_id) $user_id = $user_id["id"];
        $request->request->remove("session_id");
        $request->request->add(["user_id" => $user_id]);
        $newOrder = Order::create($request->all());
        if ($newOrder) return $this->sendInvoice($newOrder["id"], $request->all());
        return $newOrder;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOrderedProducts(Request $request)
    {
        $request->validate(["session_id" => "required|exists:users,session_id"]);

        $user_id = User::query()->where("session_id", "=", $request["session_id"])->first("id");
        if ($user_id) $user_id = $user_id["id"];
        else return response("User not found!", 404);
        $products = [];
        $userInfo = Order::with(["cart", "cart.products"])->where("user_id", "=", $user_id)->get();
        for ($i = 0; $i < count($userInfo); $i++) {
            if (!isset ($userInfo[$i]["cart"])) continue;
            if (count($userInfo[$i]["cart"]["products"]) > 0) {
              for($j = 0; $j < count($userInfo[$i]["cart"]["products"]); $j++) {
                $products[] = $userInfo[$i]["cart"]["products"][$j];
              }  
            } 
        }

        return $products;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Order::where("id", $id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Order::destroy($id);
    }
}
