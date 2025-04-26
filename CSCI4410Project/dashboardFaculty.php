<link rel="stylesheet" href="style.css">
<?php
    session_start();
    require 'db.php';
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
    echo "<h2>Welcome! You are logged in to your faculty account.</h2>";
    echo "<a href='logout.php'>Logout</a>";
?>

<form method="post">
    <button name="checkout_book">Check Out A Book</button> <!-- Added Function -->
    <button name="return_book">Return A Book</button> <!-- Added Function -->
    <button name="reserve_book">Reserve A Book</button> <!-- -->
    <button name="unreserve_book">Unreserve A Book</button> <!-- -->
</form>

<?php

if (isset($_POST['checkout_book'])) { //first we see if they have reached their limit, then we ask what user wishes to check out

    $user_id = $_SESSION["user_id"]; //take the user's user_id from session

    $stmt = $conn->prepare("SELECT * FROM checkouts WHERE user_id=? AND returned_date IS NULL"); //get all rows from checkouts where user_id = current user
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows >= 10) { //if user has met or (somehow) exceeded limit....
        echo "<p>You have already checked out the maximum amount of books allowed by your account.</p><br>";
        echo "<p>Return a book or talk to your librarian or administrator.</p>"; //print this message
    }
    else { //else...
        echo "<p><i>A faculty account may check out 10 books at a time</i></p><br>";
        echo '<form method="post">';
        echo 'Enter the ISBN of the book you want to check out: <input type="text" name="ISBN"><br>';
        echo '<button type="submit" name="checkout_book_submit">Check Out Book</button>';
        echo '</form><br>'; //create this form
    }

}

if (isset($_POST['checkout_book_submit'])) { //now if the books is not reserved by this user or there are no avilable copies, check the book out

    $user_id = $_SESSION["user_id"]; //take the user's user_id from session
    $ISBN = $_POST["ISBN"]; //get the ISBN of the book from the previous form

    $check_ISBN = $conn->prepare("SELECT * FROM books WHERE ISBN=?"); //first we check if this ISBN even exists...
    $check_ISBN->bind_param("s", $ISBN);
    $check_ISBN->execute();
    $check_ISBN->store_result(); //store the results of check_ISBN for later comparison

    $stmt = $conn->prepare("SELECT * FROM books WHERE ISBN=? AND available_copies = 0"); //get if the requested book has 0 copies left
    $stmt->bind_param("s", $ISBN);
    $stmt->execute();
    $stmt->store_result(); //store the result of stmt for comparison later

    $stmt1 = $conn->prepare("SELECT book_id FROM books WHERE ISBN=?"); //lets also get the book_id of the ISBN
    $stmt1->bind_param("s", $ISBN);
    $stmt1->execute();
    $stmt1->store_result();
    $stmt1->bind_result($book_id); //save the book_id of that ISBN into $book_id for use later
    $stmt1->fetch();

    $stmt1 = $conn->prepare("SELECT * FROM reservations WHERE book_id=? AND user_id<>?"); //now lets see if this book has been reserved by another user...
    $stmt1->bind_param("ii", $book_id, $user_id);
    $stmt1->execute();
    $stmt1->store_result(); //store result for comparison later

    if ($check_ISBN->num_rows === 0) {
        echo "<p>A book with this ISBN does not exist in this system.</p><br>";
        echo "<p>Please try again</p>";
    }
    else if ($stmt->num_rows === 1) { //if current ISBN has 0 copies available...
        echo "<p>There are no more avialable copies of this book at this moment.</p><br>";
        echo "<p>Please try again another time</p>";
    }
    else if ($stmt1->num_rows >= 1) { //if this books is reserved by someone other than current user...
        echo "<p>This book has been reserved by another account.</p><br>";
        echo "<p>Please try again another time</p>";
    }
    else { //otherwise...
        $borrowed_date = date("Y-m-d"); //save current date to a variable
        $due_date = date("Y-m-d", strtotime("+14 days")); //adds 2 weeks to current date to create the due date

        $stmt = $conn->prepare("INSERT INTO checkouts (user_id, book_id, borrowed_date, due_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $book_id, $borrowed_date, $due_date);

        try{
            $stmt->execute(); //create a new row in checkouts
            echo "Book checked out successfully!";
        } catch (mysqli_sql_exception $e){
            echo "Something went wrong.";
        }

        $stmt = $conn->prepare("UPDATE books SET available_copies=available_copies - 1 WHERE ISBN =?");
        $stmt->bind_param("s", $ISBN);

        try{
            $stmt->execute(); //decrement available_copies for the ISBN in books by 1
        } catch (mysqli_sql_exception $e){
            echo "Something went wrong.";
        }
    }
}

if (isset($_POST['return_book'])) { //first we see if they have reached their limit, then we ask what user wishes to check out

    $user_id = $_SESSION["user_id"]; //take the user's user_id from session

    $stmt = $conn->prepare("SELECT * FROM checkouts WHERE user_id=? AND returned_date IS NULL"); //get all rows from checkouts where user_id = current user
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows >= 1) { //if the user has a book to return...
        echo '<form method="post">';
        echo 'Enter the ISBN of the book you want to return: <input type="text" name="ISBN"><br>';
        echo '<button type="submit" name="return_book_submit">Return Book</button>';
        echo '</form><br>'; //create this form
    }
    else { //otherwise
        echo "<p>You have no books to return.</p><br>";
        echo "<p>Take a look at our catelogue!</p>"; //print this message
    }
}

