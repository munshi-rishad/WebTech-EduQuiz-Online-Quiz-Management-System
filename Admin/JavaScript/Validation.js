function validateRegistration()
{
    let name=document.getElementById("name").value.trim();
    let username=document.getElementById("username").value.trim();
    let email=document.getElementById("email").value.trim();
    let password=document.getElementById("password").value;
    let confirm=document.getElementById("confirm").value;

    if(name==="")
    {
        alert("Enter your name");
        return false;
    }

    if(username.length<3)
    {
        alert("Username must be at least 3 characters");
        return false;
    }

    if(!email.includes("@"))
    {
        alert("Enter a valid email");
        return false;
    }

    if(password.length<4)
    {
        alert("Password must be at least 4 characters");
        return false;
    }

    if(password!==confirm)
    {
        alert("Passwords do not match");
        return false;
    }

    return true;
}

function validateLogin()
{
    let email=document.getElementById("loginEmail").value.trim();
    let password=document.getElementById("loginPassword").value;

    if(email==="" || password==="")
    {
        alert("Email and password are required");
        return false;
    }

    if(!email.includes("@"))
    {
        alert("Enter a valid email");
        return false;
    }

    return true;
}

function validatePassword()
{
    let oldPassword=document.getElementById("oldPassword").value;
    let newPassword=document.getElementById("newPassword").value;
    let confirmPassword=document.getElementById("confirmPassword").value;

    if(oldPassword==="")
    {
        alert("Enter current password");
        return false;
    }

    if(newPassword.length<4)
    {
        alert("New password must be at least 4 characters");
        return false;
    }

    if(newPassword!==confirmPassword)
    {
        alert("Passwords do not match");
        return false;
    }

    return true;
}

function validateForgotPassword()
{
    let email=document.getElementById("forgotEmail").value.trim();
    let username=document.getElementById("forgotUsername").value.trim();
    let password=document.getElementById("forgotPassword").value;
    let confirm=document.getElementById("forgotConfirm").value;

    if(email==="" || username==="")
    {
        alert("Email and username are required");
        return false;
    }

    if(!email.includes("@"))
    {
        alert("Enter a valid email");
        return false;
    }

    if(password.length<4)
    {
        alert("Password must be at least 4 characters");
        return false;
    }

    if(password!==confirm)
    {
        alert("Passwords do not match");
        return false;
    }

    return true;
}

function validateProfile()
{
    let name=document.getElementById("profileName").value.trim();

    if(name==="")
    {
        alert("Enter your name");
        return false;
    }

    return true;
}
