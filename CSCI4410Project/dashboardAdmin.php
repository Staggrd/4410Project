<link rel="stylesheet" href="style.css">
<?php
    session_start();
    require 'db.php';
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
    echo "<h2>Welcome! You are logged in to your administrator account.</h2>";
    echo "<a href='logout.php'>Logout</a><br><br>"; //this will be our logout hyperlink (added break for spacing)

    //creating a reusable function for displaying a table
function displayTable($result){
    if ($result->num_rows > 0) { //if the query returns results...
        echo "<table border='1'>"; //start table
        while ($row = mysqli_fetch_assoc($result)) { //whie there are rows associated with result...
            echo "<tr>"; //start row
            foreach ($row as $value) { //for each value in the row
                echo "<td>" . $value . "</td>"; //print each value in its own cell
            }
            echo "</tr>"; //end row
        }
        echo "</table>"; //end table
    }
    else {
        echo "No records found.";
    }
}
//I will break up the php segment to create buttons for each admin function
?>

<form method="post">
    <button name="display_booklist">Get Booklist</button> <!-- Added Function -->
    <button name="add_book">Add Book to List</button> <!-- Added Function -->
    <button name="delete_book">Delete Book from List</button> <!-- Added Function-->
    <button name="update_book">Update Book Info</button> <!-- Added Function -->
    <button name="display_users">Get Userlist</button> <!-- Added Function -->
    <button name="add_user">Add User</button> <!-- Added Function -->
    <button name="delete_user">Delete User</button> <!-- Added Function-->
    <button name="most_borrowed_books">Most Borrowed Books</button> <!-- Added Function-->
    <button name="overdue_books">Overdue Books</button> <!-- Added Function-->
</form>

<?php //now we will give all these buttons functionality

if (isset($_POST['display_booklist'])) { //IF FUNCTION FOR DISPLAYINGA ALL BOOKLIST WITHOUT IMAGE
    $sql = "SELECT * FROM books"; //save query to $sql
    $result = $conn->query($sql);
    // Call the function to display table
     displayTable($result);
}

if (isset($_POST['display_users'])) { //IF FUNCTION FOR DISLPAYING ALL USERS
    $sql = "SELECT user_id, username, email, phone, role FROM users"; //save query to $sql
    $result = $conn->query($sql);
    // Call the function to display table
     displayTable($result);
}

if (isset($_POST['add_book'])) { //first we display a form for entering new book information
    echo '<form method="post">';
    echo 'Title: <input type="text" name="title" required><br>';
    echo 'Author: <input type="text" name="author" required><br>';
    echo 'ISBN: <input type="text" name="ISBN" required><br>';
    echo 'Category: <input type="text" name="category" required><br>';
    echo 'Image URL: <input type="text" name="image_url" required><br>';
    echo '<button type="submit" name="add_book_submit">Add Book</button>';
    echo '</form><br>';
}

if (isset($_POST['add_book_submit'])) { //now we will put information into the 'books' table
    $title = $_POST["title"];
    $author = $_POST["author"];
    $ISBN = $_POST["ISBN"];
    $category = $_POST["category"];
    $image_url = $_POST["image_url"];

    $stmt = $conn->prepare("INSERT INTO books (title, author, ISBN, category, image_url, total_copies, available_copies) VALUES (?, ?, ?, ?, ?, 1, 1)");
    $stmt->bind_param("sssss", $title, $author, $ISBN, $category, $image_url);

    try{
        $stmt->execute();
        echo "Book added successfully!";
    } catch (mysqli_sql_exception $e){
        echo "Book already exists in database.";
    }
}

if (isset($_POST['delete_book'])) { //we first create a form for the book to be deleted
    echo '<form method="post">';
    echo 'Title of Book to Delete: <input type="text" name="title" required><br>';
    echo '<button type="submit" name="delete_book_submit">Delete Book</button>';
    echo '</form><br>';

    
}

if (isset($_POST['delete_book_submit'])) {//then once submitted, we prepare a statement and 
    $title = $_POST["title"];

    $stmt = $conn->prepare("DELETE FROM books WHERE title=?");
    $stmt->bind_param("s", $title);

    try{
        $stmt->execute();
        echo "Book deleted successfully!";
    } catch (mysqli_sql_exception $e){
        echo "Something went wrong.";
    }
}

