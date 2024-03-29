<?php

@include_once __DIR__ . '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Kirby::plugin('tristantbg/kirby-stripe', [
  'options' => [
    'test_mode' => false,
    'payment_method_types' => ['card'],
    'automatic_tax' => true,
    'allowed_countries' => ['AD', 'AL', 'AM', 'AT', 'AZ', 'BA', 'BE', 'BG', 'BY', 'CA', 'CH', 'CN', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FO', 'FR', 'GB', 'GE', 'GG', 'GI', 'GR', 'HR', 'HU', 'IE', 'IM', 'IS', 'IT', 'JP', 'KR', 'KZ', 'LI', 'LT', 'LU', 'LV', 'MC', 'MD', 'ME', 'MK', 'MQ', 'MT', 'MX', 'NL', 'NO', 'PM', 'PL', 'PT', 'RE', 'RO', 'RS', 'RU', 'SE', 'SI', 'SK', 'SM', 'UA', 'US', 'XK'],
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
