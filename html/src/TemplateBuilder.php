<?php


namespace OscarApi;

use chillerlan\QRCode\QRCode;
use Oscar\Oscar;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TemplateBuilder
{
    /** @var Environment */
    private Environment $twig;

    /** @var FilesystemLoader */
    private FilesystemLoader $loader;
    public string $timestamp = "";

    public function __construct()
    {
        $path = dirname(__DIR__, 1);
        $this->loader = new FilesystemLoader($path.'/templates/');
        $this->twig = new Environment($this->loader);
    }

    /**
     * @param string $path
     * @param array $context
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $path, array $context = []): string
    {
        return $this->twig->render($path, $context);
    }

    /**
     * @param Oscar $oscar
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderBets(Oscar $oscar, string $hash): string
    {
        $this->timestamp = date('Y-m-d H:i:s');
        $hashData = $hash.' - '.$this->timestamp;
        $fingerPrint = (new QRCode())->render($hashData);

        $mainCategory = $oscar->getCategories()[0];
        $others = array_chunk(array_slice($oscar->getCategories(), 1), 2);

        return $this->twig->render(
            'index.html.twig',
            [
                'oscar' => $oscar,
                'fingerPrint' => $fingerPrint,
                'categories' => $oscar->getCategories(),
                'mainCategory' => $mainCategory,
                'others' => $others,
                'hashData' => $hashData,
                'timestamp' => $this->timestamp
            ]
        );
    }
}
