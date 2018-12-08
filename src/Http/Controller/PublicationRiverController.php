<?php
declare(strict_types=1);

namespace JournalMedia\Sample\Http\Controller;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use eftec\bladeone\BladeOne as BladeOne;
use JournalMedia\Sample\Http\Utils\ApiRequest;


class PublicationRiverController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            sprintf("Demo Mode: %s", getenv('DEMO_MODE') === "true" ? "ON" : "OFF")
        );
    }

    public function __construct(ApiRequest $apirequest)
    {

        $views = __DIR__ . '/../../../resources/views';
		$compiledFolder = __DIR__ . '/../../../resources/compiled';
		$this->blade = new BladeOne($views, $compiledFolder, BladeOne::MODE_DEBUG);

		$this->demo = getenv('DEMO_MODE') === "true";

		$this->apirequest = $apirequest;
    }

    public function index(): ResponseInterface
    {

    	if( !$this->demo )
    	{

	    	$this->apirequest->setUrl(env('API_URL') . 'sample/thejournal');

			$articles = $this->apirequest->call();

	    }
	    else
	    {
	    	$dir_path = __DIR__ . '/../../../resources/demo-responses/';
	    	$json_files = [
	    		"thejournal.json",
	    		"apple.json",
	    		"google.json"
	    	];

	    	foreach ($json_files as $file) {

	    		if (is_file($dir_path.$file))
	    		{

	    			$this->apirequest->setPath($dir_path.$file);

	    			$articles = isset($articles) ? $articles->merge( $this->apirequest->callLocal() ) : $this->apirequest->callLocal();

	    		}

	    	}

	    }

	    $articles = $articles->map(function ($article) {
		    return collect($article)
		        ->only(['title', 'excerpt'])
		        ->merge(isset($article["images"]['thumbnail']) ? $article["images"]['thumbnail'] : [])
		        ->all();
		});

		return new HtmlResponse( $this->blade->run("articles", ['articles' => $articles]));

    }
}
