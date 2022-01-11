<?php

namespace Tests\Traits;

use App\Factories\PaymentGateway\Contracts\PaymentGatewayContract;
use App\Factories\PaymentGateway\PaymentGatewayFactory;
use App\Factories\PaymentGateway\PlacetoPayGateway;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;

trait PaymentGatewayFake
{
    public static string $processUrl = 'session/123456/hash';

    protected function fakeInstancePlacetoPay(?string $statusRequest, ?string $statusQuery): void
    {
        $this->app->instance(
            PaymentGatewayContract::class,
            $this->getPTPPaymentFactoryMock($statusRequest, $statusQuery)->make('placetopay')
        );
    }

    protected function getPTPPaymentFactoryMock(?string $statusRequest, ?string $statusQuery): PaymentGatewayFactory
    {
        $ptpLibraryMock = $this->createMock(PlacetoPay::class);
        if ($statusRequest) {
            $ptpLibraryMock->method('request')->willReturn($this->returnRedirectResponse($statusRequest));
        }
        if ($statusQuery) {
            $ptpLibraryMock->method('query')->willReturn($this->returnRedirectInformacion($statusQuery));
        }

        $paymentFactoryMock = $this->getMockBuilder(PaymentGatewayFactory::class)
            ->onlyMethods(['createPlacetoPay'])
            ->getMock();
        $paymentFactoryMock->method('createPlacetoPay')->willReturn(new PlacetoPayGateway($ptpLibraryMock));

        return $paymentFactoryMock;
    }

    private function returnRedirectInformacion(string $status): RedirectInformation
    {
        return new RedirectInformation([
            'requestId' => base_convert(uniqid(), 16, 10),
            'status' => [
                'status' => $status,
                'reason' => 200,
                'message' => 'test',
                'date' => date('c'),
            ],
        ]);
    }

    private function returnRedirectResponse(string $status): RedirectResponse
    {
        $url = ($status == Status::ST_OK) ? config('services.placetopay.url') . self::$processUrl : null;
        return new RedirectResponse([
            'requestId' => base_convert(uniqid(), 16, 10),
            'processUrl' => $url,
            'status' => new Status([
                'status' => $status,
                'message' => '',
            ]),
        ]);
    }
}