if (isset($_POST['return_book_submit'])) {
    
    $user_id = $_SESSION["user_id"]; //take the user's user_id from session
    $ISBN = $_POST["ISBN"]; //get the ISBN of the book from the previous form

    $check_ISBN = $conn->prepare("SELECT * FROM books WHERE ISBN=?"); //first we check if this ISBN even exists...
    $check_ISBN->bind_param("s", $ISBN);
    $check_ISBN->execute();
    $check_ISBN->store_result(); //store the results of check_ISBN for later comparison

    $stmt = $conn->prepare("SELECT book_id FROM books WHERE ISBN=?"); //lets also get the book_id of the ISBN
    $stmt->bind_param("s", $ISBN);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($book_id); //save the book_id of that ISBN into $book_id for use later
    $stmt->fetch();

    $stmt = $conn->prepare("SELECT * FROM checkouts WHERE book_id=? AND user_id=? AND returned_date IS NULL"); //now we check if book is yet to be returned by this user
    $stmt->bind_param("ii", $book_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($check_ISBN->num_rows === 0) { //if this ISBN does not exist in our system...
        echo "<p>A book with this ISBN does not exist in this system.</p><br>";
        echo "<p>Please try again</p>";
    }
    else if ($stmt->num_rows === 1) { //if book_id and user_id match AND has not been returned yet...

        $stmt = $conn->prepare("UPDATE books SET available_copies=available_copies + 1 WHERE ISBN =?"); //add this copy back to available copies
        $stmt->bind_param("s", $ISBN);

        try{
            $stmt->execute(); //decrement available_copies for the ISBN in books by 1
        } catch (mysqli_sql_exception $e){
            echo "Something went wrong.";
        }
    }

    $returned_date = date("Y-m-d"); //save current date to a variable

    $stmt = $conn->prepare("UPDATE checkouts SET returned_date=? WHERE book_id=? AND user_id=? AND returned_date IS NULL"); //give this entry in checkous a return date
    $stmt->bind_param("sii", $returned_date, $book_id, $user_id);

    try{
        $stmt->execute(); //put the return date back that checkout row
        echo "<p>Book returned successfully!</p>";
    } catch (mysqli_sql_exception $e){
        echo "Something went wrong.";
    }
}
?>