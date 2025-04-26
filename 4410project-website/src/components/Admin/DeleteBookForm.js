function DeleteBookForm(){
    return(
        <div className="form-section">
            <form method="post">
                <label for="title">Title of Book to Delete: </label>
                <input type="text" name="title" className="form-input" required/><br/>
                
                <button type="submit" name="delete_book_submit" className="form-button">Delete Book</button>
            </form><br/>
        </div>
    )
}

export default DeleteBookForm