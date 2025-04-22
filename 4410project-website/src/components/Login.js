
const Login = () =>{

    return(
        <div className='login-container'>
            <form className='login-section'>
                <h2>Login</h2>
                Username: <input name="username" className="login-register-input" required/><br/>
                Password: <input type="password" name="password" className="login-register-input"required/><br/>
                <button type="submit" className="login-register-button">Login</button>
            </form>

        </div>
    )
}

export default Login