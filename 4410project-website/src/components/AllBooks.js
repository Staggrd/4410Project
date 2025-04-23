import React, {useState} from "react";
import {books} from "./Books.js"

function AllBooks(){
    const [booksLength, setBooksLength] = useState(books.length);
    const [search, setSearch] = useState(''); // Keeps track of whatever the user is typing in the search bar
    const [searchSubmit, setSearchSubmit] = useState(''); // Keeps track of whatever the user is typing in the search bar

    const searchCategories = ["title", "author", "ISBN"]; // Holds the valid search categories

    const [currentPage, setCurrentPage] = useState(1); // Keeps track of the current page of listed books
    const booksPerPage = 10; // Number of books displayed per page
    const lastIndex = currentPage * booksPerPage; // Keeps track of whatever the last index should be for the visible books array based on current page
    const firstIndex = lastIndex - booksPerPage;  // Keeps track of whatever the first index should be for the visible books array based on current page

    // Holds books that are visible to the user which is based on the current page and whatever the user searches
    const visibleBooks = books
    .filter((item) =>(
        searchCategories.some((category) => item[category].toLowerCase().includes(search))
    ))
    .slice(firstIndex, lastIndex);

    const totalPages = Math.ceil(booksLength/booksPerPage); // Holds the total number of pages based on all the books available and rounds the result
    const pageNumber = [...Array(totalPages + 1).keys()].slice(1); // Holds an array of the available page numbers
    const hrefLink = '#'; // Used for pagination routing


    // When the user enters their search, update book length so the total number of pages is accurate with matching books and then
    // update the search hook so the matching books are displayed
    function handleSearch(e){
        e.preventDefault();
        setBooksLength(books.filter((item) =>
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
        <div className="books-page-container">
            <form onSubmit={handleSearch}>
                <input type="text" className="search-bar" placeholder="Search books" value={searchSubmit} onChange={(e) => {setSearchSubmit(e.target.value)}}></input>
                <button className="submit-button" type="submit">Search</button>
            </form>
            <div className="books-container">
                    {visibleBooks
                    .map((item) => ( // Displays all the visible books based on the format below
                        <div className="book-info-block" key={item.book_id}>
                            <img src={item.image_url} alt="book-image" width="150px" height="170px"></img>
                            <p><strong>Title</strong>: {item.title}</p>   
                            <p><strong>Author</strong>: {item.author}</p>   
                            <p><strong>ISBN</strong>: {item.ISBN}</p>   
                            <p><strong>Category</strong>: {item.category}</p>   
                            <p><strong>Total Copies</strong>: {item.total_copies}</p>   
                            <p><strong>Available Copies</strong>: {item.available_copies}</p>
                            <div className="books-button-block">
                                <button className="books-button">Reserve</button>
                                <button className="books-button">Checkout</button>   
                                <button className="books-button">Review</button>   
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

export default AllBooks;