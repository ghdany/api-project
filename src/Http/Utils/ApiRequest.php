<?php

namespace JournalMedia\Sample\Http\Utils;

use GuzzleHttp\Client as HttpClient;

class ApiRequest
{

	private $client;

	private $data;


    public function __construct()
    {

        $this->client = new HttpClient();

        $this->data = collect();

    }


    public function setUrl($url)
    {
    	$this->url = $url;
    }


    public function setPath($path)
    {
    	$this->path = $path;
    }


    public function call()
    {

    	try
    	{

	    	$res = $this->client->get( $this->url, ['auth' =>  [env('API_USERNAME'), env('API_PASSWORD')]]);

			$this->data = json_decode($res->getBody()->getContents(), true)["response"]["articles"];

			return collect($this->data);

		}
		catch (\Exception $e)
		{
            $status = $e->getCode();
            $message = $e->getMessage();

            if ($status == 429) {
                sleep(5);
                return $this->call();
            }

            throw new \Exception($message);

        }

    }

    public function callLocal()
    {

    	try
    	{

    		$this->data = json_decode(file_get_contents($this->path), true);

    		return collect($this->data);

    	}
    	catch (\Exception $e)
		{

            $status = $e->getCode();
            $message = $e->getMessage();

            throw new \Exception($message);

        }

    }

}
