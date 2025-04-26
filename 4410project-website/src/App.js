import {BrowserRouter as Router, Routes, Route} from "react-router-dom";
import Navbar from './components/Navbar.js'
import Welcome from './components/Welcome.js'
import Login from './components/Login.js'
import AllBooks from './components/AllBooks.js'
import AdminDashboard from "./components/Admin/AdminDashboard.js";
import './App.css';

function App() {


  return (
    <div>
      <h1 className="header-section">Library Management System</h1>
    <Router>
      <Navbar />
      <Routes>
        <Route path="/" element={<Welcome />} />
        <Route path="books" element={<AllBooks />} />
        <Route path="login" element={<Login />} />
        <Route path="admin" element={<AdminDashboard/>} />
      </Routes>
    </Router>
    </div>
  );
}

export default App;
