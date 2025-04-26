import React, {useState} from "react";
import {users} from "../Users.js"

function DisplayUsers(){
    const [usersLength, setusersLength] = useState(users.length);
    const [search, setSearch] = useState(''); // Keeps track of whatever the user is typing in the search bar
    const [searchSubmit, setSearchSubmit] = useState(''); // Keeps track of whatever the user is typing in the search bar

    const searchCategories = ["username", "email", "phone", "role"]; // Holds the valid search categories

    const [currentPage, setCurrentPage] = useState(1); // Keeps track of the current page of listed users
    const usersPerPage = 10; // Number of users displayed per page
    const lastIndex = currentPage * usersPerPage; // Keeps track of whatever the last index should be for the visible users array based on current page
    const firstIndex = lastIndex - usersPerPage;  // Keeps track of whatever the first index should be for the visible users array based on current page

    // Holds users that are visible to the user which is based on the current page and whatever the user searches
    const visibleusers = users
    .filter((item) =>(
        searchCategories.some((category) => item[category].toLowerCase().includes(search))
    ))
    .slice(firstIndex, lastIndex);

    const totalPages = Math.ceil(usersLength/usersPerPage); // Holds the total number of pages based on all the users available and rounds the result
    const pageNumber = [...Array(totalPages + 1).keys()].slice(1); // Holds an array of the available page numbers
    const hrefLink = '#'; // Used for pagination routing


    // When the user enters their search, update book length so the total number of pages is accurate with matching users and then
    // update the search hook so the matching users are displayed
    function handleSearch(e){
        e.preventDefault();
        setusersLength(users.filter((item) =>
            (
                searchCategories.some((category) => item[category].toLowerCase().includes(searchSubmit))
            )).length)
        setSearch(searchSubmit);
        setCurrentPage(1)
    }
    

    function previousPage() {
        if(currentPage !== 1){
            setCurrentPage(currentPage - 1)
        }
    }

    function changeCurrentPage(id){
        setCurrentPage(id)
    }

    function nextPage(){
        if(currentPage !== totalPages){
            setCurrentPage(currentPage + 1)
        }
    }

    return(
        <div className="display-page-container">
            <form onSubmit={handleSearch}>
                <input type="text" className="search-bar" placeholder="Search users" value={searchSubmit} onChange={(e) => {setSearchSubmit(e.target.value)}}></input>
                <button className="submit-button" type="submit">Search</button>
            </form>
            <div className="display-container">
                    {visibleusers
                    .map((item) => ( // Displays all the visible users based on the format below
                        <div className="display-info-block" key={item.user_id}>
                            <p><strong>Username</strong>: {item.username}</p>   
                            <p><strong>Email</strong>: {item.email}</p>   
                            <p><strong>Phone</strong>: {item.phone}</p>   
                            <p><strong>Role</strong>: {item.role}</p> 
                            <p><strong>User ID</strong>: {item.user_id}</p>
                            <div className="display-button-block">
                                <button className="display-button">Delete User</button>  
                            </div>
                        </div>    
                    ))}
            </div>

            {/*Displays the pages at the bottom */}
            <nav className="pagination-nav">
                <ul className="pagination">
                    <li className="page-item">
                        <a href={hrefLink} className="page-link" onClick={previousPage}>Prev</a>
                    </li>
                    {
                        pageNumber.map((n,i) => (
                            <li className={`page-item ${currentPage === n ? 'active' : ''}`} key={i}>  {/*Condition so that css styling can indicate current page*/}
                                <a href={hrefLink} className='page-link' onClick={()=>changeCurrentPage(n)}>{n}</a>
                            </li>
                        ))
                    }
                    <li className="page-item">
                        <a href={hrefLink} className="page-link" onClick={nextPage}>Next</a>
                    </li>
                </ul>
            </nav>
                           
        </div>
    );
}

export default DisplayUsers;