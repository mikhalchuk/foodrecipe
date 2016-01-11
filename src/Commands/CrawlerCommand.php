<?php
namespace FoodRecipe\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DomCrawler\Crawler;

class CrawlerCommand extends Command
{
    protected function configure()
    {
        $this->setName('crawler')->setDescription('Runs crawler');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \FoodRecipe\System\ContainerAwareApplication $app */
        $app = $this->getApplication();

        print_r($app->getContainer()->keys());
        die;

        $httpClient = null;

        /** @var \Elasticsearch\Client $searchClient */
        $searchClient = $app->getService('searchClient');

        $httpHeaders = [
            'Accept' => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            'Accept-Encoding' => 'gzip, deflate, sdch',
            'Cache-Control' => 'max-age=0',
            'Connection' => 'keep-alive',
            'Accept-Language' => 'en-US',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0',
        ];
        $httpOptions = [
            'timeout' => 5,
        ];

        $uri = '/view.php?g=';
        $i = 0;
        foreach (range(1, 45) as $number) {
            $recipesHtml = $httpClient->get($uri . $number, $httpHeaders, $httpOptions)->send()->getBody(true);

            $list = [];
            try {
                $list = (new Crawler($recipesHtml))->filterXPath(
                    '//*[@id="content"]/span[1]/table/tr[*]/td[position() = 2 or position() = 4]/a'
                )->each(function (Crawler $node, $i) {
                    return $node->attr('href');
                });

                if (!empty($list)) {
                    foreach ($list as $item) {
                        $recipeHtml = $httpClient->get($item, $httpHeaders, $httpOptions)->send()->getBody(true);
                        try {
                            $recipeCrawler = new Crawler($recipeHtml);

                            $rawIngredients = $recipeCrawler->filterXPath(
                                '//*[@id="content"]/div[2]/ul[1]/li[(contains(@class, "ingredient"))]'
                            )->each(function (Crawler $node, $i) {
                                return $node->text();
                            });

                            $ingredients = [];
                            foreach ($rawIngredients as $ingredient) {
                                $matches = [];
                                if (preg_match('/(.*) - (.*)/', $ingredient, $matches) === 1) {
                                    $ingredients[] = $matches[1];
                                } else {
                                    $ingredients[] = $ingredient;
                                }
                            }
                            unset($ingredient);

                            $recipe = [
                                'title' => $recipeCrawler->filterXPath('//*[@id="content"]/div[2]/h1/a')->text(),
                                'ingredients' => $ingredients,
                                'raw' => $rawIngredients,
                            ];

                            $data = $searchClient->index([
                                'index' => 'recipes',
                                'type' => 'recipe',
                                'id' => ++$i,
                                'body' => $recipe,
                            ]);
                        } catch (\InvalidArgumentException $e) {
                            continue;
                        }
                    }
                }

            } catch (\InvalidArgumentException $e) {
                continue;
            }
        }
    }
}
