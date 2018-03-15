<?php
namespace Album\Controller;

use Album\Form\AlbumForm;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
*
*/
class AlbumController extends AbstractActionController
{
	private $table;

	public function __construct(AlbumTable $table)
	{
		$this->table = $table;
	}

	public function indexAction()
	{
		return new ViewModel([
			'albums' => $this->table->fetchAll()
		]);
	}

	public function addAction()
	{
		$form = new AlbumForm();
		$form->get('submit')->setValue('Ajouter');

		$request = $this->getRequest();

		if (!$request->isPost()) {
			return ['form' => $form];
		}

		$album = new Album();
		$form->setInputFilter($album->getInputFilter());
		$form->setData($request->getPost());

		if (! $form->isValid()) {
			return ['form' => $form];
		}

		$album->exchangeArray($form->getData());
		$this->table->saveAlbum($album);
		return $this->redirect()->toRoute('album');
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);

		if (0 === $id) {
			return $this->redirect()->toRoute('album', ['action' => 'add']);
		}

		try {
			$album = $this->table->getAlbum($id);
		} catch (\Exception $e) {
			return $this->redirect()->toRoute('album', ['action' => 'index']);
		}

		$form = new AlbumForm();
		$form->bind($album);
		$form->get('submit')->setAttribute('value', 'Modifier');

		$request = $this->getRequest();
		$viewData = ['id' => $id, 'form' => $form];

		if (! $request->isPost()) {
			return $viewData;
		}

		$form->setInputFilter($album->getInputFilter());
		$form->setData($request->getPost());

		if (! $form->isValid()) {
			return $viewData;
		}

		$this->table->saveAlbum($album);

		return $this->redirect()->toRoute('album', ['action' => 'index']);
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id)
			return $this->redirect()->toRoute('album');

		$request =$this->getRequest();
		if ($request->isPost())
		{
			$del =$request->getPost('del', 'Non');

			if ($del == 'Oui')
			{
				$id = (int) $request->getPost('id');
				$this->table->deleteAlbum($id);
			}

			return $this->redirect()->toRoute('album');
		}

		return[
			'id'    => $id,
			'album' =>$this->table->getAlbum($id)
		];
	}
}