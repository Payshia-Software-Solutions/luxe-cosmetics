<?php
class PaymentModel
{
    private $merchant_id;
    private $merchant_secret;


    public function __construct($merchant_id, $merchant_secret)
    {
        $this->merchant_id = $merchant_id;
        $this->merchant_secret = $merchant_secret;
    }

    public function generateHash($order_id, $amount, $currency)
    {
        $hashed_secret = strtoupper(md5($this->merchant_secret));
        return strtoupper(md5($this->merchant_id . $order_id . $amount . $currency . $hashed_secret));
    }

    public function prepareFormData($data)
    {
        $hash = $this->generateHash($data['order_id'], $data['amount'], $data['currency']);
        return array_merge($data, ['hash' => $hash, 'merchant_id' => $this->merchant_id]);
    }
}
