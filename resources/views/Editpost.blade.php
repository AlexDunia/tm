@extends('layouts.app')

@section('content')
<div class="container">
    <div class="header">
        <h1>Create New Event</h1>
        <p>Fill in the details below to create your event</p>
    </div>

    <form method="POST" class="form-card" action="/createticket" id="eventForm">
        {{ csrf_field() }}
        <input type="hidden" name="create_ticket_types" value="1">

        <div class="form-section">
            <h2><i class="fas fa-calendar-alt"></i> Event Information</h2>

            <div class="form-group">
                <label for="name">Event Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', 'New Event Title') }}"
                    placeholder="Enter event name"
                    required
                />
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Event Category</label>
                <select id="category" name="category">
                    <option value="">Select a category</option>
                    <option value="Music" selected>Music</option>
                    <option value="Movies">Movies</option>
                    <option value="Arts">Arts</option>
                    <option value="Food">Food & Drink</option>
                    <option value="Business">Business</option>
                    <option value="Sports">Sports</option>
                    <option value="Health">Health & Wellness</option>
                    <option value="Community">Community</option>
                    <option value="Film">Film & Media</option>
                    <option value="Travel">Travel & Outdoor</option>
                </select>
                @error('category')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea
                    id="description"
                    name="description"
                    placeholder="Describe your event"
                    required
                >{{ old('description', 'This is an amazing event you won\'t want to miss!') }}</textarea>
                @error('description')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="date">Event Date</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    value="{{ old('date', date('Y-m-d', strtotime('+2 weeks'))) }}"
                    required
                />
                @error('date')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Event Location</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location', 'Lagos, Nigeria') }}"
                    placeholder="Enter event location"
                    required
                />
                @error('location')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Event Image URL</label>
                <input
                    type="url"
                    id="image"
                    name="image"
                    value="{{ old('image', 'https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/architecture-signs.jpg') }}"
                    placeholder="https://res.cloudinary.com/your-account/image/upload/your-image.jpg"
                    required
                />
                @error('image')
                <p class="error-message">{{ $message }}</p>
                @enderror
                <div class="image-preview" id="image-preview">
                    <img src="{{ old('image', 'https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/architecture-signs.jpg') }}" alt="Event Image Preview">
                </div>
            </div>

            <div class="form-group">
                <label for="heroimage">Hero Image URL</label>
                <input
                    type="url"
                    id="heroimage"
                    name="heroimage"
                    value="{{ old('heroimage', 'https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/nature-mountains.jpg') }}"
                    placeholder="https://res.cloudinary.com/your-account/image/upload/your-hero-image.jpg"
                    required
                />
                @error('heroimage')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="herolink">Hero Link</label>
                <input
                    type="text"
                    id="herolink"
                    name="herolink"
                    value="{{ old('herolink', 'https://example.com/event') }}"
                    placeholder="Enter hero link"
                    required
                />
                @error('herolink')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h2><i class="fas fa-ticket-alt"></i> Ticket Options</h2>
            <p class="help-text">Define your ticket types. These will appear on your event page.</p>

            <div id="ticketContainer">
                <div class="ticket-item">
                    <h3>Regular Ticket</h3>
                    <div class="ticket-details">
                        <div class="ticket-field">
                            <label for="ticket_name_1">Ticket Name</label>
                            <input
                                type="text"
                                id="ticket_name_1"
                                name="ticket_name[]"
                                value="Regular Ticket"
                                required
                            />
                        </div>
                        <div class="ticket-field">
                            <label for="ticket_price_1">Price (₦)</label>
                            <input
                                type="number"
                                id="ticket_price_1"
                                name="ticket_price[]"
                                value="5000"
                                min="0"
                                step="100"
                                required
                            />
                        </div>
                        <div class="ticket-field wide">
                            <label for="ticket_description_1">Description</label>
                            <textarea
                                id="ticket_description_1"
                                name="ticket_description[]"
                            >Standard event access</textarea>
                        </div>
                        <div class="ticket-field">
                            <label for="ticket_capacity_1">Capacity (optional)</label>
                            <input
                                type="number"
                                id="ticket_capacity_1"
                                name="ticket_capacity[]"
                                placeholder="Leave empty for unlimited"
                                min="1"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="add-btn" onclick="addTicket()">
                <i class="fas fa-plus"></i> Add Ticket Type
            </button>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">
                Create Event
            </button>
        </div>
    </form>
</div>

