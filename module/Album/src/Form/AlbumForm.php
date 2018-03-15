<?php
namespace Album\Form;

use Zend\Form\Form;

/**
*
*/
class AlbumForm extends Form
{

	public function __construct($name = null)
	{
		parent::__construct('album');

		$this->add([
			'name' => 'id',
			'type' => 'hidden',
		]);
		$this->add([
			'name' => 'title',
			'type' => 'text',
			'options' => [
				'label' => 'Titre',
			],
		]);
		$this->add([
			'name' => 'artist',
			'type' => 'text',
			'options' => [
				'label' => 'Artiste',
			],
		]);
		$this->add([
			'name' => 'submit',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Ajouter',
				'id'    => 'submitbutton',
			],
		]);
	}
}

 ?>