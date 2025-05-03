<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<style>
        :root {
            --primary-color: #C04888;
            --primary-hover: #a73672;
            --secondary-color: #FECC01;
            --secondary-hover: #ffdb46;
            --text-color: #333;
            --border-color: #e1e1e1;
            --bg-color: #fff;
            --card-bg: #f9f9f9;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --input-bg: #fff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --radius: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: var(--text-color);
            line-height: 1.6;
        }

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
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2.5rem;
        }

        .header p {
            color: #666;
            margin-top: 8px;
            font-size: 1.1rem;
        }

        .form-card {
            background: var(--bg-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px;
            margin-bottom: 30px;
        }

        .form-section {
            margin-bottom: 30px;
            position: relative;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section h2 {
            font-size: 1.3rem;
        margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h2 i {
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group:last-child {
            margin-bottom: 0;
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
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            background-color: var(--input-bg);
        font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(192, 72, 136, 0.1);
        }

        .form-group input[type="url"] {
            color: #2980b9;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group .help-text {
        display: block;
            margin-top: 6px;
            font-size: 14px;
            color: #666;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row:last-child {
            margin-bottom: 0;
        }

        .form-col {
            flex: 1;
            min-width: 200px;
        }

        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
    }

    .image-preview {
            margin-top: 15px;
            border: 1px dashed var(--border-color);
            border-radius: var(--radius);
            padding: 10px;
            min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
            background-color: rgba(0, 0, 0, 0.02);
        position: relative;
            overflow: hidden;
    }

    .image-preview img {
        max-width: 100%;
            max-height: 150px;
        object-fit: contain;
    }

    .preview-placeholder {
            color: #888;
            font-size: 14px;
            text-align: center;
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: var(--radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-top: 30px;
            position: relative;
        }

        .submit-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn.loading {
            background-color: var(--primary-hover);
            cursor: not-allowed;
            pointer-events: none;
        }

        .submit-btn .loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        .submit-btn.loading .loader {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .ticket-section {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: var(--radius);
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .add-section-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--secondary-color);
            color: #333;
            border: none;
            padding: 10px 15px;
            border-radius: var(--radius);
        font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .add-section-btn:hover {
            background-color: var(--secondary-hover);
            transform: translateY(-1px);
        }

        .remove-section-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: transparent;
            color: #888;
            border: none;
        font-size: 14px;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .remove-section-btn:hover {
            color: var(--error-color);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-title h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .ticket-status {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .status-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
            }

            .form-card {
                padding: 25px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .header h1 {
                font-size: 2rem;
            }
    }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create New Event</h1>
            <p>Fill in the details below to create your event</p>
        </div>

        <form method="post" action="/creationsuccess" id="eventForm">
    {{ csrf_field() }}
            <input type="hidden" name="create_ticket_types" value="1">

            <div class="form-card">
                <div class="form-section">
                    <h2><i class="fas fa-calendar-alt"></i> Event Information</h2>

                    <div class="form-group">
        <label for="name">Event Name</label>
        <input
            type="text"
                            id="name"
            name="name"
                            value="Film Festival 2024"
                            placeholder="Enter event name"
                            required
                            maxlength="100"
        />
        @error('name')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category">Event Category</label>
                        <select id="category" name="category">
                            <option value="">Select a category</option>
                            <option value="Music">Music</option>
                            <option value="Arts">Arts</option>
                            <option value="Food">Food & Drink</option>
                            <option value="Business">Business</option>
                            <option value="Sports">Sports</option>
                            <option value="Health">Health & Wellness</option>
                            <option value="Community">Community</option>
                            <option value="Film" selected>Film & Media</option>
                            <option value="Travel">Travel & Outdoor</option>
                        </select>
                        <span class="help-text">Select the category that best fits your event</span>
                        @error('category')
                        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="date">Event Date</label>
                                <input
                                    type="date"
                                    id="date"
                                    name="date"
                                    value="2024-08-10"
                                    required
                                />
                                <span class="help-text">When will the event take place?</span>
                                @error('date')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="time">Event Time</label>
                                <input
                                    type="time"
                                    id="time"
                                    name="time"
                                    value="18:30"
                                />
                                <span class="help-text">What time does the event start?</span>
                                @error('time')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="enddate">End Date (Optional)</label>
                                <input
                                    type="date"
                                    id="enddate"
                                    name="enddate"
                                    value="2024-08-12"
                                />
                                <span class="help-text">When will the event end?</span>
                                @error('enddate')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
        <label for="location">Location</label>
        <input
            type="text"
                                    id="location"
            name="location"
                                    value="Cinema Plaza, Los Angeles"
                                    placeholder="Enter event location"
                                    required
        />
        @error('location')
                                <p class="error-message">{{ $message }}</p>
        @enderror
                            </div>
                        </div>
    </div>

                    <div class="form-group">
                        <label for="description">Event Description</label>
                        <textarea
                            id="description"
            name="description"
                            placeholder="Describe your event"
                            required
                            maxlength="255"
                        >Annual Film Festival featuring award-winning films from around the world. Experience screenings, workshops, and panel discussions with renowned directors.</textarea>
                        <span class="help-text">Provide details about your event (max 255 characters)</span>
                        <div class="character-count"><span id="descriptionCount">0</span>/255</div>
        @error('description')
                        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

                    <div class="form-group">
                        <label for="status">Event Status</label>
                        <select id="status" name="status">
                            <option value="Upcoming" selected>Upcoming</option>
                            <option value="Live">Live</option>
                            <option value="Postponed">Postponed</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Sold Out">Sold Out</option>
                        </select>
                        @error('status')
                        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-images"></i> Event Media</h2>

                    <div class="form-group">
                        <label for="image">Event Image URL</label>
        <input
            type="url"
                            id="image"
            name="image"
                            placeholder="https://res.cloudinary.com/your-account/image/upload/your-image.jpg"
                            value="https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/architecture-signs.jpg"
            onchange="previewImage(this, 'event-image-preview')"
                            required
        />
                        <span class="help-text">Paste your Cloudinary image URL here (required)</span>
        <div class="image-preview" id="event-image-preview">
            <div class="preview-placeholder">Image preview will appear here</div>
        </div>
        @error('image')
                        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

                    <div class="form-group">
                        <label for="heroimage">Hero Image URL</label>
        <input
            type="url"
                            id="heroimage"
            name="heroimage"
                            placeholder="https://res.cloudinary.com/your-account/image/upload/your-hero-image.jpg"
                            value="https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/nature-mountains.jpg"
            onchange="previewImage(this, 'hero-image-preview')"
                            required
        />
                        <span class="help-text">Paste your Cloudinary hero image URL here (required)</span>
        <div class="image-preview" id="hero-image-preview">
            <div class="preview-placeholder">Image preview will appear here</div>
        </div>
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
                            value="https://example.com/filmfestival"
                            placeholder="Enter hero link"
                            required
        />
                        <span class="help-text">Link for the hero section</span>
        @error('herolink')
                        <p class="error-message">{{ $message }}</p>
        @enderror
                    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-ticket-alt"></i> Advanced Ticket Types</h2>
                    <p class="help-text">Define your ticket types with more options. These will be stored in the ticket_types table and properly displayed on the event page.</p>

                    <div id="advancedTicketContainer">
                        <div class="ticket-section">
                            <div class="section-title">
                                <h3>Regular Ticket</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="ticket_name_1">Ticket Name</label>
                                        <input
                                            type="text"
                                            id="ticket_name_1"
                                            name="ticket_name[]"
                                            value="Regular Ticket"
                                            placeholder="Regular Ticket"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="ticket_price_1">Price</label>
                                        <input
                                            type="number"
                                            id="ticket_price_1"
                                            name="ticket_price[]"
                                            value="5000"
                                            placeholder="5000"
                                            min="0"
                                            step="100"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="ticket_description_1">Description</label>
                                        <textarea
                                            id="ticket_description_1"
                                            name="ticket_description[]"
                                            placeholder="Standard event access"
                                        >Standard event access</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="ticket_capacity_1">Capacity (leave empty for unlimited)</label>
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
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="ticket_sales_start_1">Sales Start Date</label>
                                        <input
                                            type="datetime-local"
                                            id="ticket_sales_start_1"
                                            name="ticket_sales_start[]"
                                            value="{{ date('Y-m-d\TH:i') }}"
                                        />
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="ticket_sales_end_1">Sales End Date</label>
                                        <input
                                            type="datetime-local"
                                            id="ticket_sales_end_1"
                                            name="ticket_sales_end[]"
                                            value="{{ date('Y-m-d\TH:i', strtotime('+2 months')) }}"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="add-section-btn" onclick="addAdvancedTicketSection()">
                        <i class="fas fa-plus"></i> Add More Advanced Ticket Types
                    </button>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-ticket-alt"></i> Legacy Ticket Options</h2>
                    <p class="help-text">These are the old ticket options. You can still fill them for backward compatibility.</p>

                    <div id="tableTicketsContainer">
                        <div class="ticket-section">
                            <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                                <i class="fas fa-times"></i> Remove
                            </button>
                            <div class="section-title">
                                <h3>Starting Price / Regular Admission</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="startingprice">Ticket Name & Price</label>
                                        <input
                                            type="text"
                                            id="startingprice"
                                            name="startingprice"
                                            value="Regular Admission, 5000"
                                            placeholder="Regular Admission, 5000"
                                        />
                                        <span class="help-text">Format: Name, Price (e.g. "Regular Admission, 5000")</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-section">
                            <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                                <i class="fas fa-times"></i> Remove
                            </button>
                            <div class="section-title">
                                <h3>Early Bird Tickets</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="earlybirds">Ticket Name & Price</label>
                                        <input
                                            type="text"
                                            id="earlybirds"
                                            name="earlybirds"
                                            value="Early Bird, 3500"
                                            placeholder="Early Bird, 3500"
                                        />
                                        <span class="help-text">Format: Name, Price (e.g. "Early Bird, 3500")</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-section">
                            <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                                <i class="fas fa-times"></i> Remove
                            </button>
                            <div class="section-title">
                                <h3>Ticket Option 2</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="tabletwo">Ticket Name & Price</label>
                                        <input
                                            type="text"
                                            id="tabletwo"
                                            name="tabletwo"
                                            value="VIP Pass, 15000"
                                            placeholder="Ticket Name, Price"
                                        />
                                        <span class="help-text">Format: Name, Price (e.g. "VIP, 10000")</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="add-section-btn" onclick="addTicketSection()">
                        <i class="fas fa-plus"></i> Add More Ticket Options
                    </button>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-table"></i> Table Options (Optional)</h2>
                    <p class="help-text">If your event has table options, enter them below.</p>

                    <div id="tablesContainer">
                        <div class="ticket-section">
                            <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                                <i class="fas fa-times"></i> Remove
                            </button>
                            <div class="section-title">
                                <h3>Table Option 1</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="tableone">Table Name & Price</label>
                                        <input
                                            type="text"
                                            id="tableone"
                                            name="tableone"
                                            value="VIP Table, 50000"
                                            placeholder="VIP Table, 20000"
                                        />
                                        <span class="help-text">Format: Name, Price (e.g. "VIP Table, 20000")</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="add-section-btn" onclick="addTableSection()">
                        <i class="fas fa-plus"></i> Add Table Option
                    </button>
                </div>
    </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Create Event
                <span class="loader"></span>
            </button>
        </form>
    </div>

<script>
        // Image preview functionality
    function previewImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        if (!previewContainer) return;

        // Clear the preview container
        previewContainer.innerHTML = '';

        const url = input.value.trim();
        if (!url) {
            previewContainer.innerHTML = '<div class="preview-placeholder">Image preview will appear here</div>';
            return;
        }

        // Create image element
        const img = document.createElement('img');
        img.onerror = function() {
                previewContainer.innerHTML = '<div class="preview-placeholder" style="color: var(--error-color);">Invalid image URL</div>';
        };

        img.onload = function() {
            previewContainer.innerHTML = '';
            previewContainer.appendChild(img);
        };

        img.src = url;
    }

        // Track character count for the description
    document.addEventListener('DOMContentLoaded', function() {
            const descriptionField = document.getElementById('description');
            const countDisplay = document.getElementById('descriptionCount');

            // Update character count on load
            if(descriptionField && countDisplay) {
                countDisplay.textContent = descriptionField.value.length;

                // Update count when typing
                descriptionField.addEventListener('input', function() {
                    countDisplay.textContent = this.value.length;

                    // Warn when approaching limit
                    if(this.value.length > 230) {
                        countDisplay.style.color = '#e74c3c';
                    } else {
                        countDisplay.style.color = '';
                    }
                });
            }

            // Initialize previews on page load
        const eventImageInput = document.querySelector('input[name="image"]');
        const heroImageInput = document.querySelector('input[name="heroimage"]');

        if (eventImageInput.value) {
            previewImage(eventImageInput, 'event-image-preview');
        }

        if (heroImageInput.value) {
            previewImage(heroImageInput, 'hero-image-preview');
        }

            // Form submission handler
            const form = document.getElementById('eventForm');
            const submitBtn = document.getElementById('submitBtn');

            if(form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Add loading state to button
                    submitBtn.classList.add('loading');
                    submitBtn.innerHTML = 'Creating Event... <span class="loader"></span>';

                    // Form will submit normally
                });
            }
        });

        // Add more ticket sections
        let ticketCount = 1;
        function addTicketSection() {
            ticketCount++;
            const tableNames = ['tabletwo', 'tablethree', 'tablefour', 'tablefive', 'tablesix', 'tableseven', 'tableeight'];
            const tableName = tableNames[ticketCount - 2] || `table${ticketCount}`;

            const container = document.getElementById('tableTicketsContainer');
            const section = document.createElement('div');
            section.className = 'ticket-section';
            section.innerHTML = `
                <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                    <i class="fas fa-times"></i> Remove
                </button>
                <div class="section-title">
                    <h3>Ticket Option ${ticketCount}</h3>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="${tableName}">Ticket Name & Price</label>
                            <input
                                type="text"
                                id="${tableName}"
                                name="${tableName}"
                                placeholder="Ticket Name, Price"
                            />
                            <span class="help-text">Format: Name, Price (e.g. "VIP, 10000")</span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(section);
        }

        // Add table sections
        let tableCount = 1;
        function addTableSection() {
            tableCount++;
            const tableNames = ['tabletwo', 'tablethree', 'tablefour', 'tablefive', 'tablesix', 'tableseven', 'tableeight'];
            const tableName = tableNames[tableCount - 2] || `tableExtra${tableCount}`;

            const container = document.getElementById('tablesContainer');
            const section = document.createElement('div');
            section.className = 'ticket-section';
            section.innerHTML = `
                <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                    <i class="fas fa-times"></i> Remove
                </button>
                <div class="section-title">
                    <h3>Table Option ${tableCount}</h3>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="${tableName}">Table Name & Price</label>
                            <input
                                type="text"
                                id="${tableName}"
                                name="${tableName}"
                                placeholder="Table Name, Price"
                            />
                            <span class="help-text">Format: Name, Price (e.g. "Gold Table, 50000")</span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(section);
        }

        // Remove a section
        function removeSection(button) {
            const section = button.closest('.ticket-section');
            section.remove();
        }

        // Add advanced ticket types
        let advancedTicketCount = 1;
        function addAdvancedTicketSection() {
            advancedTicketCount++;

            const container = document.getElementById('advancedTicketContainer');
            const section = document.createElement('div');
            section.className = 'ticket-section';
            section.innerHTML = `
                <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                    <i class="fas fa-times"></i> Remove
                </button>
                <div class="section-title">
                    <h3>Ticket Type ${advancedTicketCount}</h3>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="ticket_name_${advancedTicketCount}">Ticket Name</label>
                            <input
                                type="text"
                                id="ticket_name_${advancedTicketCount}"
                                name="ticket_name[]"
                                placeholder="VIP Ticket"
                                required
                            />
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="ticket_price_${advancedTicketCount}">Price</label>
                            <input
                                type="number"
                                id="ticket_price_${advancedTicketCount}"
                                name="ticket_price[]"
                                placeholder="15000"
                                min="0"
                                step="100"
                                required
                            />
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="ticket_description_${advancedTicketCount}">Description</label>
                            <textarea
                                id="ticket_description_${advancedTicketCount}"
                                name="ticket_description[]"
                                placeholder="Premium seating with complimentary drinks"
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="ticket_capacity_${advancedTicketCount}">Capacity (leave empty for unlimited)</label>
                            <input
                                type="number"
                                id="ticket_capacity_${advancedTicketCount}"
                                name="ticket_capacity[]"
                                placeholder="Leave empty for unlimited"
                                min="1"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="ticket_sales_start_${advancedTicketCount}">Sales Start Date</label>
                            <input
                                type="datetime-local"
                                id="ticket_sales_start_${advancedTicketCount}"
                                name="ticket_sales_start[]"
                                value="{{ date('Y-m-d\\TH:i') }}"
                            />
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="ticket_sales_end_${advancedTicketCount}">Sales End Date</label>
                            <input
                                type="datetime-local"
                                id="ticket_sales_end_${advancedTicketCount}"
                                name="ticket_sales_end[]"
                                value="{{ date('Y-m-d\\TH:i', strtotime('+2 months')) }}"
                            />
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(section);
        }
</script>
</body>
</html>
