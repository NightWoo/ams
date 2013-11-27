<?php
class ManagementSystemController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	/**
     */
    public function actionIndex() {
        $this->render('ManagementSystemHome');
    }

    /**
	 */
	public function actionHome() {
		$chapter = $this->validateIntVal('chapter' , 0);
		$this->render('ManagementSystemHome', array('chapter'=>$chapter));
	}

	public function actionQuality() {
		$view = $this->validateStringVal('view', '');
		$this->render($view);
	}

	public function actionField() {
		$view = $this->validateStringVal('view', '');
		$this->render($view);
	}

	public function actionManpower() {
		$view = $this->validateStringVal('view', '');
		$this->render($view);
	}

	public function actionStandardForm() {
		$this->render('MSStandardForm');
	}

	public function actionWorkSummaryAPD() {
		$this->render('WorkSummaryAPD');
	}
	
	public function actionWorkSummaryCost() {
		$this->render('WorkSummaryCost');
	}
	
	public function actionWorkSummaryManpower() {
		$this->render('WorkSummaryManpower');
	}
    
}
