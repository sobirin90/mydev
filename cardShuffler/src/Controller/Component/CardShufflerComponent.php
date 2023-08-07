<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class CardShufflerComponent extends Component
{
    public function shuffleCards(int $numPeople)
    {
        // Define two arrays, one hold for Suits, another for cards number (A, 1, 2, ... 10 is defined as X, J, Q, K)
        $suits = ['S', 'H', 'D', 'C'];
        $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'J', 'Q', 'K'];

        $deck = []; // Declare an array to store the whole deck
        foreach ($suits as $suit) { // First loop the suits
            foreach ($ranks as $rank) { // Then loop the ranks or card number
                $deck[] = $suit . '-' . $rank; // Store the result(card) in the deck array, loop will end at C as suit, and K as rank
            }
        }

        shuffle($deck); // Shuffle the elements in array to emulate random positioning of the cards

        $result = ''; // Define empty result to hold the text output
        $debugOutput = false; // Set to true for debugging the below logic

        do {
            if ($debugOutput) {
                $result .= 'Total card left:'. count($deck) . "\n";
                $result .= 'Total player left:'. $numPeople . "\n";
            }

            // Get the average of number of card should player received, using ceil to emulate first player should have priority in
            // receiving highest amount of card if the average is in float/decimal
            $no_of_cards = ceil(count($deck) / $numPeople);

            if ($debugOutput) {
                $result .= 'Next player will receive ' . $no_of_cards .  " cards.\n";
            }

            // Store cards that about to be handed to the player in the $playercards. This is an array. The elements inside the
            // deck will be reduce from 52
            $playercards = array_splice($deck, 0, intval($no_of_cards));
            if ($debugOutput) {
                $result .= 'Total cards for next player:'. count($playercards) . "\n";
            }

            // Store the list of the cards received by the player and seperated using comma. Ended with notation to start next row,
            // to prepare the result to store for the next player
            $result .= implode(',', $playercards) . "\n";

            // Countdown the number of player so the loop will not be infinite, and to process new average of card that next player
            // set of cards
            $numPeople--;
        }   while (count($deck) > 0 && $numPeople > 0); // Continue loop until number of people and cards inside the deck still available

        if ($debugOutput) {
            $result .= 'Final balance card left(should always be zero):'. count($deck) . "\n";
        }

        // Return the processed result after above loop
        return $result;

    }
}
