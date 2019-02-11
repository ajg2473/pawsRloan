<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/15/18
 * Time: 2:44 PM
 */

namespace Bolzen\Core\Request;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Bolzen\Core\Config\ConfigInterface;
use Symfony\Component\HttpFoundation;

class Request
{
    private $request;
    private $container;
    private $path;
    private $config;

    /**
     * Request constructor.
     * @param HttpFoundation\Request $request
     * @param ContainerBuilder $container
     * @param ConfigInterface $config
     */
    public function __construct(HttpFoundation\Request $request, ContainerBuilder $container, ConfigInterface $config)
    {
        $this->request = $request;
        $this->container = $container;
        $this->config = $config;
        $this->path = $this->setPath();
    }

    /**
     * @return bool|mixed|null|string|string[]
     */
    private function setPath()
    {
        $projectDir = $this->config->getProjectDirectory();
        $path = strtolower($this->request->getPathInfo());
        $path = $this->removeExtension($path);
        $path = $this->urlSlashesFormat($path);

        //need to redirect to index page?
        if ($path==="/" || $path === "" || $path==="/".$projectDir."/" || $path==="/$projectDir") {
            $path = "/".$projectDir."/index";
        }
        return $path;
    }

    /**
     * @param $path remove extension from the website
     * @return mixed
     */
    private function removeExtension($path)
    {
        return str_replace(".php", "", $path);
    }

    /**
     * @param $url
     * @return bool|null|string|string[]
     */
    private function urlSlashesFormat($url)
    {
        //remove additional slashes
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);

        //remove the last /
        if (substr($url, -1)==="/") {
            $url = substr($url, 0, strlen($url)-1);
        }
        return $url;
    }

    /**
     * Set all url for query, request, attributes, cookies, files, server, and getContent for the website
     */
    private function rewriteRequest()
    {
        $this->request->server->set('REQUEST_URI', $this->path);
        $this->request->initialize(
            $this->request->query->all(),
            $this->request->request->all(),
            $this->request->attributes->all(),
            $this->request->cookies->all(),
            $this->request->files->all(),
            $this->request->server->all(),
            $this->request->getContent()
        );
    }

    /**
     * @return HttpFoundation\Request
     */
    public function getFormattedRequest(): HttpFoundation\Request
    {
        $this->rewriteRequest();
        return $this->request;
    }

}