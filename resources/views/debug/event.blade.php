<!DOCTYPE html>
<html>
<head>
    <title>Debug: {{ $event->name ?? 'Event' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1, h2, h3 {
            color: #222;
        }
        .debug-section {
            background: white;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .debug-info {
            background: #f0f8ff;
            border-left: 4px solid #0066cc;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .event-image {
            max-width: 300px;
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .links {
            margin-top: 20px;
        }
        .links a {
            display: inline-block;
            margin-right: 15px;
            color: #0066cc;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Event Debug View</h1>

    <div class="debug-info">
        <p><strong>Request Info:</strong></p>
        <ul>
            <li><strong>Original Query:</strong> {{ $debugInfo['original_query'] ?? 'N/A' }}</li>
            <li><strong>Cleaned Query:</strong> {{ $debugInfo['cleaned_query'] ?? 'N/A' }}</li>
            <li><strong>URL:</strong> {{ $debugInfo['request_url'] ?? 'N/A' }}</li>
            <li><strong>Timestamp:</strong> {{ $debugInfo['timestamp'] ?? now() }}</li>
        </ul>
    </div>

    <div class="debug-section">
        <h2>Event Details</h2>

        @if($event)
            <table>
                <tr>
                    <th>Property</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>{{ $event->id }}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{ $event->name }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{ $event->description ?? 'No description' }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ $event->date ?? 'No date' }}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>{{ $event->location ?? 'No location' }}</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>{{ $event->category ?? 'No category' }}</td>
                </tr>
                <tr>
                    <td>Image</td>
                    <td>
                        @if($event->image)
                            <div>{{ $event->image }}</div>
                            <img class="event-image" src="{{ str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" alt="{{ $event->name }}">
                        @else
                            No image
                        @endif
                    </td>
                </tr>
            </table>

            <h3>All Event Properties</h3>
            <pre>{{ print_r($event->toArray(), true) }}</pre>
        @else
            <p>No event data found.</p>
        @endif
    </div>

    <div class="links">
        <a href="{{ url('/') }}">Back to Home</a>
        <a href="{{ url()->current() }}">Refresh</a>
        <a href="{{ url()->current() }}?view=regular">View Regular Page</a>
    </div>
</body>
</html>
