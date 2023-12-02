function checkId(){
	var userid = document.getElementById("tempId").value;

	if(userid) {
		url = "check.php?userid="+userid;
		window.open(url,"chkid","width=400,height=200");
	} else {
		alert("Please enter your ID");
	}
}

function decide(){
    document.getElementById("checkId").innerHTML = "Available ID"
	document.getElementById("id").value = document.getElementById("tempId").value
	document.getElementById("tempId").disabled = true
	document.getElementById("btn").disabled = false
	document.getElementById("checkBtn").value = "Change"
	document.getElementById("checkBtn").setAttribute("onclick", "change()")
}

function change(){
	document.getElementById("checkId").innerHTML = "Check for duplicate"
	document.getElementById("tempId").disabled = false
	document.getElementById("tempId").value = ""
	document.getElementById("btn").disabled = true
	document.getElementById("checkBtn").value = "Click"
	document.getElementById("checkBtn").setAttribute("onclick", "checkId()")
}