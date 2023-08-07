<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CardShuffler Controller
 *
 * @method \App\Model\Entity\CardShuffler[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CardShufflerController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('CardShuffler'); // Make sure the alias matches
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->autoRender = false; //Disable the view function of Cake, because this function is API

        $numPeople = $this->request->getQuery('numPeople'); //Get input number of people from GET request.

        //Validating input

        if (!is_numeric($numPeople) || $numPeople < 0) {
            $this->response = $this->response->withStringBody('Input value does not exist or value is invalid');
            return $this->response;
        }

        if ($numPeople > 53) {
            $this->response = $this->response->withStringBody('Irregularity occurred');
            return $this->response;
        }

        // Load the CardShuffler component that holds the logic of output
        $this->loadComponent('CardShuffler');

        // Storing output in result variable, run the method shuffleCards at the component to get the output
        $result = $this->CardShuffler->shuffleCards((int)$numPeople);

        // Process the output as text, and return
        $this->response = $this->response->withType('text/plain');
        $this->response = $this->response->withStringBody($result);
        return $this->response;
    }

    /**
     * View method
     *
     * @param string|null $id Card Shuffler id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cardShuffler = $this->CardShuffler->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('cardShuffler'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cardShuffler = $this->CardShuffler->newEmptyEntity();
        if ($this->request->is('post')) {
            $cardShuffler = $this->CardShuffler->patchEntity($cardShuffler, $this->request->getData());
            if ($this->CardShuffler->save($cardShuffler)) {
                $this->Flash->success(__('The card shuffler has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The card shuffler could not be saved. Please, try again.'));
        }
        $this->set(compact('cardShuffler'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Card Shuffler id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cardShuffler = $this->CardShuffler->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cardShuffler = $this->CardShuffler->patchEntity($cardShuffler, $this->request->getData());
            if ($this->CardShuffler->save($cardShuffler)) {
                $this->Flash->success(__('The card shuffler has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The card shuffler could not be saved. Please, try again.'));
        }
        $this->set(compact('cardShuffler'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Card Shuffler id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cardShuffler = $this->CardShuffler->get($id);
        if ($this->CardShuffler->delete($cardShuffler)) {
            $this->Flash->success(__('The card shuffler has been deleted.'));
        } else {
            $this->Flash->error(__('The card shuffler could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
