import AllBooks from "../AllBooks";
import AddBookForm from "./AddBookForm"
import React, {useState} from "react";
import DeleteBookForm from "./DeleteBookForm";
import AddUserForm from "./AddUserForm";
import UpdateBookForm from "./UpdateBookForm";
import DisplayUsers from "./DisplayUsers";
import DeleteUserForm from "./DeleteUserForm";

function AdminDashboard(){
    const [render, setRender] = useState(''); // Track component being rendered

    const handleSubmit = (e) => {
        e.preventDefault();
        setRender(e.target.name)
    }
    
    return(
        <div className="admin-dashboard-container">
            <h2>Welcome! You are logged in to your administrator account.</h2>
            <div className="body-links">
            <a href='/logout' className="link">Logout</a><br/><br/> {/*this will be our logout hyperlink (added break for spacing)*/}
            </div>
            <form >
                <button className="admin-button" name="display_booklist" onClick={handleSubmit}>Get Booklist</button> 
                <button className="admin-button" name="add_book" onClick={handleSubmit}>Add Book to List</button>
                <button className="admin-button" name="delete_book" onClick={handleSubmit}>Delete Book from List</button> 
                <button className="admin-button" name="update_book" onClick={handleSubmit}>Update Book Info</button> 
                <button className="admin-button" name="display_users" onClick={handleSubmit}>Get Userlist</button> 
                <button className="admin-button" name="add_user" onClick={handleSubmit}>Add User</button> 
                <button className="admin-button" name="delete_user" onClick={handleSubmit}>Delete User</button> 
            </form>

            {/* Conditional rendering based on render value */}
            {render === "display_booklist" && (
                <AllBooks/>
            )}
            {render === "add_book" && (
                <AddBookForm/>
            )}
            {render === "delete_book" && (
                <DeleteBookForm/>
            )}
            {render === "update_book" && (
                <UpdateBookForm/>
            )}
            {render === "display_users" && (
                <DisplayUsers/>
            )}
            {render === "add_user" && (
                <AddUserForm/>
            )}
            {render === "delete_user" && (
                <DeleteUserForm/>
            )}
        </div>
    ); 
}

export default AdminDashboard;