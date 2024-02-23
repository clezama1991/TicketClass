<?php

if (!function_exists('money')) {
    /**
     * Format a given amount to the given currency
     *
     * @param $amount
     * @param \App\Models\Currency $currency
     * @return string
     */
    function money($amount, \App\Models\Currency $currency)
    {
        return $currency->symbol_left . number_format($amount, $currency->decimal_place, $currency->decimal_point,
            $currency->thousand_point) . $currency->symbol_right;
    }
}
if (!function_exists('format_money')) {
    /**
     * Format a given amount to the given currency
     *
     * @param $amount
     * @param \App\Models\Currency $currency
     * @return string
     */
    function format_money($amount,  $currency)
    {
        $currency = \App\Models\Currency::where('id',$currency)->first();
        return $currency->symbol_left . number_format($amount, $currency->decimal_place, $currency->decimal_point,
            $currency->thousand_point) . $currency->symbol_right;
    }
}

if (!function_exists('payment_methods')) {

    function payment_methods()
    {
        $methods = [
            [
                'id' => 'cash',
                'name' => 'Efectivo',
            ],
            [
                'id' => 'card',
                'name' => 'Tarjeta',
            ],
            [
                'id' => 'free',
                'name' => 'Cortesia',
            ]
        ];
        return $methods;
    }
}


