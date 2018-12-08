<?php
declare(strict_types=1);

namespace JournalMedia\Sample\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use eftec\bladeone\BladeOne as BladeOne;
use JournalMedia\Sample\Http\Utils\ApiRequest;

class TagRiverController
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return new HtmlResponse(
            sprintf("Display the contents of the river for the tag '%s'", $args['tag'])
        );
    }

    public function __construct(ApiRequest $apirequest)
    {

        $views = __DIR__ . '/../../../resources/views';
		$compiledFolder = __DIR__ . '/../../../resources/compiled';
		$this->blade = new BladeOne($views,$compiledFolder,BladeOne::MODE_DEBUG);

		$this->demo = getenv('DEMO_MODE') === "true";

		$this->apirequest = $apirequest;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

    	if( !$this->demo )
    	{

	    	$this->apirequest->setUrl($url = env('API_URL') . 'sample/tag/' . $args['tag']);

			$articles = $this->apirequest->call();

			$articles = $articles->map(function ($article) {
				//print_r($article);
			    return collect($article)
			        ->only(['title', 'excerpt'])
			        ->merge(isset($article["images"]['thumbnail']) ? $article["images"]['thumbnail'] : [])
			        ->all();
			});

	    }
	    else
	    {
	    	$dir_path = __DIR__ . '/../../../resources/demo-responses/';
	    	$file = $args['tag'] . ".json";

    		if (is_file($dir_path.$file))
    		{
    			$this->apirequest->setPath($dir_path.$file);

	    		$articles = $this->apirequest->callLocal();

	    		$articles = $articles->map(function ($article) {

				    return collect($article)
				        ->only(['title', 'excerpt'])
				        ->merge(isset($article["images"]['thumbnail']) ? $article["images"]['thumbnail'] : [])
				        ->all();
				});
    		}

	    }

		return new HtmlResponse( $this->blade->run("articles", ['articles' => $articles]));

    }
}
