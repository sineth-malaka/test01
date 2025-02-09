{// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyBAFpj-naz-Hae1sVmp0dPwadaj-JuSbjY",
  authDomain: "passwordsave1998.firebaseapp.com",
  projectId: "passwordsave1998",
  storageBucket: "passwordsave1998.firebasestorage.app",
  messagingSenderId: "652382632689",
  appId: "1:652382632689:web:5a0e7a9a617c8a29e50750",
  measurementId: "G-89BX9Z59K7"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
}
