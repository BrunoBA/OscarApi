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
        $timestamp = date('Y-m-d H:i:s');
        $fingerPrint = (new QRCode())->render($hash.' - '.$timestamp);
        return $this->twig->render(
            'index.html.twig',
            [
                'oscar' => $oscar,
                'fingerPrint' => $fingerPrint,
                'categories' => $oscar->getCategories(),
            ]
        );
    }
}
