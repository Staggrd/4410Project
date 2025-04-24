// src/components/Dashboard.js
import React, { useState, useEffect } from 'react';
import axios from 'axios';

export default function Dashboard() {
  const [user, setUser] = useState({
    username: '',
    email: '',
    address: ''
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios
      .get('/api/user/profile')
      .then(res => setUser(res.data))
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  const handleChange = e => {
    const { name, value } = e.target;
    setUser(u => ({ ...u, [name]: value }));
  };

  const handleSubmit = e => {
    e.preventDefault();
    axios
      .put('/api/user/profile', user)
      .then(() => alert('Profile updated!'))
      .catch(console.error);
  };

  if (loading) return <p>Loading your profile…</p>;

  return (
    <div className="dashboard">
      <h2>Account Details</h2>
      <form onSubmit={handleSubmit}>
        <label>
          Username
          <input
            name="username"
            value={user.username}
            onChange={handleChange}
          />
        </label>
        <label>
          Email
          <input
            name="email"
            type="email"
            value={user.email}
            onChange={handleChange}
          />
        </label>
        <label>
          Address
          <input
            name="address"
            value={user.address}
            onChange={handleChange}
          />
        </label>
        <button type="submit">Save Changes</button>
      </form>
    </div>
  );
}