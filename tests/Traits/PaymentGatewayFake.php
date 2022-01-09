<?php

namespace Tests\Traits;

use App\Factories\PaymentGateway\PaymentGateway;
use App\Factories\PaymentGateway\PaymentGatewayFactory;
use App\Factories\PaymentGateway\PlacetoPayGateway;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;

trait PaymentGatewayFake
{
    protected function fakeInstancePlacetoPay(?string $statusRequest, ?string $statusQuery, bool $isSuccessful = true): void
    {
        $this->app->instance(
            PaymentGateway::class,
            $this->getPTPPaymentFactoryMock($statusRequest, $statusQuery, $isSuccessful)->make('placetopay')
        );
    }

    private function getPTPPaymentFactoryMock(?string $statusRequest, ?string $statusQuery, bool $isSuccessful): PaymentGatewayFactory
    {
        $ptpLibraryMock = $this->createMock(PlacetoPay::class);
        //->disableOriginalConstructor()
        //->onlyMethods(['request', 'query'])
        //>getMock();
        $ptpLibraryMock->method('request')->willReturn($this->returnRedirectResponse($statusRequest));
        $ptpLibraryMock->method('query')->willReturn($this->returnRedirectInformacion($statusQuery));
        $ptpLibraryMock->method('isSuccessful')->willReturn($isSuccessful);

        $paymentFactoryMock = $this->getMockBuilder(PaymentGatewayFactory::class)
            ->onlyMethods(['createPlacetoPay'])
            ->getMock();
        $paymentFactoryMock->method('createPlacetoPay')->willReturn(new PlacetoPayGateway($ptpLibraryMock));

        return $paymentFactoryMock;
    }

    private function returnRedirectInformacion(string $status = 'CREATED'): RedirectInformation
    {
        return new RedirectInformation([
            'requestId' => 12345,
            'status' => [
                'status' => $status,
                'reason' => 200,
                'message' => 'test',
                'date' => date('Y-m-d\TH:i:s-05:00'),
            ],
        ]);
    }

    private function returnRedirectResponse(string $status): RedirectResponse
    {
        return new RedirectResponse([
            'requestId' => 123456,
            'processUrl' => config('services.placetopay.url') . 'session/123456/un_hash_largisimo',
            'status' => $status,
        ]);
    }
}
