<?php

class HumanResourcesController extends BmsBaseController
{
    /**
     * Declares class-based actions.
     */
    public function actions ()
    {
        return array(
        );
    }

    public function actionIndex () {
        $this->render("home", array(""));
    }

    public function actionOrgStructure () {
        $this->render("orgStructure", array(""));
    }

    public function actionPositionSystem () {
        $this->render("positionSystem", array(""));
    }
}