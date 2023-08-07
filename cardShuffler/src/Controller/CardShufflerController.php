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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->autoRender = false;

        $numPeople = $this->request->getQuery('numPeople');

        if (!is_numeric($numPeople) || $numPeople < 0) {
            $this->response = $this->response->withStringBody('Input value does not exist or value is invalid');
            return $this->response;
        }

        if ($numPeople > 53) {
            $this->response = $this->response->withStringBody('Irregularity occurred');
            return $this->response;
        }

        $suits = ['S', 'H', 'D', 'C'];
        $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'J', 'Q', 'K'];

        $deck = [];
        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $deck[] = $suit . '-' . $rank;
            }
        }

        shuffle($deck);

        $result = '';
        $debugOutput = false; // Set to true for debugging

        do {
            if ($debugOutput) {
                $result .= 'Total card left:'. count($deck) . "\n";
                $result .= 'Total player left:'. $numPeople . "\n";
            }

            $no_of_cards = ceil(count($deck) / $numPeople);

            if ($debugOutput) {
                $result .= 'Next player will receive ' . $no_of_cards .  " cards.\n";
            }


            $playercards = array_splice($deck, 0, intval($no_of_cards));
            if ($debugOutput) {
                $result .= 'Total cards for next player:'. count($playercards) . "\n";
            }
            $result .= implode(',', $playercards) . "\n";
            $numPeople--;
        }   while (count($deck) > 0 && $numPeople > 0);

        if ($debugOutput) {
            $result .= 'Final balance card left(should always be zero):'. count($deck) . "\n";
        }

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
