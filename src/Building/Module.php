<?php

namespace Building;

class Module {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                )
            )
        );
    }

    public function getConfig() {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Building' => function($sm) {
            $factory = $sm->get('BrickFactory');
            $building = new Model\Building($factory);
            return $building;
        },
                'ViewFactory' => function($sm) {
            $factory = function($variables = null, $options = null) {
                $viewModel = new \Zend\View\Model\ViewModel($variables, $options);
                return $viewModel;
            };
            return $factory;
        },
                'BrickFactory' => function($sm) {
            $factory = function ($color = null) use ($sm) {
                $brick = new Model\Brick($color);
                return $brick;
            };
            return $factory;
        },
                'Brick' => function($sm) {
            $factory = $sm->get('BrickFactory');
            $model = $factory->__invoke();
            return $model;
        },
            ),
        );
    }

    public function getControllerConfig() {
        return array('factories' => array(
                'Building\Controller\Building' => function ($sm) {
            $building = $sm->getServiceLocator()->get('Building');
            $viewFactory = $sm->getServiceLocator()->get('ViewFactory');
            $controller = new Controller\BuildingController($building, $viewFactory);
            return $controller;
        }
        ));
    }

}
