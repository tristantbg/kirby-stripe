<?php

namespace KirbyStripe;

class Methods
{
  private static function findObjectById($array, $id)
  {
    foreach ($array as $element) {
      if ($id == $element->id) {
        return $element;
      }
    }

    return false;
  }
  public static function checkoutPriceID($price_id, $options = [])
  {
    $stripe_client = Auth::stripeClient();
    try {
      $shipping_rate_data = option('tristantbg.kirby-stripe.shipping_rate_data');

      if (!empty($options['shipping_rate_amount'])) {
        $shipping_rate_data['fixed_amount']['amount'] = $options['shipping_rate_amount'];
      }

      $checkout_session = $stripe_client->checkout->sessions->create([
        'payment_method_types' => option('tristantbg.kirby-stripe.payment_method_types'),
        'line_items' => [[
          'price' => $price_id,
          'quantity' => 1,
        ]],
        'automatic_tax' => [
          'enabled' => option('tristantbg.kirby-stripe.automatic_tax'),
        ],
        'shipping_address_collection' => [
          'allowed_countries' => option('tristantbg.kirby-stripe.allowed_countries'),
        ],
        'shipping_options' => [
          [
            'shipping_rate_data' => $shipping_rate_data
          ]
        ],
        'mode' => 'payment',
        'success_url' => !empty($options['success_url']) ? $options['success_url'] : site()->url(),
        'cancel_url' => !empty($options['cancel_url']) ? $options['cancel_url'] : site()->url(),
      ]);

      go($checkout_session->url);
    } catch (Exception $e) {
      $e = $e->getMessage();
      echo $e;
    }
  }
  public static function getPrices($search = null)
  {
    $stripe_client = Auth::stripeClient();
    $products = $stripe_client->products->all(['limit' => 100])['data'];

    if ($search) {
      $prices = $stripe_client->prices->search(['limit' => 100, 'query' => $search])['data'];
    } else {
      $prices = $stripe_client->prices->all(['limit' => 100])['data'];
    }

    foreach ($prices as $key => $price) {
      if ($product = self::findObjectById($products, $price['product'])) {
        $prices[$key]['product_name'] = $product->name;
        $prices[$key]['price'] = $price->unit_amount / 100 . ' ' . $price->currency;
      }
    }

    return $prices;
  }
  public static function getProducts($search = null)
  {
    $stripe_client = Auth::stripeClient();

    if ($search) {
      $products = $stripe_client->products->search(['limit' => 100, 'query' => $search])['data'];
    } else {
      $products = $stripe_client->products->all(['limit' => 100])['data'];
    }

    return $products;
  }
}