if (isset($_POST['add_user'])) { //first we display a form for entering new user information
    echo '<form method="post">';
    echo 'Username: <input name="username" required><br>';
    echo 'Password: <input type="password" name="password" required><br>';
    echo 'Email: <input type="email" name="email" required><br>';
    echo 'Phone: <input name="phone" required><br>';
    echo 'Role: <select name="role" id="role">';
        echo '<option value="Student">Student</option>';
        echo '<option value="Faculty">Faculty</option>';
        echo '<option value="Admin">Admin</option>';
    echo '</select><br>';
    echo '<button type="submit" name="add_user_submit">Add User</button>';
    echo '</form><br>';
}

if (isset($_POST['add_user_submit'])) { //now we will put information into the 'users' table
    $username = $_POST["username"]; //username
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); //password
    $email = $_POST["email"]; //email
    $role = $_POST["role"]; //role
    $phone = $_POST["phone"]; //phone

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $email, $phone, $role);

    try{
        $stmt->execute();
        echo "User added successfully!";
    } catch (mysqli_sql_exception $e){
        echo "User already exists in database.";
    }
}

if (isset($_POST['delete_user'])) { //first we display a form for entering new user information
    echo '<form method="post">';
    echo 'Username of account to delete: <input name="username" required><br>';
    echo '<button type="submit" name="delete_user_submit">Add Book</button>';
    echo '</form><br>';
}

if (isset($_POST['delete_user_submit'])) { //now we execute the previous form
    $username = $_POST["username"];

    $stmt = $conn->prepare("DELETE FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    try{
        $stmt->execute(); //try to execute statement
        echo "Deleted successfully!"; //Display if successful
    } catch (mysqli_sql_exception $e){
        echo "Something went wrong."; //Display if failed
    }
}

if (isset($_POST['update_book'])) { //show a form to update a book
    echo '<form method="post">';
    echo 'BookID to Update: <input type="number" name="book_id" required><br>';
    echo 'Title: <input type="text" name="title" required><br>';
    echo 'Author: <input type="text" name="author" required><br>';
    echo 'ISBN: <input type="text" name="ISBN" required><br>';
    echo 'Category: <input type="text" name="category" required><br>';
    echo 'Image URL: <input type="text" name="image_url" required><br>';
    echo '<button type="submit" name="update_book_submit">Update Book</button>';
    echo '</form><br>';
}

if (isset($_POST['update_book_submit'])) { //now we execute the previous form
    $book_id = $_POST["book_id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $ISBN = $_POST["ISBN"];
    $category = $_POST["category"];
    $image_url = $_POST["image_url"];

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, ISBN=?, category=?, image_url=? WHERE book_id=?");
    $stmt->bind_param("sssssi", $title, $author, $ISBN, $category, $image_url, $book_id);

    try{
        $stmt->execute(); //try to execute statement
        echo "Updated successfully!"; //Display if successful
    } catch (mysqli_sql_exception $e){
        echo "Something went wrong."; //Display if failed
    }
}

if (isset($_POST['most_borrowed_books'])) { // function for displaying the most borrowed books
    $sql = "SELECT b.book_id, b.title, b.author, b.ISBN,
     COUNT(c.book_id) AS times_Borrowed 
    FROM books b 
    JOIN checkouts c ON b.book_id = c.book_id 
    GROUP BY b.book_id ORDER BY times_Borrowed DESC"; //save query to $sql

    $result = $conn->query($sql);
    // Call the function to display table
     displayTable($result);
}

if (isset($_POST['overdue_books'])) { // function for displaying overdue books
    $sql = "SELECT b.book_id, b.title, b.author, b.ISBN,
     c.due_date, u.user_id, u.username, DATEDIFF(NOW(), c.due_date) AS days_Overdue 
    FROM checkouts c 
    JOIN users u ON u.user_id = c.user_id
    JOIN books b ON b.book_id = c.book_id 
    AND c.due_date < NOW() AND c.returned_date IS NULL"; //save query to $sql

    $result = $conn->query($sql);
    // Call the function to display table
    displayTable($result);
}
?>