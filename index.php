<?php

@include_once __DIR__ . '/vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

Kirby::plugin('tristantbg/kirby-stripe', [
  'options' => [
    'test_mode' => false,
    'payment_method_types' => ['card'],
    'automatic_tax' => true,
    'allowed_countries' => ['AL', 'AD', 'AM', 'AT', 'AZ', 'BY', 'BE', 'BA', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE',  'FO', 'FI', 'FR', 'GE', 'DE', 'GI', 'GR', 'HU', 'IS', 'IE', 'IM', 'IT', 'KZ', 'XK', 'LV', 'LI', 'LT', 'LU', 'MT', 'MD', 'MC', 'ME', 'NL', 'MK', 'NO', 'PL', 'PT', 'RO', 'RU', 'SM', 'RS', 'SK', 'SI', 'ES', 'SE', 'CH', 'UA', 'GB', 'MX', 'CA', 'US', 'JP', 'KR'],
    'shipping_rate_data' => [
      'type' => 'fixed_amount',
      'tax_behavior' => 'inclusive',
      'fixed_amount' => [
        // Amount*100 = 5€ => 500
        'amount' => 500,
        'currency' => 'eur',
      ],
      'display_name' => 'Standard shipping',
      // Delivers between 5-7 business days
      'delivery_estimate' => [
        'minimum' => [
          'unit' => 'business_day',
          'value' => 5,
        ],
        'maximum' => [
          'unit' => 'business_day',
          'value' => 7,
        ],
      ]
    ]
  ],
  'translations' => [
    'en' => [
      'field.blocks.stripe-product.select' => 'Select a product'
    ]
  ],
  'blueprints' => [
    'fields/stripe-price-id' => __DIR__ . '/blueprints/fields/stripe-price-id.yml'
  ],
  'routes' => [
    [
      'pattern' => 'sck/checkout/(:any)',
      'action'  => function ($uid) {
        $product = site()->pages()->findBy('intendedTemplate', 'products')->children()->findBy('uid', $uid);
        if ($product && $product->stripePriceID()->isNotEmpty()) {
          $options = [
            'success_url' => $product->url(),
            'cancel_url' => $product->url()
          ];
          if ($product->stripeShippingRateAmount()->isNotEmpty()) $options['shipping_rate_amount'] = $product->stripeShippingRateAmount()->toFloat() * 100;
          KirbyStripe\Methods::checkoutPriceID($product->stripePriceID(), $options);
        }
      }
    ],
    [
      'pattern' => 'sck/checkout/price/(:any)',
      'action'  => function ($priceID) {
        KirbyStripe\Methods::checkoutPriceID($priceID);
      }
    ],
    [
      'pattern' => 'sck/api/prices',
      'action'  => function () {
        return [
          'status' => 'success',
          'data' => KirbyStripe\Methods::getPrices()
        ];
      }
    ],
    [
      'pattern' => 'sck/api/products',
      'action'  => function () {
        return [
          'status' => 'success',
          'data' => KirbyStripe\Methods::getProducts()
        ];
      }
    ],
  ]
]);
