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

### API KEYS

In order for the plugin to work, you need to get your keys here [https://dashboard.stripe.com/apikeys](https://dashboard.stripe.com/apikeys)

> **NOTE:** This plugin includes a .env.example file as well.

### METHODS

```
KirbyStripe\getPrices($search = 'optional query')
```
Gets all prices

```
KirbyStripe\getProducts($search = "active:'true' AND name~'name of product'")
```
Gets all products
https://stripe.com/docs/search#search-query-language

### ENDPOINTS

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
