import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://localhost:8765', // Replace with your backend URL
  withCredentials: true, // Allow sending cookies
});

export const getCards = async (numPeople) => {
  try {
    const response = await instance.get(`/cardShuffler?numPeople=${numPeople}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};