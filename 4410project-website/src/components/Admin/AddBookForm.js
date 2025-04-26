function AddBookForm(){
    return(
        <div className="form-section">
            <form method="post">
                <label for="title" className="form-label">Title: </label>
                <input type="text" name="title" className="form-input" required/><br/>

                <label for="author"className="form-label">Author: </label>
                <input type="text" name="author" className="form-input" required/><br/>

                <label for="ISBN" className="form-label">ISBN: </label>
                <input type="text" name="ISBN" className="form-input" required/><br/>

                <label for="category" className="form-label">Category: </label>
                <input type="text" name="category" className="form-input" required/><br/>

                <label for="image_url" className="form-label">Image URL: </label>
                <input type="text" name="image_url" className="form-input" required/><br/>
                
                <button type="submit" name="add_book_submit " className="form-button">Add Book</button>
            </form>
        </div>
    )
}

export default AddBookForm