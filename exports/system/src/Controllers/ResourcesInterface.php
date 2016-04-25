<?php

namespace Rmtram\Pages\System\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResourcesInterface
 * @package Rmtram\Pages\System\Controllers
 */
interface ResourcesInterface
{
    /**
     * @param Application $app
     * @return Response
     */
    public function getIndex(Application $app);

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getShow(Application $app, Request $request, $id);

    /**
     * @param Application $app
     * @return Response
     */
    public function getCreate(Application $app);

    /**
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function postCreate(Application $app, Request $request);

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getEdit(Application $app, Request $request, $id);

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function postEdit(Application $app, Request $request, $id);

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getDelete(Application $app, Request $request, $id);

}