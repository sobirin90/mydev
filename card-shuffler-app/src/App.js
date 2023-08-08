import React, { useState } from 'react';
import {getCards} from './api';

function App() {
  const [numPeople, setNumPeople] = useState(1);
  const [cards, setCards] = useState('');

  const handleNumPeopleChange = (event) => {
    setNumPeople(event.target.value);
  };

  const handleDistributeClick = async () => {
    try {
      const cardsData = await getCards(numPeople);
      setCards(cardsData);
    } catch (error) {
      console.error('Error distributing cards:', error);
    }
  };

  return (
    <div className="container mt-5">
      <h1 className="text-center">Cards Shuffler</h1>
      <div className="row justify-content-center">
        <div className="col-md-6">
          <div className="form-group">
            <label htmlFor="numPeople">Number of People</label>
            <input
              type="number"
              className="form-control"
              id="numPeople"
              min="1"
              value={numPeople}
              onChange={handleNumPeopleChange}
              placeholder="Enter the number of people"
            />
          </div>
          <button
            className="btn btn-primary"
            onClick={handleDistributeClick}
          >
            Shuffle Cards
          </button>
        </div>
      </div>
      <div className="row mt-3">
        <div className="col-md-6">
          <h2>Received Cards:</h2>
          <pre>{cards}</pre>
        </div>
      </div>
    </div>
  );
}

export default App;
