import {Link} from "react-router-dom";

function Navbar(){
    
    return(
        <nav className="navbar">
            <ul className="nav-links">
                <li><Link to='/'>Welcome</Link></li>
                <li><Link to='/books'>Books</Link></li>
                <li><Link to='/login'>Login</Link></li>
                <li><Link to='/dashboard'>Dashboard</Link></li>
            </ul>
        </nav>
    );
}

export default Navbar;