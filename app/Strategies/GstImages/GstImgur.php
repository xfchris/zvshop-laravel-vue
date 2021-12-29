<?php

namespace App\Strategies\GstImages;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;
use Imgur\Client;
use Throwable;
use TypeError;

class GstImgur implements Strategy
{
    public function __construct(array $conexion, Client $client)
    {
        $this->client = $client;
        $this->client->setOption('client_id', $conexion['client_id']);
        $this->client->setOption('client_secret', $conexion['client_secret']);
    }

    public function upload(array $data, string $typeFile = 'image'): ResponseImage
    {
        $result = $this->client->api($typeFile)->upload($data);
        return new ResponseImage($result['id'], $result['link'], ['deletehash' => $result['deletehash']]);
    }

    public function getSize(string $idOrUrl, ?string $size): string
    {
        $hash = preg_replace('@([A-Z0-9]+)(.*)@i', '$1', preg_replace('@http(s)://(i.)?imgur.com/@i', '', $idOrUrl));
        if (!in_array($size, ['s', 'b', 't', 'm', 'l', 'h'])) {
            $size = '';
        }
        return 'https://i.imgur.com/' . $hash . $size . '.jpg';
    }

    public function remove(ResponseImage $responseImage): bool
    {
        $deleteHash = ($responseImage->data) ? $responseImage->data['deletehash'] : $responseImage->id;
        $result = true;

        try {
            $this->client->api('image')->deleteImage($deleteHash);
        } catch (TypeError $th) {
            Log::warning('The image was removed but a type error occurred: ' . $th->getMessage());
            $result = true;
        } catch (ConnectException $e) {
            Log::warning('The image could not be deleted due to a connection error');
            $result = false;
        } catch (Throwable $e) {
            Log::error('The image could not be deleted: ' . $e->getMessage());
            $result = false;
        }
        return $result;
    }
}
