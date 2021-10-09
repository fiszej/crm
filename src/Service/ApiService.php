<?php

namespace App\Service;

use App\Entity\ApiData;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class ApiService 
{

    private const API = 'https://randomuser.me/api/?results=10&nat=us';
    private $client;
    

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    public function getRandomUserFromApi(): ApiData
    {
        $response = $this->client->request('GET', self::API);
        $content = $response->getContent();
        $content = $response->toArray();
        
        $customer = new ApiData();
        $result = $content['results'][0];

        $customer->setName($result['name']['first'].' '.$result['name']['last']);
        $customer->setCity($result['location']['city']);
        $customer->setZipcode($result['location']['postcode']);
        $customer->setAddress($result['location']['street']['name'].' '.$result['location']['street']['number']);
        $customer->setPhone($result['phone']);
        $customer->setEmail($result['email']);
        
        return $customer;
    }

    public function getUsers($quantity = 1)
    {   
        $data = [];
        for ($i=0; $i < $quantity ; $i++) { 
            $data[] = $this->getRandomUserFromApi();
        }
        return $data;
    }
    
}