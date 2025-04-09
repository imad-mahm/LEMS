// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-analytics.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyALKkpq3ZX2B3W193PedJk1zWadZAAumzs",
  authDomain: "lems-se.firebaseapp.com",
  projectId: "lems-se",
  storageBucket: "lems-se.firebasestorage.app",
  messagingSenderId: "611708679572",
  appId: "1:611708679572:web:d87e76d2a51ef258190b81",
  measurementId: "G-H80JG8VXQW",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

export { app };
