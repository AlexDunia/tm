@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Payment Security Logs</h1>
        <p class="admin-subtitle">
            Monitor unauthorized access attempts and track payment verification activity
        </p>
    </div>

    <div class="admin-card">
        <div class="logs-header">
            <div class="logs-summary">
                <div class="summary-item">
                    <div class="summary-title">Total Logs</div>
                    <div class="summary-value">{{ count($logs) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-title">Unauthorized</div>
                    <div class="summary-value">{{ count(array_filter($logs, function($log) { return isset($log['is_unauthorized']); })) }}</div>
                </div>
            </div>
            <div class="logs-actions">
                <a href="{{ route('home') }}" class="back-btn">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>

        @if(empty($logs))
            <div class="no-logs">
                <div class="no-data-icon">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <h2>No Security Logs Found</h2>
                <p>There are no payment security logs to display at this time.</p>
            </div>
        @else
            <div class="logs-table-container">
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="{{ isset($log['is_unauthorized']) ? 'unauthorized' : '' }}">
                                <td class="log-time">{{ $log['timestamp'] }}</td>
                                <td class="log-status">
                                    @if(isset($log['is_unauthorized']))
                                        <span class="status-badge unauthorized">
                                            <i class="fa-solid fa-ban"></i> Unauthorized
                                        </span>
                                    @else
                                        <span class="status-badge verified">
                                            <i class="fa-solid fa-check"></i> Verified
                                        </span>
                                    @endif
                                </td>
                                <td class="log-ip">
                                    @php
                                        preg_match('/ip":\s*"([^"]+)"/', $log['entry'], $ipMatch);
                                        $ip = isset($ipMatch[1]) ? $ipMatch[1] : 'Unknown';
                                    @endphp
                                    {{ $ip }}
                                </td>
                                <td class="log-details">
                                    <button class="details-toggle" onclick="toggleDetails(this)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                    <div class="log-details-content">
                                        <pre>{{ $log['entry'] }}</pre>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleDetails(button) {
        const detailsContent = button.nextElementSibling;
        const isVisible = detailsContent.style.display === 'block';

        // Hide all other open details
        document.querySelectorAll('.log-details-content').forEach(content => {
            content.style.display = 'none';
        });

        document.querySelectorAll('.details-toggle').forEach(btn => {
            btn.innerHTML = '<i class="fa-solid fa-eye"></i> View';
        });

        // Toggle this detail
        if (!isVisible) {
            detailsContent.style.display = 'block';
            button.innerHTML = '<i class="fa-solid fa-eye-slash"></i> Hide';
        }
    }
</script>

<style>
    .admin-container {
        width: 95%;
        max-width: 1200px;
        margin: 40px auto;
    }

    .admin-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .admin-header h1 {
        font-size: 32px;
        color: #ffffff;
        margin-bottom: 10px;
    }

    .admin-subtitle {
        color: rgba(255, 255, 255, 0.7);
        font-size: 16px;
    }

    .admin-card {
        background: rgba(37, 36, 50, 0.8);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(192, 72, 136, 0.3);
        padding: 30px;
    }

    .logs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .logs-summary {
        display: flex;
        gap: 20px;
    }

    .summary-item {
        background: rgba(50, 49, 66, 0.6);
        border-radius: 8px;
        padding: 15px;
        min-width: 120px;
        text-align: center;
    }

    .summary-title {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 5px;
    }

    .summary-value {
        font-size: 24px;
        font-weight: bold;
        color: #ffffff;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 15px;
        background: rgba(192, 72, 136, 0.8);
        color: white !important;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: #C04888;
        transform: translateY(-2px);
    }

    .no-logs {
        text-align: center;
        padding: 50px 0;
    }

    .no-data-icon {
        font-size: 60px;
        color: rgba(192, 72, 136, 0.5);
        margin-bottom: 20px;
    }

    .no-logs h2 {
        font-size: 24px;
        color: #ffffff;
        margin-bottom: 10px;
    }

    .no-logs p {
        color: rgba(255, 255, 255, 0.7);
    }

    .logs-table-container {
        overflow-x: auto;
    }

    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .logs-table thead th {
        background: rgba(50, 49, 66, 0.8);
        padding: 12px 15px;
        text-align: left;
        color: #fff;
        font-weight: 600;
        border-bottom: 2px solid rgba(192, 72, 136, 0.5);
    }

    .logs-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }

    .logs-table tbody tr:hover {
        background: rgba(50, 49, 66, 0.5);
    }

    .logs-table tbody tr.unauthorized {
        background: rgba(220, 53, 69, 0.1);
    }

    .logs-table tbody tr.unauthorized:hover {
        background: rgba(220, 53, 69, 0.2);
    }

    .logs-table td {
        padding: 12px 15px;
        color: rgba(255, 255, 255, 0.8);
    }

    .log-time {
        white-space: nowrap;
        color: #C04888;
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.verified {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }

    .status-badge.unauthorized {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .log-ip {
        font-family: monospace;
    }

    .details-toggle {
        background: transparent;
        border: 1px solid #C04888;
        color: #C04888;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .details-toggle:hover {
        background: rgba(192, 72, 136, 0.1);
    }

    .log-details-content {
        display: none;
        margin-top: 10px;
        background: rgba(0, 0, 0, 0.3);
        padding: 10px;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
    }

    .log-details-content pre {
        white-space: pre-wrap;
        word-break: break-all;
        font-family: monospace;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
    }

    @media (max-width: 768px) {
        .logs-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .logs-table thead {
            display: none;
        }

        .logs-table, .logs-table tbody, .logs-table tr, .logs-table td {
            display: block;
            width: 100%;
        }

        .logs-table tr {
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px;
        }

        .logs-table td {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logs-table td:before {
            content: attr(data-label);
            font-weight: bold;
            color: rgba(255, 255, 255, 0.5);
            margin-right: 10px;
        }

        .log-details-content {
            width: 100%;
        }
    }
</style>
@endsection
