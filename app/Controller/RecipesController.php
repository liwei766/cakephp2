<?php

App::uses('CakeEmail', 'Network/Email');

class RecipesController extends AppController {

	public $components = array('RequestHandler');

	public function index() {
		$recipes = $this->Recipe->find('all');
		$this->set(array(
			'recipes' => $recipes,
			'_serialize' => array('recipes')
		));
	}

	public function view($id) {
		$recipe = $this->Recipe->findById($id);
		$this->set(array(
			'recipe' => $recipe,
			'_serialize' => array('recipe')
		));
	}

	public function add() {
		// $this->Recipe->create();
		// if ($this->Recipe->save($this->request->data)) {
		// 	$message = 'Saved';
		// } else {
		// 	$message = 'Error';
		// }
		// $this->set(array(
		// 	'message' => $message,
		// 	'_serialize' => array('message')
		// ));

		$this->Recipe->create();

		if( filter_var($this->request->data["mailAddress"], FILTER_VALIDATE_EMAIL) && ( $this->request->data["status"] ==0 || $this->request->data["status"] ==1 )){
		    if ($this->Recipe->save($this->request->data)) {
				$message = '000';

				try{
					$Email = new CakeEmail('simple');
					$Email->from(array('imadoco6@gmail.com' => 'Imadoco app server'))
					->to($this->request->data["mailAddress"])
					->subject('imadoko info')
					->send($this->request->data["areaName"].( $this->request->data["status"] == 1 ? 'の範囲に入りました。' : 'の範囲から出ました。' ));
				}catch (Exception $e) {
					 $message = '002';
				}

			} else {
				$message = '999';
			}

		} else {
		    $message = '001';
		}

		$this->set(array(
			'message' => $message,
			'_serialize' => array('message')
		));
	}

	public function edit($id) {
		$this->Recipe->id = $id;
		if ($this->Recipe->save($this->request->data)) {
			$message = 'Saved';
		} else {
			$message = 'Error';
		}
		$this->set(array(
			'message' => $message,
			'_serialize' => array('message')
		));
	}

	public function delete($id) {
		if ($this->Recipe->delete($id)) {
			$message = 'Deleted';
		} else {
			$message = 'Error';
		}
		$this->set(array(
			'message' => $message,
			'_serialize' => array('message')
		));
	}
}