<style>
    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .header {
        text-align: center;
        margin-bottom: 30px;
    }
    .header h1 {
        color: #C04888;
        margin: 0;
        font-weight: 700;
    }
    .header p {
        color: #666;
        margin-top: 8px;
    }
    .form-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-bottom: 30px;
    }
    .form-section {
        margin-bottom: 30px;
    }
    .form-section h2 {
        font-size: 1.3rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e1e1e1;
        color: #C04888;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #C04888;
        box-shadow: 0 0 0 3px rgba(192, 72, 136, 0.1);
    }
    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }
    .error-message {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
    }
    .image-preview {
        margin-top: 10px;
        border: 1px dashed #e1e1e1;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
    }
    .image-preview img {
        max-width: 100%;
        max-height: 200px;
    }
    .ticket-item {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #e1e1e1;
    }
    .ticket-item h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
        color: #333;
    }
    .ticket-details {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .ticket-field {
        flex: 1;
        min-width: 200px;
    }
    .ticket-field.wide {
        flex: 100%;
    }
    .ticket-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }
    .ticket-field input,
    .ticket-field textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #e1e1e1;
        border-radius: 5px;
    }
    .ticket-field textarea {
        min-height: 80px;
    }
    .help-text {
        color: #666;
        margin-top: -15px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .add-btn {
        background-color: transparent;
        border: 2px dashed #C04888;
        color: #C04888;
        padding: 10px 15px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 auto;
        transition: all 0.3s;
    }
    .add-btn:hover {
        background-color: rgba(192, 72, 136, 0.1);
    }
    .remove-btn {
        background-color: #f8d7da;
        border: none;
        color: #721c24;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        float: right;
        margin-top: -25px;
    }
    .submit-btn {
        background-color: #C04888;
        color: white;
        border: none;
        padding: 14px 25px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }
    .submit-btn:hover {
        background-color: #a73672;
    }
    .form-actions {
        text-align: center;
    }

    @media (max-width: 768px) {
        .container {
            margin: 20px auto;
        }
        .form-card {
            padding: 25px;
        }
        .ticket-field {
            min-width: 100%;
        }
    }
</style>

<script>
    let ticketCount = 1;

    function addTicket() {
        ticketCount++;

        const container = document.getElementById('ticketContainer');
        const ticketItem = document.createElement('div');
        ticketItem.className = 'ticket-item';

        ticketItem.innerHTML = `
            <button type="button" class="remove-btn" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i> Remove
            </button>
            <h3>Ticket Type ${ticketCount}</h3>
            <div class="ticket-details">
                <div class="ticket-field">
                    <label for="ticket_name_${ticketCount}">Ticket Name</label>
                    <input
                        type="text"
                        id="ticket_name_${ticketCount}"
                        name="ticket_name[]"
                        value="VIP Ticket"
                        required
                    />
                </div>
                <div class="ticket-field">
                    <label for="ticket_price_${ticketCount}">Price (₦)</label>
                    <input
                        type="number"
                        id="ticket_price_${ticketCount}"
                        name="ticket_price[]"
                        value="15000"
                        min="0"
                        step="100"
                        required
                    />
                </div>
                <div class="ticket-field wide">
                    <label for="ticket_description_${ticketCount}">Description</label>
                    <textarea
                        id="ticket_description_${ticketCount}"
                        name="ticket_description[]"
                    >Premium seating with complimentary drinks</textarea>
                </div>
                <div class="ticket-field">
                    <label for="ticket_capacity_${ticketCount}">Capacity (optional)</label>
                    <input
                        type="number"
                        id="ticket_capacity_${ticketCount}"
                        name="ticket_capacity[]"
                        placeholder="Leave empty for unlimited"
                        min="1"
                        value="50"
                    />
                </div>
            </div>
        `;

        container.appendChild(ticketItem);
    }

    // Update image preview when URL changes
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');

        if (imageInput) {
            imageInput.addEventListener('input', function() {
                const preview = document.getElementById('image-preview');
                const url = this.value.trim();

                if (url) {
                    preview.innerHTML = `<img src="${url}" alt="Event Image Preview">`;
                } else {
                    preview.innerHTML = '<div class="placeholder">Enter an image URL to see preview</div>';
                }
            });
        }

        // Add form submission debugging
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // Don't prevent default - we want the form to submit
            // e.preventDefault();

            console.log('Form is being submitted...');

            const ticketNames = document.querySelectorAll('input[name="ticket_name[]"]');
            console.log(`Submitting form with ${ticketNames.length} ticket types`);

            // Log each ticket type
            for (let i = 0; i < ticketNames.length; i++) {
                const name = ticketNames[i].value;
                const price = document.querySelectorAll('input[name="ticket_price[]"]')[i].value;
                const desc = document.querySelectorAll('textarea[name="ticket_description[]"]')[i].value;
                const capacity = document.querySelectorAll('input[name="ticket_capacity[]"]')[i].value;

                console.log(`Ticket #${i+1}: ${name} - ₦${price} - ${desc} - Capacity: ${capacity || 'unlimited'}`);
            }

            // Check hidden field for create_ticket_types
            console.log(`Create ticket types flag: ${document.querySelector('input[name="create_ticket_types"]').value}`);

            // At this point, the form will continue submitting
        });

        // Add "Show Form Data" button to help debug
        const formActions = document.querySelector('.form-actions');
        const debugButton = document.createElement('button');
        debugButton.type = 'button';
        debugButton.className = 'debug-btn';
        debugButton.textContent = 'Debug Form Data';
        debugButton.style.marginTop = '15px';
        debugButton.style.padding = '8px 15px';
        debugButton.style.background = '#6c757d';
        debugButton.style.color = 'white';
        debugButton.style.border = 'none';
        debugButton.style.borderRadius = '4px';
        debugButton.style.cursor = 'pointer';

        debugButton.addEventListener('click', function() {
            const formData = new FormData(document.getElementById('eventForm'));
            const outputDiv = document.createElement('div');
            outputDiv.style.marginTop = '20px';
            outputDiv.style.padding = '15px';
            outputDiv.style.background = '#f8f9fa';
            outputDiv.style.border = '1px solid #ddd';
            outputDiv.style.borderRadius = '4px';

            let html = '<h4>Form Data</h4><ul style="list-style: none; padding: 0;">';

            // Log ticket data specifically
            const ticketNames = formData.getAll('ticket_name[]');
            const ticketPrices = formData.getAll('ticket_price[]');

            html += `<li><strong>Ticket Count:</strong> ${ticketNames.length}</li>`;

            // Add all form fields
            for (const [key, value] of formData.entries()) {
                html += `<li><strong>${key}:</strong> ${value}</li>`;
            }

            html += '</ul>';
            outputDiv.innerHTML = html;

            // Remove previous debug output if it exists
            const previousOutput = document.querySelector('.debug-output');
            if (previousOutput) {
                previousOutput.remove();
            }

            outputDiv.className = 'debug-output';
            formActions.appendChild(outputDiv);
        });

        formActions.appendChild(debugButton);
    });
</script>
@endsection
