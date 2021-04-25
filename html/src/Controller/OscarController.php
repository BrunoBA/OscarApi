<?php

namespace OscarApi\Controller;

use Dompdf\Dompdf;
use Oscar\Oscar;
use OscarApi\Model\CategoryWinnerDecrypt;
use OscarApi\Model\OscarCategoryParser;
use OscarApi\TemplateBuilder;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class OscarController extends AbstractController
{

    public function show(Request $request, Response $response, $args): Response
    {
        $queryString = $request->getQueryParams();
        $hash = $args['hash'];

        $render = in_array("render", array_keys($queryString));
        $categoriesWinner = (new CategoryWinnerDecrypt($hash))->decrypt();
        $oscarData = new OscarCategoryParser($categoriesWinner);
        $oscarData->attachChoices();

        $templateBuilder = new TemplateBuilder();

        $dompdf = new Dompdf();
        $html = $templateBuilder->renderBets($oscarData->getOscar(), $hash);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4');

        $dompdf->render();
        if ($render) {
            $dompdf->stream("Café com Nerdeza - Oscar" . $templateBuilder->timestamp);
        } else {
            $response->getBody()->write($html);
        }

        return $response;
    }

    public function index(Request $request, Response $response, $args): Response
    {
        $oscar = new Oscar();
        $jsonOscar = $oscar->toJson();
        $response->getBody()->write($jsonOscar);

        return $response;
    }

    function store(Request $request, Response $response, $args): Response
    {

        $data = $request->getBody()->getContents();

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
        $converter = new CamelCaseToSnakeCaseNameConverter();
        $normalizers = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(null, $converter, null, $extractor),
        ];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        /** @var OscarCategoryParser $oscarData */
        $oscarData = $serializer->deserialize(
            $data,
            OscarCategoryParser::class,
            'json'
        );

        $oscarData->attachChoices();

        $response->getBody()->write($oscarData->parseToHash());

        return $response;
    }
}
