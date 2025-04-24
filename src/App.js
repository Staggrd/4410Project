import { Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import Welcome from './components/Welcome';
import Login from './components/Login';
import AllBooks from './components/AllBooks';
import Dashboard from './components/Dashboard';
import './App.css';

function App() {
  return (
    <div>
      <h1 className="header-section">Library Management System</h1>
      <Navbar />
      <Routes>
        <Route path="/" element={<Welcome />} />
        <Route path="/books" element={<AllBooks />} />
        <Route path="/login" element={<Login />} />
        <Route path="/dashboard" element={<Dashboard />} />
      </Routes>
    </div>
  );
}

export default App;