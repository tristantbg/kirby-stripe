# Kirby Stripe

A [Kirby](https://getkirby.com) plugin to connect to [Stripe](https://stripe.com).

## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-stripe`.

### Git submodule

```
git submodule add https://github.com/tristantbg/kirby-stripe.git site/plugins/kirby-stripe
```

### Composer

```
composer require tristantbg/kirby-stripe
```

## Configuration

Add a .env file to the root of your Kirby plugin with the following properties:

| Key                             | Type      |
| ------------------------------- | --------- |
| STRIPE_LIVE_PUBLISHABLE_KEY     | `String`  |
| STRIPE_LIVE_SECRET_KEY          | `String`  |
| STRIPE_TEST_PUBLISHABLE_KEY     | `String`  |
| STRIPE_TEST_SECRET_KEY          | `String`  |

## Kirby config defaults

```
'tristantbg.kirby-stripe' => [
    'test_mode' => false,
    'payment_method_types' => ['card'],
    'automatic_tax' => true,
    'allowed_countries' => [
      'FR',
      'US',
      'CA',
      'GB',
      'IT',
      'ES',
      'DE',
      'MF',
      'PM',
      'RE',
      'MC',
      'MQ',
      'LU',
      'LI',
      'GG',
      'GP',
      'GR',
      'FI',
      'DK',
      'BE'
    ],
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
  ]
```

## Use the plugin

### API KEYS

In order for the plugin to work, you need to get your keys here [https://dashboard.stripe.com/apikeys](https://dashboard.stripe.com/apikeys)

> **NOTE:** This plugin includes a .env.example file as well.

### METHODS

Use Stripe PHP client for custom queries

```
$stripe_client = KirbyStripe\Auth::stripeClient();
$products = $stripe_client->products->all(['limit' => 100])['data'];
```


Gets all prices

```
KirbyStripe\Methods::getPrices($search = 'optional query')
```

Gets all products
```
KirbyStripe\Methods::getProducts($search = 'optional query')
```

Queries uses Stripe query language:

Example:

```
$search = "active:'true' AND name~'name of product'"
```

https://stripe.com/docs/search#search-query-language

### ROUTES ENDPOINTS

```
https://site.url/sck/api/prices
```

```
https://site.url/sck/api/products
```

```
https://site.url/sck/checkout/{{kirbyProductPageUid}}
```

```
https://site.url/sck/checkout/price/{{stripePriceId}}
```

### PRICE_ID KIRBY FIELD

To easily connect a Kirby page to a Stripe price_id of a product:

```
stripePriceID:
  extends: fields/stripe-price-id
```

## Plugin Development

[Kirbyup](https://github.com/johannschopplich/kirbyup) is used for the development and build setup.

Kirbyup will be fetched remotely with your first `npm run` command, which may take a short amount of time.

### Development

Start the dev process with:

```
npm run dev
```

This will automatically update the `index.js` and `index.css` of the plugin as soon as changes are made.
Reload the Panel to see the code changes reflected.

### Production

Build final files with:

```
npm run build
```

This will automatically create a minified and optimized version of the `index.js` and `index.css`.

## License

MIT
