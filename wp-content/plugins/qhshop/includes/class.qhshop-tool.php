<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class QHShopTool
{
    protected static $instance;

    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new QHShopTool();
        }

        return self::$instance;
    }

    public static function get_currency($number, $args=array())
    {
        $result = '';
        $currency_symbol = isset($args['currency_symbol']) ? $args['currency_symbol'] : '$';
        $currency_position = isset($args['currency_position']) ? $args['currency_position'] : 0;
        $thousand_separator = isset($args['thousand_separator']) ? $args['thousand_separator'] : ',';
        $decimal_separator = isset($args['decimal_separator']) ? $args['decimal_separator'] : '.';
        $number_of_decimals = isset($args['number_of_decimals']) ? $args['number_of_decimals'] : 2;

        switch ($currency_position) {
            case 0:
                $result = sprintf('%s%s', $currency_symbol, number_format($number,$number_of_decimals, $decimal_separator, $thousand_separator));
                break;
            case 1:
                $result = sprintf('%s%s',number_format($number,$number_of_decimals, $decimal_separator, $thousand_separator), $currency_symbol);
                break;
        }

        return $result;
    }
}