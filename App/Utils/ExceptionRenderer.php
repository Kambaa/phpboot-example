<?php
namespace App\Utils;

use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionRenderer
{
    /**
     * @param \Exception $e
     * @return Response
     */
    public function render(\Exception $e)
    {
        $message = json_encode(
            ['error' => (new ReflectionClass($e))->getShortName(), 'message' => $e->getMessage()],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        if($e instanceof HttpException){
            return new Response(
                $message,
                $e->getStatusCode(),
                ['Content-Type'=>'application/json']
            );
        } if($e instanceof \InvalidArgumentException){
            return new Response($message, Response::HTTP_BAD_REQUEST, ['Content-Type'=>'application/json']);
        }else{
            return new Response($message, Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type'=>'application/json']);
        }
    }
}