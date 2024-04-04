const validateEmail = (email) => {
  var errorEmail = document.querySelector(".error-email");

  if (email.length > 64) {
    errorEmail.innerHTML = "✖ Email is too long.";
    return false;
  } else if (email.indexOf("@") !== email.lastIndexOf("@")) {
    errorEmail.innerHTML = "✖ Not a valid email address.";
    return false;
    // Search for @ within the string. if it is not the last of the @ within the string, we have too many in the email.
  } else if (email.endsWith(".")) {
    errorEmail.innerHTML = "✖ Not a valid email address.";
    return false;
    // if the final character in the email is a period we have a problem
  } else if (email.indexOf(".") === -1) {
    errorEmail.innerHTML = "✖ Not a valid email address.";
    return false;
    // in the even there is not a single period at all in the email
  } else {
    errorEmail.innerHTML = "";
    return true;
  }
};

function sendMail() {
  console.log("im here");

  const email = document.getElementById("email");
  const fullName = document.getElementById("fullName");
  const message = document.getElementById("message");

  var fullNameVal = fullName ? fullName.value : "";

  var emailVal = email ? email.value : "";

  var messageVal = message ? message.value : "";

  var pass1 = true;
  var pass2 = true;
  var pass3 = true;

  // Perform form validation

  if (fullNameVal === "") {
    console.log("im here");
    var errorName = document.querySelector(".error-name");
    errorName.innerHTML = "✖ Please enter your full name.";
    pass1 = false;
  } else {
    var errorName = document.querySelector(".error-name");
    errorName.innerHTML = "";
    pass1 = true;
  }

  if (!validateEmail(emailVal)) {
    pass2 = false;
  } else {
    pass2 = true;
  }

  if (messageVal === "") {
    console.log("im here in message");
    var errorMessage = document.querySelector(".error-message");
    errorMessage.innerHTML = "✖ Please enter a message to be sent.";
    pass3 = false;
  } else {
    var errorMessage = document.querySelector(".error-message");
    errorMessage.innerHTML = "";
    pass3 = true;
  }

  if ((pass1 === false) | (pass2 === false) | (pass3 === false)) return;

  var params = {
    from_name: fullNameVal,
    email_id: emailVal,
    message: messageVal,
  };

  if ((pass1 === true) & (pass2 === true) & (pass3 == true)) {
    emailjs
      .send("service_o0nmbjl", "template_fupncvr", params)
      .then(function (response) {
        alert("Email sent successfully!");
      });
  }
}
