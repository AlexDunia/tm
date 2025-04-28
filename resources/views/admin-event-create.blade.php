<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #C04888;
            --primary-light: rgba(192, 72, 136, 0.1);
            --primary-hover: #a53a70;
            --dark-bg: #1e1d27;
            --card-bg: #252431;
            --card-bg-hover: #2a2936;
            --text: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.5);
            --border: rgba(255, 255, 255, 0.08);
            --shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            --input-bg: rgba(0, 0, 0, 0.2);
            --subtle-bg: rgba(255, 255, 255, 0.03);
            --success: #00C896;
            --info: #0F8CFF;
            --warning: #FFB800;
            --danger: #FF5470;
            --gradient: linear-gradient(135deg, #C04888 0%, #8E2DE2 100%);
        }

        body {
            background: var(--dark-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-form-container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 20px;
            background: transparent;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 0 20px 0;
            border-bottom: 1px solid var(--border);
        }

        .page-title {
            margin: 0;
        }

        .page-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 8px 0;
        }

        .page-title p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 16px;
        }

        .form-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient);
            opacity: 0.8;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .form-section {
            border: none;
            padding: 0;
            background: transparent;
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .section-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .section-header h2 i {
            color: var(--primary);
            margin-right: 8px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }

        .form-group {
            flex: 1 0 30%;
            margin: 0 12px 24px;
            min-width: 260px;
        }

        .form-group.full-width {
            flex: 1 0 100%;
            margin: 0 12px 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .form-group label.required::after {
            content: " *";
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(192, 72, 136, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-error {
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
            font-weight: 500;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--input-bg);
            border: 1px dashed var(--border);
            border-radius: 8px;
            height: 160px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .file-upload-label:hover {
            background: rgba(0, 0, 0, 0.25);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .file-upload-label div {
            text-align: center;
            z-index: 1;
        }

        .file-upload-label i {
            display: block;
            font-size: 28px;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .file-upload-label span {
            display: block;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 3;
        }

        .file-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 2;
        }

        .file-preview-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 2;
        }

        .file-preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 3;
        }

        .file-upload-label:hover .file-preview-overlay {
            opacity: 1;
        }

        .file-preview-name {
            color: white;
            font-size: 13px;
            max-width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background: rgba(0, 0, 0, 0.7);
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .file-preview-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .file-preview-icon i {
            color: white;
            font-size: 18px;
            margin: 0;
        }

        .ticket-container {
            background: var(--card-bg-hover);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .ticket-container:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            line-height: 1;
            outline: none;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(192, 72, 136, 0.3);
        }

        .btn-secondary {
            background: var(--subtle-bg);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .btn i {
            margin-right: 8px;
        }

        .submit-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
            margin-top: 20px;
        }

        .submit-container .btn {
            padding: 14px 36px;
            font-size: 16px;
            background: var(--gradient);
            min-width: 200px;
        }

        .submit-container .btn:hover {
            background: linear-gradient(135deg, #a53a70 0%, #7928c9 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(192, 72, 136, 0.4);
        }

        .submit-helper {
            margin-top: 10px;
            font-size: 13px;
            color: var(--text-muted);
            text-align: center;
        }

        /* Custom select styling */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='rgba(255, 255, 255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        /* Price inputs styling */
        .price-input {
            position: relative;
        }

        .price-input .form-control {
            padding-left: 30px;
        }

        .price-input::before {
            content: "$";
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-form-container {
                margin: 20px auto;
                padding: 0 15px;
            }

            .form-card {
                padding: 20px;
            }

            .form-group {
                flex: 1 0 100%;
                min-width: 100%;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .submit-container .btn {
                width: 100%;
            }
        }

        /* Additional styles for input focus and error states */
        .form-control {
            /* ... existing code ... */
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(192, 72, 136, 0.15);
            outline: none;
        }

        .form-control.error {
            border-color: var(--danger);
            background-color: rgba(255, 84, 112, 0.05);
        }

        .form-control.error:focus {
            box-shadow: 0 0 0 3px rgba(255, 84, 112, 0.15);
        }

        /* Add this to create an animated background for drop targets */
        .file-upload-label.dragover {
            background: rgba(192, 72, 136, 0.05);
            border-color: var(--primary);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(192, 72, 136, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(192, 72, 136, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(192, 72, 136, 0);
            }
        }

        /* Progress indicator for form sections */
        .form-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .progress-step {
            display: flex;
            align-items: center;
            color: var(--text-muted);
            font-size: 14px;
            margin-right: 20px;
            white-space: nowrap;
        }

        .progress-step.active {
            color: var(--text);
        }

        .progress-step i {
            margin-right: 8px;
            font-size: 18px;
        }

        .progress-step.completed i {
            color: var(--success);
        }

        .progress-step.active i {
            color: var(--primary);
        }

        /* Input with icons */
        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
        }

        .input-icon .form-control {
            padding-left: 40px;
        }

        /* Form helper text */
        .form-helper {
            display: flex;
            align-items: center;
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .form-helper i {
            margin-right: 6px;
            font-size: 14px;
            color: var(--info);
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-form-container">
            <div class="page-header">
                <div class="page-title">
                    <h1>Create New Event</h1>
                    <p>Craft a memorable experience with complete details</p>
                </div>
                <a href="/admin/events" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Events
                </a>
            </div>

            <!-- Add progress indicator after the page header -->
            <div class="form-progress">
                <div class="progress-step active completed">
                    <i class="fas fa-info-circle"></i>
                    <span>Basic Info</span>
                </div>
                <div class="progress-step active">
                    <i class="fas fa-image"></i>
                    <span>Visuals</span>
                </div>
                <div class="progress-step">
                    <i class="fas fa-tag"></i>
                    <span>Pricing</span>
                </div>
                <div class="progress-step">
                    <i class="fas fa-chair"></i>
                    <span>Tables</span>
                </div>
                <div class="progress-step">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Tickets</span>
                </div>
            </div>

            <form method="post" action="/admin/events/store" enctype="multipart/form-data" id="eventForm">
                @csrf

                <div class="form-card">
                    <div class="section-header">
                        <h2><i class="fas fa-info-circle"></i> Basic Event Information</h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="required">Event Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control" placeholder="Enter event name">
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control">
                                <option value="">Select Category</option>
                                <option value="Music">Music</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Sports">Sports</option>
                                <option value="Theater">Theater</option>
                                <option value="Arts">Arts</option>
                                <option value="Food">Food</option>
                                <option value="Networking">Networking</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('category')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location" class="required">Location *</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" required class="form-control" placeholder="Venue name or address">
                            @error('location')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date" class="required">Event Date *</label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="text" id="date" name="date" value="{{ old('date') }}" placeholder="e.g. December 15 @6:30pm" required class="form-control">
                            </div>
                            @error('date')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="time">Event Time</label>
                            <div class="input-icon">
                                <i class="fas fa-clock"></i>
                                <input type="text" id="time" name="time" value="{{ old('time') }}" class="form-control" placeholder="e.g. 6:30 PM - 10:30 PM">
                            </div>
                            @error('time')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="enddate">End Date</label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="text" id="enddate" name="enddate" value="{{ old('enddate') }}" placeholder="e.g. December 16 @10:00pm" class="form-control">
                            </div>
                            @error('enddate')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="description" class="required">Event Description *</label>
                            <textarea id="description" name="description" rows="5" required class="form-control" placeholder="Provide a detailed description of your event">{{ old('description') }}</textarea>
                            <div class="form-helper">
                                <i class="fas fa-info-circle"></i>
                                <span>Provide clear details about what attendees can expect at your event</span>
                            </div>
                            @error('description')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="section-header">
                        <h2><i class="fas fa-image"></i> Event Visuals</h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="image" class="required">Event Thumbnail</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <div>
                                        <i class="fas fa-upload"></i>
                                        <span>Choose a file or drag it here</span>
                                    </div>
                                    <input type="file" id="image" name="image" accept="image/*">
                                    <div class="file-preview-overlay">
                                        <div class="file-preview-icon">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <span>Change image</span>
                                    </div>
                                </label>
                            </div>
                            @error('image')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="heroimage" class="required">Hero Banner Image</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <div>
                                        <i class="fas fa-upload"></i>
                                        <span>Choose a file or drag it here</span>
                                    </div>
                                    <input type="file" id="heroimage" name="heroimage" accept="image/*">
                                    <div class="file-preview-overlay">
                                        <div class="file-preview-icon">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <span>Change image</span>
                                    </div>
                                </label>
                            </div>
                            @error('heroimage')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="herolink" class="required">Hero Link *</label>
                            <input type="url" id="herolink" name="herolink" value="{{ old('herolink', 'https://ticketmars.com/events/') }}" required class="form-control" placeholder="Enter URL for the hero banner">
                            @error('herolink')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="section-header">
                        <h2><i class="fas fa-tag"></i> Pricing Information</h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startingprice">Starting Price</label>
                            <div class="price-input">
                                <input type="text" id="startingprice" name="startingprice" value="{{ old('startingprice') }}" class="form-control" placeholder="0.00">
                            </div>
                            @error('startingprice')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="earlybirds">Early Bird Price</label>
                            <div class="price-input">
                                <input type="text" id="earlybirds" name="earlybirds" value="{{ old('earlybirds') }}" class="form-control" placeholder="0.00">
                            </div>
                            @error('earlybirds')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="section-header">
                        <h2><i class="fas fa-chair"></i> Table Pricing (Optional)</h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tableone">Table for One</label>
                            <div class="price-input">
                                <input type="text" id="tableone" name="tableone" value="{{ old('tableone') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tabletwo">Table for Two</label>
                            <div class="price-input">
                                <input type="text" id="tabletwo" name="tabletwo" value="{{ old('tabletwo') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tablethree">Table for Three</label>
                            <div class="price-input">
                                <input type="text" id="tablethree" name="tablethree" value="{{ old('tablethree') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tablefour">Table for Four</label>
                            <div class="price-input">
                                <input type="text" id="tablefour" name="tablefour" value="{{ old('tablefour') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tablefive">Table for Five</label>
                            <div class="price-input">
                                <input type="text" id="tablefive" name="tablefive" value="{{ old('tablefive') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tablesix">Table for Six</label>
                            <div class="price-input">
                                <input type="text" id="tablesix" name="tablesix" value="{{ old('tablesix') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tableseven">Table for Seven</label>
                            <div class="price-input">
                                <input type="text" id="tableseven" name="tableseven" value="{{ old('tableseven') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tableeight">Table for Eight</label>
                            <div class="price-input">
                                <input type="text" id="tableeight" name="tableeight" value="{{ old('tableeight') }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="ticket-header">
                        <h2><i class="fas fa-ticket-alt"></i> Ticket Types</h2>
                        <button type="button" class="btn btn-secondary btn-sm" id="addTicketType">
                            <i class="fas fa-plus"></i> Add Ticket Type
                        </button>
                    </div>

                    <div id="ticketTypes">
                        <!-- Ticket type forms will be added dynamically -->
                        <div class="ticket-container">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tickets[0][name]">Ticket Name *</label>
                                    <input type="text" name="tickets[0][name]" required class="form-control" placeholder="e.g. General Admission">
                                </div>

                                <div class="form-group">
                                    <label for="tickets[0][price]">Price *</label>
                                    <div class="price-input">
                                        <input type="number" name="tickets[0][price]" step="0.01" required class="form-control" placeholder="0.00">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tickets[0][capacity]">Capacity</label>
                                    <input type="number" name="tickets[0][capacity]" class="form-control" placeholder="e.g. 100">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tickets[0][sales_start]">Sales Start Date</label>
                                    <input type="datetime-local" name="tickets[0][sales_start]" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="tickets[0][sales_end]">Sales End Date</label>
                                    <input type="datetime-local" name="tickets[0][sales_end]" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="tickets[0][is_active]">Status</label>
                                    <select name="tickets[0][is_active]" class="form-control">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label for="tickets[0][description]">Ticket Description</label>
                                    <textarea name="tickets[0][description]" rows="3" class="form-control" placeholder="Describe what's included with this ticket type"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="submit-container">
                    <button type="submit" class="btn">
                        <i class="fas fa-check"></i> Create Event
                    </button>
                    <p class="submit-helper">By clicking Create Event, you confirm that all provided information is accurate.</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ticketCount = 1;
            const ticketContainer = document.getElementById('ticketTypes');
            const addButton = document.getElementById('addTicketType');

            // Prefill some form fields with sample data
            document.getElementById('name').value = document.getElementById('name').value || "Summer Music Festival 2023";
            document.getElementById('location').value = document.getElementById('location').value || "Central Park, New York";
            document.getElementById('date').value = document.getElementById('date').value || "July 15, 2023";
            document.getElementById('time').value = document.getElementById('time').value || "2:00 PM - 11:00 PM";
            document.getElementById('category').value = document.getElementById('category').value || "Music";
            document.getElementById('description').value = document.getElementById('description').value || "Join us for an unforgettable day of music featuring top artists from around the world. Food, drinks, and amazing atmosphere guaranteed!";
            document.getElementById('startingprice').value = document.getElementById('startingprice').value || "49.99";
            document.getElementById('earlybirds').value = document.getElementById('earlybirds').value || "39.99";

            // File upload preview with drag and drop support
            const fileInputs = document.querySelectorAll('input[type="file"]');

            fileInputs.forEach(input => {
                const label = input.closest('.file-upload-label');

                // Drag over event
                label.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                // Drag leave event
                label.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });

                // Drop event
                label.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');

                    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                        input.files = e.dataTransfer.files;
                        handleFileSelect(input);
                    }
                });

                // Change event
                input.addEventListener('change', function() {
                    handleFileSelect(this);
                });
            });

            function handleFileSelect(input) {
                const label = input.closest('.file-upload-label');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Remove existing preview if any
                        const existingPreview = label.querySelector('.file-preview');
                        if (existingPreview) {
                            label.removeChild(existingPreview);
                        }

                        // Create new preview
                        const preview = document.createElement('div');
                        preview.className = 'file-preview';

                        // If the file is an image, display it
                        if (file.type.match('image.*')) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'file-preview-image';
                            preview.appendChild(img);
                        } else {
                            // For non-image files (fallback, shouldn't happen with accept="image/*")
                            const icon = document.createElement('div');
                            icon.className = 'file-preview-icon';
                            icon.innerHTML = '<i class="fas fa-file"></i>';
                            preview.appendChild(icon);
                        }

                        // Add filename
                        const nameSpan = document.createElement('span');
                        nameSpan.className = 'file-preview-name';
                        nameSpan.textContent = file.name;
                        preview.appendChild(nameSpan);

                        // Hide the upload instructions
                        const uploadInstructions = label.querySelector('div:not(.file-preview-overlay)');
                        if (uploadInstructions) {
                            uploadInstructions.style.display = 'none';
                        }

                        // Show the overlay with change instructions
                        const overlay = label.querySelector('.file-preview-overlay');
                        if (overlay) {
                            overlay.style.display = 'flex';
                        }

                        // Add the preview
                        label.appendChild(preview);
                    };

                    reader.readAsDataURL(file);
                }
            }

            addButton.addEventListener('click', function() {
                const newTicket = document.createElement('div');
                newTicket.className = 'ticket-container';
                newTicket.innerHTML = `
                    <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
                        <button type="button" class="btn btn-secondary btn-sm remove-ticket">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tickets[${ticketCount}][name]">Ticket Name *</label>
                            <input type="text" name="tickets[${ticketCount}][name]" required class="form-control" placeholder="e.g. VIP Access">
                        </div>

                        <div class="form-group">
                            <label for="tickets[${ticketCount}][price]">Price *</label>
                            <div class="price-input">
                                <input type="number" name="tickets[${ticketCount}][price]" step="0.01" required class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tickets[${ticketCount}][capacity]">Capacity</label>
                            <input type="number" name="tickets[${ticketCount}][capacity]" class="form-control" placeholder="e.g. 50">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tickets[${ticketCount}][sales_start]">Sales Start Date</label>
                            <input type="datetime-local" name="tickets[${ticketCount}][sales_start]" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="tickets[${ticketCount}][sales_end]">Sales End Date</label>
                            <input type="datetime-local" name="tickets[${ticketCount}][sales_end]" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="tickets[${ticketCount}][is_active]">Status</label>
                            <select name="tickets[${ticketCount}][is_active]" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="tickets[${ticketCount}][description]">Ticket Description</label>
                            <textarea name="tickets[${ticketCount}][description]" rows="3" class="form-control" placeholder="Describe what's included with this ticket type"></textarea>
                        </div>
                    </div>
                `;

                ticketContainer.appendChild(newTicket);
                ticketCount++;

                // Add event listener to the remove button
                const removeButtons = document.querySelectorAll('.remove-ticket');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('.ticket-container').remove();
                    });
                });
            });

            // Form validation
            const form = document.getElementById('eventForm');
            form.addEventListener('submit', function(event) {
                const requiredFields = form.querySelectorAll('[required]');
                let valid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('error');
                        valid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });

                if (!valid) {
                    event.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });

            // Initialize first ticket with sample data
            document.querySelector('input[name="tickets[0][name]"]').value = 'General Admission';
            document.querySelector('input[name="tickets[0][price]"]').value = '49.99';
            document.querySelector('input[name="tickets[0][capacity]"]').value = '500';
            document.querySelector('textarea[name="tickets[0][description]"]').value = 'Standard festival entry with access to all main stages.';
        });
    </script>
</body>
</html>
