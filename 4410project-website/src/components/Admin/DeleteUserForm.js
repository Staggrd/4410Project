
function DeleteUserForm(){
    return(
        <div className="form-section">
            <form method="post">
                <label for="title">Username of account to delete: </label>
                <input name="username" className="form-input" required/><br/>
                
                <button type="submit" name="delete_user_submit" className="form-button">Add Book</button>
            </form><br/>
        </div>
    )
}

export default DeleteUserForm