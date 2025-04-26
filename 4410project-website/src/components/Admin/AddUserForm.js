
    function AddUserForm(){
    return(
        <div className="form-section">
            <form method="post">
                <label for="username" className="form-label">Username: </label>
                <input name="username" className="form-input" required/><br/>

                <label for="password" className="form-label">Password: </label>
                <input type="password" name="password" className="form-input" required/><br/>

                <label for="email" className="form-label">Email: </label>
                <input type="email" name="email" className="form-input" required/><br/>

                <label for="phone" className="form-label">Phone: </label>
                <input name="phone" className="form-input" required/><br/>

                <label for="role" className="form-label">Role: </label>
                <select name="role" id="role">
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Admin">Admin</option></select><br/>
                <button type="submit" name="add_user_submit" className="form-button">Add Book</button>
            </form><br/>
        </div>
    )
}

export default AddUserForm