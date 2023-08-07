<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class CardShufflerComponent extends Component
{
    public function shuffleCards(int $numPeople)
    {
        // Your card shuffling logic here
        // ...
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
        return $result;
    }
}
