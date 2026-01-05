<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoService
{
	public function __construct()
	{
		MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));
	}

	public function criarPreferencia($doacao)
	{
		try {
			$client = new PreferenceClient();
			$baseUrl = env('MERCADOPAGO_WEBHOOK');


			$preference = $client->create([
				"items" => [
					[
						"title" => "Doação APREPI",
						"quantity" => 1,
						"unit_price" => (float) $doacao->valor,
						"currency_id" => "BRL"
					]
				],
				"payment_methods" => [
					"excluded_payment_types" => [],
					"default_payment_method_id" => null 
				],
				 "back_urls" => [
					"success" => "{$baseUrl}/mercadopago/success",
					"failure" => "{$baseUrl}/mercadopago/failure",
					"pending" => "{$baseUrl}/mercadopago/pending",
				],

				"auto_return" => "approved",
				"external_reference" => (string) $doacao->id,
				"notification_url" => env('MERCADOPAGO_WEBHOOK'),
			]);

		

			return $preference->init_point;
		} catch (MPApiException $e) {
			dd('Erro Mercado Pago API:', $e->getApiResponse()->getContent());
		} catch (\Exception $e) {
			dd('Erro geral Mercado Pago:', $e->getMessage());
		}
	}
}
