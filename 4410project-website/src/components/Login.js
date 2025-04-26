
const Login = () =>{

    return(
        <div className='login-container'>
            <form className='form-section'>
                <h2>Login</h2>
                <label for="username" className="form-label">Username: </label>
                <input name="username" className="form-input" required/><br/>

                <label for="password" className="form-label">Password: </label>
                <input type="password" name="password" className="form-input"required/><br/>

                <button type="submit" className="form-button">Login</button>
            </form>

        </div>
    )
}

export default Login