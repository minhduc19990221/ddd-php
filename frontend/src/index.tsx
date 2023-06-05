import React from 'react';
import ReactDOM from 'react-dom/client';
import axios from 'axios';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import './index.css';
import SignIn from './pages/SignIn';
import reportWebVitals from './reportWebVitals';
import SignUp from './pages/SignUp';
import Profile from './pages/Profile';


const root = document.getElementById('root');
if (root) {
  // Setting default headers to avoid CORS errors
  axios.defaults.headers.common['Authorization'] = localStorage.length > 1 && localStorage.getItem("token") ? 'Bearer ' + localStorage.getItem("token") : '';
  axios.defaults.headers.common['Access-Control-Allow-Origin'] = "*";
  axios.defaults.headers.common['Access-Control-Allow-Methods'] = "DELETE, POST, GET, PUT";
  axios.defaults.headers.common['Access-Control-Allow-Headers'] = "Content-Type, Authorization, X-Requested-With, Access"
  axios.defaults.headers.common['Content-Type'] = 'application/json';
  const rootElement = ReactDOM.createRoot(root);
  rootElement.render(
    <React.StrictMode>
     <BrowserRouter>
      <Routes>
        <Route path="/signin" element={<SignIn />} />
        <Route path="/signup" element={<SignUp />} />
        <Route path="/profile" element={<Profile />} />
        {/* <Route path="/contact" element={<Contact />} /> */}
      </Routes>
    </BrowserRouter>
    </React.StrictMode>
  );
}

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
