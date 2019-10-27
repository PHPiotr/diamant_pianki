<?php

namespace App\Controller;

use Google_Client;
use Google_Service_Analytics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GoogleController extends AbstractController
{
    public function getAccessToken()
    {
        putenv(sprintf('GOOGLE_APPLICATION_CREDENTIALS=%s', $this->getParameter('google_application_credentials')));

        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
        $client->fetchAccessTokenWithAssertion();

        return $this->json($client->getAccessToken());
    }
}