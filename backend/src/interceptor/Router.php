<?php

namespace D002834\Backend\interceptor;

use D002834\Backend\interceptor\IRequest;

class Router
{
    private IRequest $request;
    private array $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    function __call($name, $args): void
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    private function formatRoute($route): string
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler(): void
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler(): void
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    function resolve(): void
    {
        if (!empty($this->request->requestMethod)) {
            $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        } else {
            $methodDictionary = $this->{strtolower('GET')};
        }
        if (!empty($this->request->requestUri)) {
            $formatedRoute = $this->formatRoute($this->request->requestUri);
        } else {
            $formatedRoute = '/';
        }
        $method = $methodDictionary[$formatedRoute];
        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array($this->request));
    }

    function __destruct()
    {
        $this->resolve();
    }
}