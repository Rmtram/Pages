<?php

namespace Rmtram\Pages\System\Controllers;

use Rmtram\SimpleTextDb\Driver\ListDriver;
use Rmtram\SimpleTextDb\Query\Where;
use Rmtram\SimpleTextDb\Util\Unique;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;

/**
 * Class PagesController
 * @package Rmtram\Pages\System\Controllers
 */
class PagesController extends AbstractController implements ResourcesInterface
{

    /**
     * @var ListDriver
     */
    private $page;

    /**
     * @var array
     */
    public $routes = [
        'getIndex'   => 'index',
        'getCreate'  => 'add',
        'postCreate' => 'add',
        'getEdit'    => 'edit/{id}',
        'postEdit'   => 'edit/{id}',
        'getDelete'  => 'delete/{id}',
        'postDemo'   => 'demo'
    ];

    /**
     * @var array
     */
    private $rules = [
        'required'   => [['id'], ['name'], ['description'], ['createdAt'], ['updatedAt']],
        'alphaNum'   => 'id',
        'slug'       => 'name',
        'dateFormat' => [['createdAt', 'Y-m-d H:i:s'], ['updatedAt', 'Y-m-d H:i:s']],
        'lengthMax'  => [['name', 50]]
    ];

    public function initialize(Application $app)
    {
        $connector = $app['connector'];
        $this->page = $connector->connection('pages');
        Validator::lang('ja');
    }

    /**
     * @param Application $app
     * @return Response
     */
    public function getIndex(Application $app)
    {
        return $app->redirect('add');
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getShow(Application $app, Request $request, $id)
    {
        $page = $this->page->find(function(Where $where) use($id) {
            $where->eq('id', $id);
        })->first();
    }

    /**
     * @param Application $app
     * @return Response
     */
    public function getCreate(Application $app)
    {
        return $app['twig']->render('contents/pages/create.twig');
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function postCreate(Application $app, Request $request)
    {
        $now = $this->now();

        $page = array_merge(
            ['id' => Unique::id(), 'createdAt' => $now, 'updatedAt' => $now],
            $request->request->all()
        );

        $validator = new Validator($page);
        $validator->rules($this->rules);

        $fail = function($errors) use($app) {
            $app['twig']->render('pages/create.twig', compact('errors'));
        };

        if (!$validator->validate()) {
            return $fail($validator->errors());
        }

        $existed = $this->page->find(function(Where $where) use($page) {
            $where->eq('name', $page['name']);
        })->exists();

        if (true === $existed) {
            $fail([$page['name'] . 'は既に登録されています']);
        }

        if (!$this->page->add($page)) {
            return $fail(['作成が出来ませんでした']);
        }

        return $app->redirect('index');
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getEdit(Application $app, Request $request, $id)
    {
        $page = $this->page->find(function(Where $where) use($id) {
            $where->eq('id', $id);
        })->first();
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function postEdit(Application $app, Request $request, $id)
    {
        $this->page->update([], function(Where $where) use($id) {
            $where->eq('id', $id);
        });
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getDelete(Application $app, Request $request, $id)
    {
        $this->page->delete(function(Where $where) use($id) {
            $where->eq('id', $id);
        });
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function postDemo(Application $app, Request $request)
    {
        $markdown = new \Parsedown();
        $html = $markdown->parse($request->request->get('description'));
        return new Response($html);
    }
}