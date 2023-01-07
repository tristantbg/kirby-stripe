<?php

namespace KirbyStripe;

class Auth
{
    public static function stripeClient()
    {
        // Authentication setup
        if (option('tristantbg.kirby-stripe.test_mode', false) === true) {
            $stripe_client = new \Stripe\StripeClient(
                $_ENV['STRIPE_TEST_SECRET_KEY']
            );
        } else {
            $stripe_client = new \Stripe\StripeClient(
                $_ENV['STRIPE_LIVE_SECRET_KEY']
            );
        }
        return $stripe_client;
    }
}
