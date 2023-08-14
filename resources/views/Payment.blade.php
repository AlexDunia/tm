<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="stylesheet"
        href="\css\admin.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>
    <form id="paymentForm">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email-address" required />
        </div>
        <div class="form-group">
          <label for="amount">Amount</label>
          <input type="tel" id="amount" required />
        </div>
        <div class="form-group">
          <label for="first-name">First Name</label>
          <input type="text" id="first-name" />
        </div>
        <div class="form-group">
          <label for="last-name">Last Name</label>
          <input type="text" id="last-name" />
        </div>
        <div class="form-submit">
          <button type="submit" onclick="payWithPaystack(event)"> Pay </button>
        </div>
      </form>
      <script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</body>

<script>
    const paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener("submit", payWithPaystack, false);

function payWithPaystack(e) {
  e.preventDefault();

  let handler = PaystackPop.setup({
    key: 'pk_test_a23671022344a4de4ca87e5b42f68b3f5d84bfd9', // Replace with your public key
    email: document.getElementById("email-address").value,
    amount: document.getElementById("amount").value * 100,
    ref: 'TD'+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
    // label: "Optional string that replaces customer email"
    onClose: function(){
      alert('Window closed.');
    },
    callback: function(response){
    //   let message = 'Payment complete! Reference: ' + response.reference;
      let reference = response.reference
      fetch("{{URL::to('verifypayment')}}/" + reference)
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error("Error:", error);
    });

//       $.ajax({
//     type: "GET",
//     url: "{{URL::to('verifypayment')}}/" + reference,
//     success: function(response) {
//         console.log(response);
//     }
// });

    //   console.log(reference);
    }
  });

  handler.openIframe();
}
</script>
