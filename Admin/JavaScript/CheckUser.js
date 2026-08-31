function CheckUser()
{
    let username=document.getElementById("username").value;
    let response=document.getElementById("userresponse");
    let xhttp=new XMLHttpRequest();

    xhttp.onreadystatechange=function()
    {
        if(this.readyState==4 && this.status==200)
        {
            response.innerHTML=this.responseText;
        }
    }

    xhttp.open("POST","../Control/CheckUser.php",true);
    xhttp.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhttp.send("username="+encodeURIComponent(username));
}

function CheckEmail()
{
    let email=document.getElementById("email").value;
    let response=document.getElementById("emailresponse");
    let xhttp=new XMLHttpRequest();

    xhttp.onreadystatechange=function()
    {
        if(this.readyState==4 && this.status==200)
        {
            response.innerHTML=this.responseText;
        }
    }

    xhttp.open("POST","../Control/CheckEmail.php",true);
    xhttp.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhttp.send("email="+encodeURIComponent(email));
}

function CheckPhone()
{
    let phone=document.getElementById("phone").value;
    let response=document.getElementById("phoneresponse");
    let xhttp=new XMLHttpRequest();

    xhttp.onreadystatechange=function()
    {
        if(this.readyState==4 && this.status==200)
        {
            response.innerHTML=this.responseText;
        }
    }

    xhttp.open("POST","../Control/CheckPhone.php",true);
    xhttp.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhttp.send("phone="+encodeURIComponent(phone));
}
