<head>
    <link
        rel="stylesheet"
        href="\css\checkout.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
        <style>
            .ticket-gallery {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                gap: 20px;
                padding: 20px 0;
                margin-bottom: 20px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                scrollbar-color: #C04888 rgba(255, 255, 255, 0.1);
            }

            .ticket-gallery::-webkit-scrollbar {
                height: 6px;
            }

            .ticket-gallery::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }

            .ticket-gallery::-webkit-scrollbar-thumb {
                background: #C04888;
                border-radius: 10px;
            }

            .ticket-card {
                min-width: 260px;
                background: rgba(40, 40, 55, 0.8);
                border-radius: 12px;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
                position: relative;
            }

            .ticket-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            }

            .ticket-header {
                position: relative;
                height: 100px;
                overflow: hidden;
                background: linear-gradient(45deg, #C04888, #6c13a0);
            }

            .ticket-header img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.7;
            }

            .ticket-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(rgba(0,0,0,0.2), rgba(40, 40, 55, 0.9));
                display: flex;
                align-items: flex-end;
                padding: 15px;
            }

            .ticket-name {
                color: white;
                font-size: 18px;
                font-weight: 600;
                text-shadow: 0 1px 3px rgba(0,0,0,0.4);
            }

            .ticket-content {
                padding: 15px;
            }

            .ticket-type {
                font-size: 12px;
                color: #C04888;
                margin-bottom: 5px;
            }

            .ticket-price {
                font-size: 16px;
                font-weight: 600;
                color: white;
                margin-bottom: 15px;
            }

            .ticket-quantity {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }

            .quantity-label {
                font-size: 12px;
                color: rgba(255, 255, 255, 0.6);
                margin-right: 10px;
            }

            .ticket-quantity-value {
                background: rgba(192, 72, 136, 0.2);
                border-radius: 4px;
                padding: 3px 8px;
                font-size: 14px;
                font-weight: 600;
                color: white;
            }

            .ticket-ids {
                margin-top: 10px;
            }

            .ticket-ids-label {
                font-size: 12px;
                color: rgba(255, 255, 255, 0.6);
                margin-bottom: 5px;
            }

            .ticket-id-list {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .ticket-id {
                background: rgba(192, 72, 136, 0.2);
                border-radius: 4px;
                padding: 2px 5px;
                font-size: 10px;
                color: #C04888;
            }

            .ticket-more {
                font-size: 10px;
                color: #C04888;
                text-decoration: underline;
                cursor: pointer;
            }

            .ticket-summary {
                background: rgba(40, 40, 55, 0.7);
                border-radius: 12px;
                padding: 20px;
                margin-top: 20px;
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            .summary-title {
                font-size: 18px;
                font-weight: 600;
                color: white;
                margin-bottom: 15px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding-bottom: 10px;
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .summary-label {
                font-size: 14px;
                color: rgba(255, 255, 255, 0.7);
            }

            .summary-value {
                font-size: 14px;
                font-weight: 600;
                color: white;
            }

            .summary-total {
                display: flex;
                justify-content: space-between;
                padding-top: 10px;
                margin-top: 10px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .total-label {
                font-size: 16px;
                font-weight: 600;
                color: white;
            }

            .total-value {
                font-size: 20px;
                font-weight: 700;
                color: #C04888;
            }

            /* Modal styles */
            .modal-container {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }

            .modal-content {
                position: relative;
                background-color: #141822;
                margin: 10% auto;
                padding: 0;
                width: 80%;
                max-width: 600px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.5);
                border-radius: 12px;
                animation: modalFadeIn 0.3s;
                border: 1px solid #2e3546;
            }

            @keyframes modalFadeIn {
                from {opacity: 0; transform: translateY(-20px);}
                to {opacity: 1; transform: translateY(0);}
            }

            .modal-header {
                background-color: #0c111b;
                padding: 15px 20px;
                border-bottom: 1px solid #2e3546;
                border-radius: 12px 12px 0 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-title {
                color: #f0b90b;
                margin: 0;
                font-size: 24px;
                font-weight: 600;
            }

            .close-modal {
                color: #aaa;
                font-size: 28px;
                font-weight: bold;
                line-height: 1;
                cursor: pointer;
                background: none;
                border: none;
                padding: 0;
                margin: 0;
            }

            .close-modal:hover {
                color: white;
            }

            .modal-body {
                padding: 20px;
                color: #ddd;
                max-height: 70vh;
                overflow-y: auto;
            }

            /* Ticket ID styles */
            .event-title {
                color: #f0b90b;
                margin: 15px 0 10px;
                font-size: 20px;
                font-weight: 600;
                border-bottom: 1px solid rgba(240, 185, 11, 0.3);
                padding-bottom: 8px;
            }

            .ticket-type {
                color: #ffffff;
                margin: 12px 0 8px;
                font-size: 16px;
                font-weight: 500;
                display: flex;
                align-items: center;
            }

            .ticket-type::before {
                content: "";
                display: inline-block;
                width: 8px;
                height: 8px;
                background-color: #f0b90b;
                border-radius: 50%;
                margin-right: 8px;
            }

            .ticket-id-list {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 15px;
            }

            .ticket-id-item {
                background-color: rgba(46, 53, 70, 0.6);
                padding: 10px 15px;
                border-radius: 8px;
                margin-bottom: 5px;
                border: 1px solid rgba(240, 185, 11, 0.2);
                transition: all 0.2s ease;
            }

            .ticket-id-item:hover {
                background-color: rgba(46, 53, 70, 0.8);
                transform: translateY(-2px);
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            }

            .ticket-id {
                font-weight: bold;
                font-family: monospace;
                font-size: 14px;
                background-color: rgba(240,185,11,0.2);
                padding: 5px 8px;
                border-radius: 6px;
                display: inline-block;
                letter-spacing: 1px;
                color: #f0b90b;
            }

            /* Media queries for responsiveness */
            @media (max-width: 768px) {
                .modal-content {
                    width: 95%;
                    margin: 10% auto;
                }

                .ticket-id-list {
                    flex-direction: column;
                    gap: 8px;
                }

                .modal-title {
                    font-size: 20px;
                }

                .modal-body {
                    padding: 15px;
                }

                .event-title {
                    font-size: 18px;
                }

                .ticket-type {
                    font-size: 15px;
                }
            }

            @media (max-width: 480px) {
                .modal-content {
                    margin: 5% auto;
                }

                .ticket-id-item {
                    padding: 8px 12px;
                }

                .ticket-id {
                    font-size: 12px;
                }
            }
        </style>
</head>


{{-- <body>
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
</body> --}}


<body>



    @auth

    <?php
    // Where A stands for authenticated User
    $atotalPrice = 0; // Initialize the total price variable
    ?>

    {{-- <div class="tabledetails">
        <p> {{ $mycart->cprice }} </p>
    </div> --}}
    <?php
    // where a stands for authenticated user.
    $atotalPrice += $mycart->ctotalprice;
    ?>

     <div class="checkoutimage">
     <img  width="250px" src="{{asset('storage/' . $mycart->cdescription)}}">
     </div>

     <div class="checkoutname">
      <h1> {{$mycart->cname}} </h1>
     </div>

    <div class="ft">

    <div class="form">
        <div class="formhead">
            <h3> Buyer Information. </h3>
        </div>
            <form id="paymentForm" class="coformbg">
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email-address" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="amount"  value="{{$atotalPrice}}" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="cn"  value="{{$mycart->cname}}" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="cq"  value="{{$mycart->cquantity}}" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="en"  value="{{$mycart->eventname}}" required />
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
                  <button type="submit" onclick="payWithPaystack(event)" class="btncontact"> Pay </button>
                </div>
              </form>

        </div>



         {{-- <h1> Cart page </h1>
        @foreach($mycart as $onewelcome)
        <a> {{$onewelcome['cname']}} </a>
        <a href="{{url('/delete', $onewelcome->id)}}""> Delete me </a>
        <br/>
        @endforeach --}}

        <div class="tabledetailsbg">

            <div class="tabledetailsbghead">
            <h2> Ticket Information </h2>
            </div>

            <?php
            // Where A stands for authenticated User
            $atotalPrice = 0; // Initialize the total price variable

            // Get ticket IDs from session
            $ticketIds = session('ticket_ids_' . $mycart->id, []);
            if (empty($ticketIds)) {
                // Generate IDs if not in session
                $ticketIds = [];
                $baseId = 'TIX-' . strtoupper(substr(md5($mycart->eventname . $mycart->cname), 0, 6));
                for ($i = 1; $i <= $mycart->cquantity; $i++) {
                    $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                }
                session(['ticket_ids_' . $mycart->id => $ticketIds]);
            }

            // Store ticket IDs as JSON string for Paystack
            $ticketIdsJson = json_encode($ticketIds);
            ?>

            <div class="tabledetailsflex">
                <div class="tabledetails">
                    <p> {{ $mycart->cname }} X  {{ $mycart->cquantity }} </p>
                </div>

                <div class="tabledetails">
                    <p> {{ $mycart->cprice }} </p>
                </div>
                <?php
                // where a stands for authenticated user.
                $atotalPrice += $mycart->ctotalprice;
                ?>
            </div>

            <div class="tabledetailsflex" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="tabledetails" style="width: 100%;">
                    <p style="margin-bottom: 5px; font-weight: bold;">Your Unique Ticket IDs:</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                        @foreach($ticketIds as $id)
                            <span style="display: inline-block; background: rgba(192, 72, 136, 0.2); padding: 3px 6px; border-radius: 4px; font-size: 12px;">{{ $id }}</span>
                        @endforeach
                    </div>
                    <p style="margin-top: 10px; font-size: 12px; color: rgba(255, 255, 255, 0.6);">
                        These IDs will be used to identify your tickets at the event.
                    </p>
                </div>
            </div>

            <div class="tabledetailsflex">
                <div class="tabledetails">
                    <p> Total </p>
                </div>

                <div class="tabledetails">
                    <p> {{$atotalPrice}} </p>
                </div>
            </div>
        </div>

     </div>

        @else


        <div class="checkoutimage">
          <img  width="250px" src="{{asset('storage/' . $timage)}}">
          </div>

          <div class="checkoutname">
           <h1> {{$eventname}} </h1>
          </div>

        <div class="ft">

        <div class="form">
            <div class="formhead">
                <h3> Buyer Information </h3>
            </div>
        {{-- <form method="post" class="coformbg" action="/" enctype="multipart/form-data"> --}}
            {{-- <form method="post" class="coformbg" action="/" enctype="multipart/form-data"> --}}
             {{-- <h1> {{$atotalPrice}} </h1> --}}
                <form id="paymentForm" class="coformbg">
                    <div class="form-group">
                      <label for="email">Email Address</label>
                      <input type="email" id="email-address" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="amount"  value="{{$totalprice}}" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="cn"  value="{{$tname}}" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="cq"  value="{{$tquantity}}" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="en"  value="{{$eventname}}" required />
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
                      <button type="submit" onclick="payWithPaystack(event)" class="btncontact"> Pay </button>
                    </div>
                  </form>

            </div>


    <div class="tabledetailsbg">

      <div class="tabledetailsbghead">
      <h2> Ticket Information </h2>
      </div>

  <div class="tabledetailsflex">

  <div class="tabledetails">
      {{-- <p> Table for 10 </p> --}}
      <p> {{$tname}}  X  {{$tquantity}} </p>
  </div>

  <div class="tabledetails">
      <p> {{$tprice}} </p>
  </div>

 </div>

 <div class="tabledetailsflex">

  <div class="tabledetails">
      <p> Total </p>
  </div>

  <div class="tabledetails">
      <p> {{$totalprice}} </p>
  </div>




           {{-- <div class="tabledetailsflex">

            <div class="tabledetails">
                <p> Total </p>
            </div>

            <div class="tabledetails">
                 <p> {{$atotalPrice}} </p>
            </div>

           </div> --}}



        </div>

         </div>


     </div>

     @endauth

     <br/>
     <br/>
     <br/>
     <br/>
     <br/>

    {{-- end of circular bg --}}

<!-- Modal for displaying ticket IDs -->
<div id="idsModal" class="modal-container">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Your Ticket IDs</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div id="modalBody" class="modal-body">
            <!-- Ticket IDs will be loaded here by the showTicketIds function -->
        </div>
    </div>
</div>

<script>
// Function to show ticket IDs modal
function showTicketIds() {
    // Get the modal
    const modal = document.getElementById('idsModal');
    const modalBody = document.getElementById('modalBody');

    // Clear previous content
    modalBody.innerHTML = '';

    // Get tickets from localStorage
    const ticketCart = localStorage.getItem('ticketCart');

    if (!ticketCart || JSON.parse(ticketCart).length === 0) {
        modalBody.innerHTML = '<p>No tickets found in your cart.</p>';
        modal.style.display = 'block';
        return;
    }

    const tickets = JSON.parse(ticketCart);

    // Group tickets by event and ticket type
    const eventGroups = {};

    tickets.forEach(ticket => {
        const eventName = ticket.event_name;
        const ticketType = ticket.ticket_type;

        if (!eventGroups[eventName]) {
            eventGroups[eventName] = {};
        }

        if (!eventGroups[eventName][ticketType]) {
            eventGroups[eventName][ticketType] = [];
        }

        // Generate a unique ticket ID for each ticket based on quantity
        for (let i = 0; i < ticket.quantity; i++) {
            eventGroups[eventName][ticketType].push(
                generateTicketId(ticket.event_id, ticket.ticket_type_id)
            );
        }
    });

    // Display grouped tickets in the modal
    for (const eventName in eventGroups) {
        const eventTitle = document.createElement('h3');
        eventTitle.textContent = eventName;
        eventTitle.className = 'event-title';
        modalBody.appendChild(eventTitle);

        for (const ticketType in eventGroups[eventName]) {
            const typeHeader = document.createElement('h4');
            typeHeader.textContent = ticketType;
            typeHeader.className = 'ticket-type';
            modalBody.appendChild(typeHeader);

            const ticketList = document.createElement('div');
            ticketList.className = 'ticket-id-list';

            eventGroups[eventName][ticketType].forEach(ticketId => {
                const item = document.createElement('div');
                item.className = 'ticket-id-item';

                const idSpan = document.createElement('span');
                idSpan.className = 'ticket-id';
                idSpan.textContent = ticketId;

                item.appendChild(idSpan);
                ticketList.appendChild(item);
            });

            modalBody.appendChild(ticketList);
        }
    }

    // Show the modal
    modal.style.display = 'block';

    // Close modal when clicking the close button
    document.querySelector('.close-modal').onclick = function() {
        modal.style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
}

// Helper function to generate unique ticket IDs
function generateTicketId(eventId, ticketTypeId) {
    const timestamp = Date.now().toString().slice(-6);
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `TK-${eventId}${ticketTypeId}-${timestamp}-${random}`;
}
</script>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

<script>
    @auth
    // For authenticated users
    const ticketData = JSON.parse(document.getElementById('ticket_data').value);
    const totalAmount = parseFloat(document.getElementById('amount').value);

    function payWithPaystack(e) {
        e.preventDefault();

        // Flatten the ticket IDs for metadata
        let allTicketIds = [];
        for (const itemId in ticketData) {
            allTicketIds = [...allTicketIds, ...ticketData[itemId]];
        }

        // Generate a unique reference
        const randomRef = 'TD' + Math.floor((Math.random() * 1000000000) + 1);

        let handler = PaystackPop.setup({
            key: 'pk_test_a23671022344a4de4ca87e5b42f68b3f5d84bfd9',
            email: document.getElementById("email-address").value,
            amount: totalAmount * 105, // Adding 5% service fee
            metadata: {
                custom_fields: [
                    {
                        display_name: "Customer Name",
                        variable_name: "customer_name",
                        value: document.getElementById("first-name").value + " " + document.getElementById("last-name").value
                    },
                    {
                        display_name: "Phone Number",
                        variable_name: "phone",
                        value: document.getElementById("phone").value
                    },
                    {
                        display_name: "Ticket-IDs",
                        variable_name: "ticket_ids",
                        value: JSON.stringify(allTicketIds)
                    }
                ]
            },
            ref: randomRef,
            onClose: function() {
                alert('Window closed.');
            },
            callback: function(response) {
                let reference = response.reference;
                fetch("{{URL::to('verifypayment')}}/" + reference)
                    .then(response => response.json())
                    .then(data => {
                        window.location.href = "{{URL::to('success')}}";
                    })
                    .catch(error => {
                        // Handle error
                    });
            }
        });

        handler.openIframe();
    }
    @else
    // For guest users
    const input = document.getElementById("cn").value;
    const words = input.split(' ');
    const fl = words.map(word => word[0]).filter(Boolean).join('');
    const fll = document.getElementById("en").value;
    const flsecond = fll.replace(/\s/g, '');
    const cr = fl + flsecond;
    const ticketIds = @json($ticketIds);

    function payWithPaystack(e) {
        e.preventDefault();

        let handler = PaystackPop.setup({
            key: 'pk_test_a23671022344a4de4ca87e5b42f68b3f5d84bfd9',
            email: document.getElementById("email-address").value,
            amount: document.getElementById("amount").value * 105, // Adding 5% service fee
            metadata: {
                custom_fields: [
                    {
                        display_name: "Event-name",
                        variable_name: "Event-name",
                        value: document.getElementById("cn").value,
                    },
                    {
                        display_name: "Quantity",
                        variable_name: "Quantity",
                        value: document.getElementById("cq").value,
                    },
                    {
                        display_name: "Eventname",
                        variable_name: "eventname",
                        value: fl,
                    },
                    {
                        display_name: "Ticket-IDs",
                        variable_name: "ticket_ids",
                        value: JSON.stringify(ticketIds),
                    },
                    {
                        display_name: "Customer Name",
                        variable_name: "customer_name",
                        value: document.getElementById("first-name").value + " " + document.getElementById("last-name").value
                    },
                    {
                        display_name: "Phone Number",
                        variable_name: "phone",
                        value: document.getElementById("phone").value
                    }
                ]
            },
            ref: 'TD' + cr + Math.floor((Math.random() * 1000000000) + 1),
            onClose: function() {
                alert('Window closed.');
            },
            callback: function(response) {
                let reference = response.reference;
                fetch("{{URL::to('verifypayment')}}/" + reference)
                    .then(response => response.json())
                    .then(data => {
                        window.location.href = "{{URL::to('success')}}";
                    })
                    .catch(error => {
                        // Handle error
                    });
            }
        });

        handler.openIframe();
    }
    @endauth

    // Show ticket IDs modal
    function showIds(ids, eventName) {
        const modal = document.getElementById('idsModal');
        const list = document.getElementById('modalBody');
        const title = document.querySelector('.modal-title');

        // Update modal title
        title.textContent = `Ticket IDs for ${eventName}`;

        // Clear previous content
        list.innerHTML = '';

        // Add each ticket ID to the list
        ids.forEach(id => {
            const div = document.createElement('div');
            div.className = 'ticket-id-item';
            div.textContent = id;
            list.appendChild(div);
        });

        // Show the modal
        modal.style.display = 'block';
    }

    // Close modal
    function closeIdsModal() {
        document.getElementById('idsModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('idsModal');
        if (event.target === modal) {
            closeIdsModal();
        }
    }
</script>
