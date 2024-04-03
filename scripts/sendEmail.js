function sendMail() {
  var params = {
    from_name: document.getElementById("fullName").value,
    email_id: document.getElementById("email").value,
    message: document.getElementById("message").value,
  };

  emailjs
    .send("service_o0nmbjl", "template_fupncvr", params)
    .then(function (response) {
      alert("Email sent successfully!" + response.status + " " + response.text);
    });
}
