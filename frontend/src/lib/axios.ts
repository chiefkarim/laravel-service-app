import axios from 'axios';

const api = axios.create({
    //TODO: update this to source form env
    baseURL: 'http://127.0.0.1:8000', // or full URL like http://localhost:8000/api if needed
    withCredentials: true,
    headers: {
        Accept: 'application/json',
    },
});

export default api;
