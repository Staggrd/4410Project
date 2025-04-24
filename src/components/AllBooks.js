import React, {useState} from "react";
import {books} from "./Books.js"

function AllBooks(){
    const [search, setSearch] = useState('');
    const searchCategories = ["title", "author", "ISBN"]

    return(
        <div className="books-page-container">
            <form>
                <input type="text" className="search-bar" placeholder="Search books" onChange={(e) => setSearch(e.target.value)}></input>
            </form>
            <div className="books-container">
                    {books
                    .filter((item) =>(
                            searchCategories.some((category) => item[category].toLowerCase().includes(search))
                    ))
                    .map((item) => (
                        <div className="book-info-block" key={item.book_id}>
                            <img src={item.image_url} alt="book-image" width="150px" height="170px"></img>
                            <p><strong>Title</strong>: {item.title}</p>   
                            <p><strong>Author</strong>: {item.author}</p>   
                            <p><strong>ISBN</strong>: {item.ISBN}</p>   
                            <p><strong>Category</strong>: {item.category}</p>   
                            <p><strong>Total Copies</strong>: {item.total_copies}</p>   
                            <p><strong>Available Copies</strong>: {item.available_copies}</p>   
                        </div>    
                    ))}
            </div>
        </div>
    );
}

export default AllBooks;