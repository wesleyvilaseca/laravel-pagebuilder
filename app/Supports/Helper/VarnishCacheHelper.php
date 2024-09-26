<?php

namespace App\Supports\Helper;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class VarnishCacheHelper
{
   /**
     * Purga o cache do Varnish para uma URL específica usando o Guzzle.
     *
     * @param string $url URL do Varnish (ou recurso específico) que será purgado
     * @param string $cacheTag Tag do cache a ser purgada (opcional)
     * @return string Resultado da operação de purga
     */
    public function purgeCache($url = 'http://127.0.0.1:6081', $cacheTag = 'c292')
    {
        $client = new Client();

        try {
            $response = $client->request('PURGE', $url, [
                'headers' => [
                    'X-Cache-Tags' => $cacheTag
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                return "Cache purgado com sucesso!";
            }

            throw new Exception("Falha ao purgar o cache. Código de status: " . $response->getStatusCode());
        } catch (RequestException $e) {
            throw new Exception("Erro ao purgar o cache: " . $e->getMessage());
        }
    }
}