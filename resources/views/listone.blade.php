@extends('layouts.app')

@section('content')
<style>
/* Premium Animated Loader */
.premium-loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(18, 18, 26, 0.98);
    z-index: 99999;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    transition: opacity 0.7s ease, visibility 0.7s ease;
}

.premium-loader-content {
    position: relative;
    text-align: center;
}

.premium-spinner {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    position: relative;
}

.premium-spinner:before,
.premium-spinner:after {
    content: '';
    position: absolute;
    border-radius: 50%;
    animation-duration: 1.8s;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
    filter: drop-shadow(0 0 10px rgba(192, 72, 136, 0.8));
}

.premium-spinner:before {
    width: 100%;
    height: 100%;
    border: 3px solid transparent;
    border-top-color: #C04888;
    border-left-color: #C04888;
    animation-name: premium-spin-clockwise;
    box-shadow: 0 0 20px rgba(192, 72, 136, 0.5);
}

.premium-spinner:after {
    width: 80%;
    height: 80%;
    border: 3px solid transparent;
    border-right-color: #FECC01;
    border-bottom-color: #FECC01;
    top: 10%;
    left: 10%;
    animation-name: premium-spin-counter-clockwise;
    box-shadow: 0 0 10px rgba(254, 204, 1, 0.5);
}

@keyframes premium-spin-clockwise {
    0% {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(360deg);
    }
    100% {
        transform: rotate(720deg);
    }
}

@keyframes premium-spin-counter-clockwise {
    0% {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(-360deg);
    }
    100% {
        transform: rotate(-720deg);
    }
}

.premium-loader-text {
    font-size: 18px;
    font-weight: 600;
    color: white;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-top: 20px;
    opacity: 0;
    animation: text-fade 2s ease infinite;
}

@keyframes text-fade {
    0%, 100% {
        opacity: 0.3;
    }
    50% {
        opacity: 1;
    }
}

.premium-progress {
    width: 100px;
    height: 3px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    margin: 10px auto 0;
    overflow: hidden;
}

.premium-progress-bar {
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, #C04888, #FECC01);
    border-radius: 3px;
    transition: width 0.5s ease;
    animation: progress-pulse 1.5s ease infinite;
}

@keyframes progress-pulse {
    0% {
        opacity: 0.6;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0.6;
    }
}

/* No loading or transitions */
.pricing-section {
    opacity: 1;
    visibility: visible;
}

/* Hide standalone cards outside the ticket grid */
.spotify-ticket-card:not(.ticket-grid .spotify-ticket-card) {
    display: none !important;
}

/* Hide any preview cards that might be generated */
body > .general-admission-card,
body > [class*="ticket-card"],
body > [class*="admission-card"],
.main-container > .general-admission-card,
.main-container > [class*="ticket-card"],
.main-container > [class*="admission-card"],
.content-wrapper > .general-admission-card,
.content-wrapper > [class*="ticket-card"],
.content-wrapper > [class*="admission-card"] {
    display: none !important;
}

/* Prevent layout shifts by setting minimum dimensions */
.ticket-grid {
    min-height: 400px;
}

.direct-quantity-control {
    min-height: 38px;
}

.buy-ticket-btn {
    min-height: 42px;
}

/* Hide any ticket tags */
.ticket-tag {
    display: none !important;
}

/* Spotify-inspired Ticket Grid Styling - Consolidated to remove duplicates */
.ticket-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
    width: 85%;
    margin-left: auto;
    margin-right: auto;
}

/* Center pricing elements */
.price-info {
    text-align: center;
    margin-left: auto;
    margin-right: auto;
}

/* Event details styling - based on the provided design */
.event-date-badge {
    display: flex;
    background-color: rgba(44, 44, 44, 0.9); /* Dark background like before */
    border-radius: 8px;
    padding: 10px 16px;
    gap: 15px;
    width: fit-content;
    margin-bottom: 20px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.event-date-icon, .event-time-icon {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.event-date-icon i, .event-time-icon i {
    color: #C04888;
}

.event-info-container {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.info-box {
    display: flex;
    background-color: rgba(44, 44, 44, 0.9); /* Dark background like before */
    border-radius: 12px;
    padding: 15px;
    gap: 15px;
    align-items: center;
    width: auto;
    min-width: 200px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}

.info-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.15);
}

.info-box .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(192, 72, 136, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #C04888;
}

.info-box .info-content h3 {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
    color: white;
}

.info-box .info-content p {
    margin: 0;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
}

.social-icons {
    display: flex;
    gap: 10px;
}

.social-icons a {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: rgba(192, 72, 136, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #C04888;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid rgba(192, 72, 136, 0.3);
}

.social-icons a:hover {
    background-color: #C04888;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(192, 72, 136, 0.4);
}

/* Countdown styles */
.countdown-overlay {
    background-color: rgba(0, 0, 0, 0.7);
    padding: 20px;
    border-radius: 12px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.countdown-heading {
    color: white;
    text-align: center;
    margin-bottom: 15px;
    font-size: 18px;
    font-weight: 600;
}

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.countdown-item {
    text-align: center;
}

.countdown-value {
    background-color: #C04888;
    color: white;
    border-radius: 8px;
    font-size: 28px;
    font-weight: 700;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 5px;
    box-shadow: 0 4px 10px rgba(192, 72, 136, 0.3);
}

.countdown-label {
    color: white;
    font-size: 12px;
    font-weight: 500;
}

/* Updated ticket card styles to match modern design */
.spotify-ticket-card {
    min-height: 320px;
    background-color: rgba(18, 18, 18, 0.4);
    border-radius: 8px;
    overflow: hidden;
    padding: 20px;
    transition: all 0.3s ease, opacity 0.5s ease, transform 0.5s ease;
    position: relative;
    overflow: hidden;
    color: white;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.spotify-ticket-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.15);
    z-index: 1;
}

.spotify-ticket-card .ticket-tag {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    max-width: 90%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: none !important; /* Hide ticket tags */
}

.spotify-ticket-card .ticket-tag.available {
    background: #28a745;
    color: white;
}

.spotify-ticket-card .ticket-tag.sold-out {
    background: #dc3545;
    color: white;
}

.spotify-ticket-card .ticket-tag.upcoming {
    background: #ffc107;
    color: #000;
}

.spotify-ticket-card .ticket-header {
    margin-bottom: 15px;
    padding-top: 10px;
}

.spotify-ticket-card .ticket-name {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: white;
    letter-spacing: -0.01em;
}

.spotify-ticket-card .ticket-price {
    font-size: 1.8rem;
    font-weight: 800;
    color: #C04888;
    margin-bottom: 15px;
    line-height: 1;
}

.spotify-ticket-card .ticket-description {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 15px;
    flex-grow: 1;
    line-height: 1.4;
}

.spotify-ticket-card .buy-ticket-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(192, 72, 136, 0.25);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: auto;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
    width: 100%;
}

/* Ripple effect */
.spotify-ticket-card .buy-ticket-btn .ripple {
    position: absolute;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.spotify-ticket-card .buy-ticket-btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.spotify-ticket-card .buy-ticket-btn:hover:before {
    left: 100%;
}

.spotify-ticket-card .buy-ticket-btn:hover {
    background: linear-gradient(45deg, #C04888, #d65c9e);
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 4px 10px rgba(192, 72, 136, 0.3);
}

.spotify-ticket-card .buy-ticket-btn:active {
    transform: translateY(0);
}

.spotify-ticket-card .buy-ticket-btn i {
    margin-right: 8px;
}

.spotify-ticket-card .buy-ticket-btn.ticket-selected {
    background: #C04888;
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(192, 72, 136, 0.4);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(192, 72, 136, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(192, 72, 136, 0);
    }
}

.direct-quantity-control {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    padding: 5px 10px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.direct-quantity-input {
    width: 100%;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 14px;
    padding: 5px 0;
    outline: none;
    position: relative;
    opacity: 1;
    cursor: pointer;
}

.direct-quantity-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.direct-quantity-input:focus {
    border-color: #FECC01;
}

.quantity-value-display {
    display: none;
}

/* Remove the ::before pseudo-element that adds "How many?" text */
.quantity-value-display::before {
    content: none;
}

.quantity-number {
    font-weight: 600;
}

.dropdown-toggle {
    display: none;
}

.buy-ticket-btn {
    width: 100%;
    padding: 10px 15px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.buy-ticket-btn.selected {
    background-color: #FECC01;
    color: #000;
}

.buy-ticket-btn:hover {
    background-color: #FECC01;
    color: #000;
    opacity: 0.9;
}

.buy-ticket-btn:disabled {
    background-color: #555;
    cursor: not-allowed;
    opacity: 0.6;
}

.fixed-buy-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(30, 30, 40, 0.95);
    backdrop-filter: blur(10px);
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.2);
    transform: translateY(100%);
    transition: transform 0.3s ease-out;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.fixed-buy-footer.visible {
    transform: translateY(0);
}

.fixed-buy-summary {
    display: flex;
    flex-direction: column;
}

.fixed-buy-count {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 4px;
}

.fixed-buy-total {
    font-size: 18px;
    font-weight: 700;
    color: white;
}

.fixed-buy-total .amount {
    color: #C04888;
}

.fixed-buy-btn {
    background: #C04888;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.fixed-buy-btn:hover {
    background: #d65c9e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(192, 72, 136, 0.3);
}

.fixed-buy-btn i {
    font-size: 18px;
}

.spotify-ticket-card .buy-ticket-btn:hover {
    background-color: #C04888;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(192, 72, 136, 0.3);
}

.spotify-ticket-card .buy-ticket-btn:active {
    transform: translateY(0);
}

.spotify-ticket-card .buy-ticket-btn i {
    margin-right: 8px;
}

.spotify-ticket-card .buy-ticket-btn.ticket-selected {
    background: rgba(192, 72, 136, 0.15);
    border: 1px solid #C04888;
}

.spotify-ticket-card .ticket-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 6px;
    padding: 8px 12px;
}

.spotify-ticket-card .quantity-control {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 30px;
    padding: 5px;
}

.spotify-ticket-card .quantity-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.3);
    color: white;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.spotify-ticket-card .quantity-btn:hover {
    background: #C04888;
    color: white;
}

.spotify-ticket-card .quantity-input,
.spotify-ticket-card .quantity-select {
    width: 40px;
    border: none;
    background: transparent;
    color: white;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    -moz-appearance: textfield;
}

.spotify-ticket-card .quantity-select {
    padding: 0 5px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.spotify-ticket-card .quantity-select:focus,
.spotify-ticket-card .quantity-input:focus {
    outline: none;
}

.spotify-ticket-card .ticket-capacity {
    margin-top: 15px;
    margin-bottom: 15px;
}

.spotify-ticket-card .capacity-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 5px;
}

.spotify-ticket-card .capacity-bar-container {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
}

.spotify-ticket-card .capacity-bar {
    height: 100%;
    background: linear-gradient(90deg, #C04888, #ff6b9d);
    border-radius: 2px;
    box-shadow: 0 2px 5px rgba(192, 72, 136, 0.3);
}

.spotify-ticket-card .capacity-text {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 5px;
    text-align: right;
}

.spotify-ticket-card .unavailable-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
}

.spotify-ticket-card .unavailable-message {
    font-size: 18px;
    font-weight: 700;
    color: white;
    padding: 10px 16px;
    background: rgba(235, 87, 87, 0.7);
    border-radius: 30px;
    transform: rotate(-15deg);
}

/* Section titles and headers */
.pricing-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-title {
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: white;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.pricing-subtitle {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.7);
}

/* Modal styling */
.ticket-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.ticket-modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.ticket-modal {
    background: linear-gradient(145deg, rgba(40, 40, 55, 0.95), rgba(30, 30, 45, 0.98));
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
    transform: translateY(30px);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.ticket-modal-overlay.active .ticket-modal {
    transform: translateY(0);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.close-modal {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.6);
    font-size: 24px;
    cursor: pointer;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    width: 32px;
    height: 32px;
}

.close-modal:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
}

.modal-body {
    padding: 20px;
}

.modal-ticket-details {
    margin-bottom: 25px;
}

.modal-ticket-name {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 8px;
}

.modal-ticket-price {
    font-size: 28px;
    color: #C04888;
    font-weight: 800;
    margin-bottom: 15px;
}

.modal-ticket-description {
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 20px;
}

.modal-quantity-selector {
    background: rgba(255, 255, 255, 0.04);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.06);
}

.quantity-control-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.quantity-label {
    font-size: 16px;
    font-weight: 600;
}

.modal-quantity-control {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: rgba(255, 255, 255, 0.06);
    padding: 4px 8px;
    border-radius: 6px;
}

.modal-quantity-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.3);
    color: white;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-quantity-btn:hover {
    background: #C04888;
}

.modal-quantity-input {
    width: 40px;
    text-align: center;
    background: transparent;
    border: none;
    color: white;
    font-size: 18px;
    font-weight: 600;
}

.modal-footer {
    padding: 12px 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: flex-end;
    align-items: center;
    background: rgba(40, 40, 55, 0.98);
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    position: sticky;
    bottom: 0;
    gap: 12px;
}

.modal-total {
    font-size: 15px;
    font-weight: 600;
    margin-right: auto;
}

.modal-total-amount {
    font-size: 18px;
    color: #C04888;
    font-weight: 700;
}

.add-to-cart-modal-btn {
    background: #FECC01;
    color: black;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.add-to-cart-modal-btn:hover {
    background: #ffdb46;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(254, 204, 1, 0.3);
}

.modal-ticket-list {
    margin-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 15px;
}

.modal-ticket-list-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
    color: rgba(255, 255, 255, 0.9);
}

.other-ticket-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    cursor: pointer;
    transition: all 0.2s;
}

.other-ticket-item:hover {
    background: rgba(255, 255, 255, 0.05);
    padding-left: 10px;
    padding-right: 10px;
    margin-left: -10px;
    margin-right: -10px;
}

.other-ticket-info {
    display: flex;
    flex-direction: column;
}

.other-ticket-name {
    font-size: 14px;
    font-weight: 600;
}

.other-ticket-price {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
}

.other-ticket-action {
    font-size: 12px;
    color: #C04888;
    font-weight: 600;
}

/* Selected ticket styles */
.other-ticket-item.selected {
    background: rgba(192, 72, 136, 0.1);
    border-left: 3px solid #C04888;
    padding-left: 10px;
    margin-left: -10px;
}

.other-ticket-item .selected-qty {
    background: #C04888;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    margin-right: 5px;
}

.add-to-cart-button.disabled {
    background-color: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.4);
    cursor: not-allowed;
}

#selectedTicketsContainer {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 500;
    display: flex;
    align-items: center;
    background: rgba(192, 72, 136, 0.15);
    padding: 4px 10px;
    border-radius: 20px;
}

#selectedTicketsCount {
    color: #C04888;
    font-weight: 700;
    margin: 0 3px;
}

/* Cart summary and checkout button */
.cart-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(40, 40, 55, 0.9);
    padding: 1.25rem;
    border-radius: 12px;
    margin-top: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.summary-details {
    display: flex;
    gap: 1.5rem;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.summary-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.summary-value, .summary-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
}

.summary-total {
    color: #C04888;
}

.add-to-cart-button {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: #C04888;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-to-cart-button:not(.disabled):hover {
    background-color: #d65c9e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(192, 72, 136, 0.3);
}

.cart-actions {
    display: flex;
    gap: 10px;
}

/* Mobile and tablet responsiveness */
@media (max-width: 1200px) {
    .ticket-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .ticket-grid {
        grid-template-columns: repeat(2, 1fr);
    }


    .summary-details {
        width: 100%;
        justify-content: space-between;
    }

    .add-to-cart-button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .ticket-grid {
        grid-template-columns: 1fr;
    }

    .ticket-modal {
        width: 95%;
    }

    .event-info-container {
        flex-direction: column;
    }

    .info-box {
        width: 100%;
    }

    .section-title {
        font-size: 1.75rem;
    }
}

.add-to-cart-modal-btn {
    background: #FECC01;
    color: black;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.add-to-cart-modal-btn:hover {
    background: #ffdb46;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(254, 204, 1, 0.3);
}

.modal-actions {
    display: flex;
    align-items: center;
}

.checkout-modal-btn {
    background: #2a2a2a;
    color: white;
    border: 1px solid #FECC01;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.checkout-modal-btn:hover {
    background: #FECC01;
    color: black;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(254, 204, 1, 0.3);
}

.modal-ticket-list {
    margin-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 15px;
}

#checkoutModalBtn {
    display: none; /* Hide by default */
}

/* Update the updateModalTotal function to control checkout button visibility */
function updateModalTotal() {
    const quantity = parseInt(document.getElementById('modalQuantityInput').value);
    const price = parseFloat(currentTicket.price.toString().replace(/,/g, ''));

    if (!isNaN(price)) {
        const total = quantity * price;
        document.getElementById('modalTotalAmount').textContent = '₦' + total.toLocaleString();
    }

    // Show or hide checkout button based on whether tickets are selected
    const checkoutBtn = document.getElementById('checkoutModalBtn');
    if (quantity > 0 || Object.keys(selectedTickets).length > 0) {
        checkoutBtn.style.display = 'flex';
    } else {
        checkoutBtn.style.display = 'none';
    }
}

// Function to update ticket selection when input changes
function updateTicketSelection(input) {
    const card = input.closest('.spotify-ticket-card');

    // Force numeric input
    let quantity = input.value;

    // Remove any non-numeric characters
    quantity = quantity.replace(/[^0-9]/g, '');

    // Convert to integer
    quantity = parseInt(quantity || 0);

    // Update input value to ensure it's a clean number
    input.value = quantity;

    const ticketId = input.dataset.ticketId;
    const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));

    // Make sure quantity is not negative
    if (quantity < 0) {
        input.value = 0;
        quantity = 0;
    }

    // Update the hidden quantity input
    const hiddenQuantity = card.querySelector('.hidden-quantity');
    if (hiddenQuantity) {
        hiddenQuantity.value = quantity;
    }

    // Update the button text - Changed to "Add to Cart" regardless of selection
    const buyButton = card.querySelector('.buy-ticket-btn');
    if (buyButton) {
        if (quantity > 0) {
            buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
            buyButton.classList.add('ticket-selected');
        } else {
            buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
            buyButton.classList.remove('ticket-selected');
        }
    }

    // Store in the selected tickets object
    const ticketKey = ticketId;
    if (quantity > 0) {
        selectedTickets[ticketKey] = {
            id: ticketId,
            table: null,
            name: card.dataset.ticketName,
            price: card.dataset.ticketPrice,
            quantity: quantity,
            card: card
        };
    } else {
        // Remove if quantity is 0
        delete selectedTickets[ticketKey];
    }

    // Update the cart summary
    updateCartSummary();

    // Update fixed buy footer if it exists
    if (typeof updateFixedBuyFooter === 'function') {
        updateFixedBuyFooter();
    }
}

// Function to update table ticket selection when input changes
function updateTableTicketSelection(input) {
    const card = input.closest('.spotify-ticket-card');
    const quantity = parseInt(input.value) || 0;
    const ticketTable = input.dataset.ticketTable;
    const ticketName = input.dataset.ticketName;
    const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));

    // Make sure quantity is not negative
    if (quantity < 0) {
        input.value = 0;
        return;
    }

    // Update the hidden quantity input
    const hiddenQuantity = card.querySelector('.hidden-quantity');
    if (hiddenQuantity) {
        hiddenQuantity.value = quantity;
    }

    // Update the button text - Use "Add to Cart" regardless of selection
    const buyButton = card.querySelector('.buy-ticket-btn');
    if (buyButton) {
        if (quantity > 0) {
            buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
            buyButton.classList.add('ticket-selected');
        } else {
            buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
            buyButton.classList.remove('ticket-selected');
        }
    }

    // Store in the selected tickets object
    const ticketKey = 'table-' + ticketTable;
    if (quantity > 0) {
        selectedTickets[ticketKey] = {
            id: null,
            table: ticketTable,
            name: ticketName,
            price: ticketPrice,
            quantity: quantity,
            card: card
        };
    } else {
        // Remove if quantity is 0
        delete selectedTickets[ticketKey];
    }

    // Update the cart summary
    updateCartSummary();

    // Update fixed buy footer if it exists
    if (typeof updateFixedBuyFooter === 'function') {
        updateFixedBuyFooter();
    }
}

// Function to update cart summary totals
function updateCartSummary() {
    let totalTickets = 0;
    let totalAmount = 0;

    // Calculate totals based on selected tickets
    Object.values(selectedTickets).forEach(ticket => {
        if (ticket.quantity > 0) {
            totalTickets += ticket.quantity;
            const ticketPrice = parseFloat(ticket.price.toString().replace(/,/g, ''));
            if (!isNaN(ticketPrice)) {
                totalAmount += ticketPrice * ticket.quantity;
            }
        }
    });

    // Skip updating count display as it was removed
    // Update fixed buy footer which is still in the document
    const fixedBuyCount = document.getElementById('fixedBuyCount');
    if (fixedBuyCount) {
        fixedBuyCount.textContent = `${totalTickets} ${totalTickets === 1 ? 'ticket' : 'tickets'} selected`;
    }

    const fixedBuyTotal = document.getElementById('fixedBuyTotal');
    if (fixedBuyTotal) {
        fixedBuyTotal.textContent = `₦${totalAmount.toLocaleString()}`;
    }

    // Show/hide fixed buy footer
    const fixedBuyFooter = document.getElementById('fixedBuyFooter');
    if (fixedBuyFooter) {
        if (totalTickets > 0) {
            fixedBuyFooter.style.display = 'flex';
            fixedBuyFooter.style.transform = 'translateY(0)';
        } else {
            fixedBuyFooter.style.display = 'none';
            fixedBuyFooter.style.transform = 'translateY(100%)';
        }
    }
}

// Function to show a notification when items are added to cart
function showCartNotification(quantity, ticketName) {
    // Create notification element if it doesn't exist
    let notification = document.getElementById('cart-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'cart-notification';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';  // Position at top right
        notification.style.backgroundColor = 'rgba(40, 40, 55, 0.9)';
        notification.style.color = 'white';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = '8px';
        notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.2)';
        notification.style.zIndex = '9999';
        notification.style.display = 'flex';
        notification.style.alignItems = 'center';
        notification.style.gap = '15px';
        notification.style.transform = 'translateY(-100px)';
        notification.style.opacity = '0';
        notification.style.transition = 'all 0.3s ease-out';
        notification.style.border = '1px solid rgba(255, 255, 255, 0.1)';
        document.body.appendChild(notification);
    }

    // Set content of notification
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-check-circle" style="color: #C04888; font-size: 24px;"></i>
            <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Added to Cart</div>
                <div style="font-size: 14px; color: rgba(255, 255, 255, 0.7);">${quantity} ${ticketName} ${quantity === 1 ? 'ticket' : 'tickets'} added</div>
            </div>
        </div>
        <a href="{{ route('cart') }}" style="background: #C04888; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; white-space: nowrap; transition: all 0.2s;">
            View Cart
        </a>
    `;

    // Show notification with animation
    setTimeout(() => {
        notification.style.transform = 'translateY(0)';
        notification.style.opacity = '1';

        // Hide notification after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateY(-100px)';
            notification.style.opacity = '0';
        }, 5000);
    }, 100);
}

// Set the date we're counting down to
var eventDateString = "{{$listonee['date']}}";
var eventTimeString = "{{$listonee['time'] ?? ''}}";
var countDownDate;

// Global variable to track selected tickets
var selectedTickets = {};

// Try to parse the date, handling different formats
try {
    // Create a complete datetime string
    var dateTimeStr = eventDateString;

    // Add time if available (just use the start time if it's a range)
    if (eventTimeString) {
        // If time contains a range (with hyphen), just take the start time
        if (eventTimeString.includes('-')) {
            var timeParts = eventTimeString.split('-');
            eventTimeString = timeParts[0].trim();
        }
        dateTimeStr += ' ' + eventTimeString;
    }

    // Try to parse the combined date and time
    countDownDate = new Date(dateTimeStr).getTime();

    // Check if the date is invalid
    if (isNaN(countDownDate)) {
        // Try to handle special formats like "December 15 @6:30pm"
        if (eventDateString.includes('@')) {
            // Replace @ with space
            var cleanDateStr = eventDateString.replace('@', ' ');
            countDownDate = new Date(cleanDateStr).getTime();
        }
    }

    // If still invalid, use a fallback date (e.g., current date + 30 days)
    if (isNaN(countDownDate)) {
        var fallbackDate = new Date();
        fallbackDate.setDate(fallbackDate.getDate() + 30);
        countDownDate = fallbackDate.getTime();
        console.log("Warning: Could not parse date string. Using fallback date.");
    }
} catch (e) {
    // If all parsing attempts fail, use a fallback date
    var fallbackDate = new Date();
    fallbackDate.setDate(fallbackDate.getDate() + 30);
    countDownDate = fallbackDate.getTime();
    console.log("Error parsing date: " + e.message + ". Using fallback date.");
}

// Update the count down every 1 second
var x = setInterval(function() {
    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Output the result
    document.getElementById("countdown-days").innerHTML = days;
    document.getElementById("countdown-hours").innerHTML = hours;
    document.getElementById("countdown-mins").innerHTML = minutes;
    document.getElementById("countdown-secs").innerHTML = seconds;

    // If the count down is over, write some text
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("countdown-days").innerHTML = "0";
        document.getElementById("countdown-hours").innerHTML = "0";
        document.getElementById("countdown-mins").innerHTML = "0";
        document.getElementById("countdown-secs").innerHTML = "0";
    }
}, 1000);

// Generate QR code
var qrcode = new QRCode(document.getElementById("qrcode"), {
    text: document.getElementById("text").value,
    width: 128,
    height: 128,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// Current ticket selected in the modal
let currentTicket = null;

// Initialize the form when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add input listeners to all ticket quantity fields
    const allQuantityInputs = document.querySelectorAll('.direct-quantity-input');
    allQuantityInputs.forEach(input => {
        // On input (as user types)
        input.addEventListener('input', function() {
            if (input.dataset.ticketId) {
                updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                updateTableTicketSelection(input);
            }
        });

        // On change (when user completes entry)
        input.addEventListener('change', function() {
            if (input.dataset.ticketId) {
                updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                updateTableTicketSelection(input);
            }
        });
    });
});

// Function to handle the "Add to Cart" button click
function updateTicketFromButton(button) {
    console.log("Adding to cart...");
    const card = button.closest('.spotify-ticket-card');
    const input = card.querySelector('.direct-quantity-input');

    // If user clicks buy with 0 quantity, set to 1
    if (parseInt(input.value) === 0 || !input.value) {
        input.value = 1;
    }

    // Update the selection
    if (input.dataset.ticketId) {
        window.updateTicketSelection(input);
    } else if (input.dataset.ticketTable) {
        window.updateTableTicketSelection(input);
    }

    // Add to cart with animation
    const quantity = parseInt(input.value) || 0;
    const ticketName = card.dataset.ticketName || "tickets";

    if (quantity <= 0) {
        // If quantity is 0 or invalid, update the button to show "Add to Cart"
        button.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
        button.classList.remove('ticket-selected');
        button.style.background = '';
        button.style.color = '';
        return; // Don't proceed with cart update
    }

    // Only proceed with cart update if quantity > 0
    if (quantity > 0) {
        // Get the form element
        const form = document.getElementById('addToCartForm');

        // Set form action to addtocart
        form.action = "{{ route('addtocart') }}";
        form.method = "POST";

        // Clear any old dynamic inputs
        document.querySelectorAll('.dynamic-input').forEach(el => el.remove());

        // Create a FormData object from the form (to include CSRF token)
        const formData = new FormData(form);

        // Add the no_redirect flag to prevent automatic redirect
        formData.append('no_redirect', 'true');

        // Add the current ticket to cart
        if (input.dataset.ticketId) {
            formData.append('ticket_ids[]', input.dataset.ticketId);
            formData.append('ticket_quantities[]', quantity);
        } else if (input.dataset.ticketTable) {
            formData.append('product_ids[]', card.querySelector('input[name="product_ids[]"]').value);
            formData.append('table_names[]', `${input.dataset.ticketName}, ${input.dataset.ticketPrice}`);
            formData.append('quantities[]', quantity);
        }

        // Update button to show processing state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
        button.style.background = '#FECC01';
        button.style.color = '#000';

        // Submit using fetch API with animation
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Cart response:', data);

            // Show success animation on button
            button.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} ${ticketName} added`;

            // Update cart count in header if possible
            if (data.total_cart_items) {
                // Use the global cart count updater function
                if (window.updateGlobalCartCount) {
                    window.updateGlobalCartCount(data.total_cart_items);
                } else {
                    // Fallback for older pages without the global updater
                    const cartCounter = document.querySelector('.cart-count');
                    if (cartCounter) {
                        cartCounter.textContent = data.total_cart_items;
                        cartCounter.classList.add('active');
                    }
                }
            }

            // Show the notification with option to go to cart
            showCartNotification(quantity, ticketName);

            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.style.color = '';

                // DO NOT reset the input value to 0 anymore
                // input.value = 0;

                // No need to update selection state here, since nothing changed
            }, 2000);
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            button.innerHTML = '<i class="fa-solid fa-times"></i> Error';

            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.style.color = '';
            }, 2000);
        });
    }
};

// Create a function to update the cart counter in the header
function updateCartCounter(quantity) {
    // Find the cart counter element in the header
    const cartCounter = document.querySelector('.cart-count');
    if (cartCounter) {
        // If we have the cart counter element, update it
        // We will rely on the server's response for the total count
        // in the updateTicketFromButton's fetch response
        cartCounter.style.display = 'block';
    } else {
        // If there's no counter yet, find the cart icon and add a counter
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) {
            const counter = document.createElement('span');
            counter.className = 'cart-count';
            counter.textContent = quantity; // Initial count
            counter.style.position = 'absolute';
            counter.style.top = '-8px';
            counter.style.right = '-8px';
            counter.style.backgroundColor = '#C04888';
            counter.style.color = 'white';
            counter.style.borderRadius = '50%';
            counter.style.width = '20px';
            counter.style.height = '20px';
            counter.style.display = 'flex';
            counter.style.alignItems = 'center';
            counter.style.justifyContent = 'center';
            counter.style.fontSize = '12px';
            counter.style.fontWeight = 'bold';

            // Make sure cartIcon is positioned relative
            cartIcon.style.position = 'relative';

            cartIcon.appendChild(counter);
        }
    }
}

// Function for table tickets - uses the same logic as regular tickets
function updateTableTicketFromButton(button) {
    updateTicketFromButton(button);
}

// Function to proceed to checkout
function proceedToCheckout() {
    // Set checkout direct flag
    document.getElementById('checkout_direct').value = '1';

    // Submit the form
    document.getElementById('addToCartForm').submit();
}

// Update the fixed footer when ticket selections change
function updateFixedBuyFooter() {
    // Just call updateCartSummary to handle everything in one place
    window.updateCartSummary();
}

window.proceedToCheckout = function() {
    // Get the form
    const form = document.getElementById('addToCartForm');

    // Check if we have any tickets selected
    const totalTickets = Object.values(window.selectedTickets).reduce((sum, ticket) => sum + ticket.quantity, 0);

    if (totalTickets <= 0) {
        return; // Don't proceed if no tickets selected
    }

    // Set the checkout_direct flag
    document.getElementById('checkout_direct').value = "1";

    // Clear any existing dynamic inputs
    document.querySelectorAll('.dynamic-input').forEach(el => el.remove());

    // Add all selected tickets to the form
    Object.values(window.selectedTickets).forEach(ticket => {
        if (ticket.quantity > 0) {
            // If it's a ticket type
            if (ticket.id) {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'ticket_ids[]';
                idInput.value = ticket.id;
                idInput.className = 'dynamic-input';
                form.appendChild(idInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'ticket_quantities[]';
                qtyInput.value = ticket.quantity;
                qtyInput.className = 'dynamic-input';
                form.appendChild(qtyInput);
            }
            // If it's a table ticket
            else if (ticket.table) {
                const productInput = document.createElement('input');
                productInput.type = 'hidden';
                productInput.name = 'product_ids[]';
                productInput.value = ticket.card.querySelector('input[name="product_ids[]"]').value;
                productInput.className = 'dynamic-input';
                form.appendChild(productInput);

                const tableInput = document.createElement('input');
                tableInput.type = 'hidden';
                tableInput.name = 'table_names[]';
                tableInput.value = `${ticket.name}, ${ticket.price}`;
                tableInput.className = 'dynamic-input';
                form.appendChild(tableInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'quantities[]';
                qtyInput.value = ticket.quantity;
                qtyInput.className = 'dynamic-input';
                form.appendChild(qtyInput);
            }
        }
    });

    // Submit the form
    form.submit();
};

window.updateTicketFromButton = function(button) {
    console.log("Adding to cart...");
    const card = button.closest('.spotify-ticket-card');
    const input = card.querySelector('.direct-quantity-input');

    // If user clicks buy with 0 quantity, set to 1
    if (parseInt(input.value) === 0 || !input.value) {
        input.value = 1;
    }

    // Update the selection
    if (input.dataset.ticketId) {
        window.updateTicketSelection(input);
    } else if (input.dataset.ticketTable) {
        window.updateTableTicketSelection(input);
    }

    // Add to cart with animation
    const quantity = parseInt(input.value) || 0;
    const ticketName = card.dataset.ticketName || "tickets";

    if (quantity <= 0) {
        // If quantity is 0 or invalid, update the button to show "Add to Cart"
        button.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
        button.classList.remove('ticket-selected');
        button.style.background = '';
        button.style.color = '';
        return; // Don't proceed with cart update
    }

    // Only proceed with cart update if quantity > 0
    if (quantity > 0) {
        // Get the form element
        const form = document.getElementById('addToCartForm');

        // Set form action to addtocart
        form.action = "{{ route('addtocart') }}";
        form.method = "POST";

        // Clear any old dynamic inputs
        document.querySelectorAll('.dynamic-input').forEach(el => el.remove());

        // Create a FormData object from the form (to include CSRF token)
        const formData = new FormData(form);

        // Add the no_redirect flag to prevent automatic redirect
        formData.append('no_redirect', 'true');

        // Add the current ticket to cart
        if (input.dataset.ticketId) {
            formData.append('ticket_ids[]', input.dataset.ticketId);
            formData.append('ticket_quantities[]', quantity);
        } else if (input.dataset.ticketTable) {
            formData.append('product_ids[]', card.querySelector('input[name="product_ids[]"]').value);
            formData.append('table_names[]', `${input.dataset.ticketName}, ${input.dataset.ticketPrice}`);
            formData.append('quantities[]', quantity);
        }

        // Update button to show processing state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
        button.style.background = '#FECC01';
        button.style.color = '#000';

        // Submit using fetch API with animation
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Cart response:', data);

            // Show success animation on button
            button.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} ${ticketName} added`;

            // Update cart count in header if possible
            if (data.total_cart_items) {
                // Use the global cart count updater function
                if (window.updateGlobalCartCount) {
                    window.updateGlobalCartCount(data.total_cart_items);
                } else {
                    // Fallback for older pages without the global updater
                    const cartCounter = document.querySelector('.cart-count');
                    if (cartCounter) {
                        cartCounter.textContent = data.total_cart_items;
                        cartCounter.classList.add('active');
                    }
                }
            }

            // Show the notification with option to go to cart
            showCartNotification(quantity, ticketName);

            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.style.color = '';

                // DO NOT reset the input value to 0 anymore
                // input.value = 0;

                // No need to update selection state here, since nothing changed
            }, 2000);
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            button.innerHTML = '<i class="fa-solid fa-times"></i> Error';

            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.style.color = '';
            }, 2000);
        });
    }
};

// Add a function to create the ripple effect for buttons
function createRipple(event) {
    const button = event.currentTarget;

    // Remove any existing ripple elements
    const ripples = button.getElementsByClassName("ripple");
    if (ripples.length > 0) {
        ripples[0].remove();
    }

    // Create ripple element
    const circle = document.createElement("span");
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;

    // Position the ripple where clicked
    const rect = button.getBoundingClientRect();
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${event.clientX - rect.left - radius}px`;
    circle.style.top = `${event.clientY - rect.top - radius}px`;
    circle.classList.add("ripple");

    // Add to button and auto-remove after animation
    button.appendChild(circle);
    setTimeout(() => {
        if (circle && circle.parentElement) {
            circle.parentElement.removeChild(circle);
        }
    }, 600);
}

// Add event listeners for ripple effect to all buy buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.buy-ticket-btn').forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Also add listeners to quantity inputs
    // ... existing code ...
});

// Add event listeners for ripple effect and other functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to all buy buttons
    document.querySelectorAll('.buy-ticket-btn').forEach(button => {
        button.addEventListener('click', createRipple);

        // Keep existing click handlers for ticket functionality
        button.addEventListener('click', function() {
            if (button.dataset.ticketId) {
                window.updateTicketFromButton(this);
            } else if (button.dataset.ticketTable) {
                window.updateTableTicketFromButton(this);
            }
        });
    });

    // Add input listeners to all ticket quantity fields
    document.querySelectorAll('.direct-quantity-input').forEach(input => {
        // On input (as user types)
        input.addEventListener('input', function() {
            // Get parent card and buy button
            const card = this.closest('.spotify-ticket-card');
            const buyButton = card.querySelector('.buy-ticket-btn');

            // If user types anything, immediately highlight the button
            if (this.value && parseInt(this.value) > 0) {
                buyButton.classList.add('ticket-selected');
            } else {
                buyButton.classList.remove('ticket-selected');
            }

            // Then update selection state
            if (input.dataset.ticketId) {
                window.updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                window.updateTableTicketSelection(input);
            }
        });

        // On change (when user completes entry)
        input.addEventListener('change', function() {
            if (input.dataset.ticketId) {
                window.updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                window.updateTableTicketSelection(input);
            }
        });
    });
});

// Main initialization when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // 1. Add ripple effect to all buy buttons
    document.querySelectorAll('.buy-ticket-btn').forEach(button => {
        // Add ripple effect
        button.addEventListener('click', createRipple);

        // Add ticket functionality
        button.addEventListener('click', function() {
            if (this.dataset.ticketId || this.closest('.spotify-ticket-card').querySelector('[data-ticket-id]')) {
                window.updateTicketFromButton(this);
            } else if (this.dataset.ticketTable || this.closest('.spotify-ticket-card').querySelector('[data-ticket-table]')) {
                window.updateTableTicketFromButton(this);
            }
        });
    });

    // 2. Add input listeners to all ticket quantity fields
    document.querySelectorAll('.direct-quantity-input').forEach(input => {
        // Force numeric input only, but don't restrict values
        input.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');

            // Get parent card and buy button
            const card = this.closest('.spotify-ticket-card');
            const buyButton = card.querySelector('.buy-ticket-btn');

            // If user types anything, immediately highlight the button
            if (this.value && parseInt(this.value) > 0) {
                buyButton.classList.add('ticket-selected');
            } else {
                buyButton.classList.remove('ticket-selected');
            }

            // Update the selection state
            if (input.dataset.ticketId) {
                updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                updateTableTicketSelection(input);
            }
        });

        // On focus, select all text for easy replacement
        input.addEventListener('focus', function() {
            this.select();
        });

        // On blur, ensure valid value but don't cap at max
        input.addEventListener('blur', function() {
            // If empty, set to 0
            if (this.value === '' || isNaN(parseInt(this.value, 10))) {
                this.value = 0;
            }

            // Trigger change event to update state
            const event = new Event('change', { bubbles: true });
            this.dispatchEvent(event);
        });

        // On change (when user completes entry)
        input.addEventListener('change', function() {
            if (input.dataset.ticketId) {
                updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                updateTableTicketSelection(input);
            }
        });
    });

    // 3. Initialize any other components
    try {
        // Set up countdown timer if present
        if (typeof countDownDate !== 'undefined') {
            // Countdown is already set up in the global scope
        }

        // Initialize QR code if present
        if (document.getElementById("qrcode") && typeof QRCode !== 'undefined') {
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: document.getElementById("text").value,
                width: 128,
                height: 128,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    } catch (e) {
        console.error('Error initializing components:', e);
    }
});

window.showCartNotification = function(quantity, ticketName) {
    // Create notification element if it doesn't exist
    let notification = document.getElementById('cart-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'cart-notification';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';  // Position at top right
        notification.style.backgroundColor = 'rgba(40, 40, 55, 0.9)';
        notification.style.color = 'white';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = '8px';
        notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.2)';
        notification.style.zIndex = '9999';
        notification.style.display = 'flex';
        notification.style.alignItems = 'center';
        notification.style.gap = '15px';
        notification.style.transform = 'translateY(-100px)';
        notification.style.opacity = '0';
        notification.style.transition = 'all 0.3s ease-out';
        notification.style.border = '1px solid rgba(255, 255, 255, 0.1)';
        document.body.appendChild(notification);
    }

    // Set content of notification
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-check-circle" style="color: #C04888; font-size: 24px;"></i>
            <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Added to Cart</div>
                <div style="font-size: 14px; color: rgba(255, 255, 255, 0.7);">${quantity} ${ticketName} ${quantity === 1 ? 'ticket' : 'tickets'} added</div>
            </div>
        </div>
        <a href="{{ route('cart') }}" style="background: #C04888; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; white-space: nowrap; transition: all 0.2s;">
            View Cart
        </a>
    `;

    // Show notification with animation
    setTimeout(() => {
        notification.style.transform = 'translateY(0)';
        notification.style.opacity = '1';

        // Hide notification after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateY(-100px)';
            notification.style.opacity = '0';
        }, 5000);
    }, 100);
};

// Add this at the beginning of your script to handle page loading
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all JS functionality after DOM is ready
    initializePage();
});

// Consolidate all initialization functions
function initializePage() {
    // Pre-style all ticket cards to prevent flicker
    const ticketCards = document.querySelectorAll('.spotify-ticket-card');
    ticketCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
    });

    // Initialize QR code if present
    if (document.getElementById("qrcode") && typeof QRCode !== 'undefined') {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: document.getElementById("text").value,
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    // Initialize quantity inputs
    const allQuantityInputs = document.querySelectorAll('.direct-quantity-input');
    allQuantityInputs.forEach(input => {
        // On input (as user types)
        input.addEventListener('input', function() {
            // Force numeric input
            this.value = this.value.replace(/[^0-9]/g, '');

            if (input.dataset.ticketId) {
                updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                updateTableTicketSelection(input);
            }
        });

        // On focus, select all text
        input.addEventListener('focus', function() {
            this.select();
        });

        // On blur, ensure valid value
        input.addEventListener('blur', function() {
            if (this.value === '' || isNaN(parseInt(this.value, 10))) {
                this.value = 0;
            }

            if (input.dataset.ticketId) {
                updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                updateTableTicketSelection(input);
            }
        });
    });

    // Initialize buy buttons
    document.querySelectorAll('.buy-ticket-btn').forEach(button => {
        button.addEventListener('click', createRipple);
        button.addEventListener('click', function() {
            if (this.dataset.ticketId || this.closest('.spotify-ticket-card').querySelector('[data-ticket-id]')) {
                updateTicketFromButton(this);
            } else if (this.dataset.ticketTable || this.closest('.spotify-ticket-card').querySelector('[data-ticket-table]')) {
                updateTableTicketFromButton(this);
            }
        });
    });

    // Initialize countdown timer
    initializeCountdown();
}

// Set the date we're counting down to - moved into its own function
function initializeCountdown() {
    var eventDateString = "{{$listonee['date']}}";
    var eventTimeString = "{{$listonee['time'] ?? ''}}";
    var countDownDate;

    // Try to parse the date, handling different formats
    try {
        // Create a complete datetime string
        var dateTimeStr = eventDateString;

        // Add time if available (just use the start time if it's a range)
        if (eventTimeString) {
            // If time contains a range (with hyphen), just take the start time
            if (eventTimeString.includes('-')) {
                var timeParts = eventTimeString.split('-');
                eventTimeString = timeParts[0].trim();
            }
            dateTimeStr += ' ' + eventTimeString;
        }

        // Try to parse the combined date and time
        countDownDate = new Date(dateTimeStr).getTime();

        // Check if the date is invalid
        if (isNaN(countDownDate)) {
            // Try to handle special formats like "December 15 @6:30pm"
            if (eventDateString.includes('@')) {
                // Replace @ with space
                var cleanDateStr = eventDateString.replace('@', ' ');
                countDownDate = new Date(cleanDateStr).getTime();
            }
        }

        // If still invalid, use a fallback date (e.g., current date + 30 days)
        if (isNaN(countDownDate)) {
            var fallbackDate = new Date();
            fallbackDate.setDate(fallbackDate.getDate() + 30);
            countDownDate = fallbackDate.getTime();
            console.log("Warning: Could not parse date string. Using fallback date.");
        }
    } catch (e) {
        // If all parsing attempts fail, use a fallback date
        var fallbackDate = new Date();
        fallbackDate.setDate(fallbackDate.getDate() + 30);
        countDownDate = fallbackDate.getTime();
        console.log("Error parsing date: " + e.message + ". Using fallback date.");
    }

    // Update the count down every 1 second
    var x = setInterval(function() {
        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result to elements if they exist
        if (document.getElementById("countdown-days")) {
            document.getElementById("countdown-days").innerHTML = days;
            document.getElementById("countdown-hours").innerHTML = hours;
            document.getElementById("countdown-mins").innerHTML = minutes;
            document.getElementById("countdown-secs").innerHTML = seconds;
        }

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            if (document.getElementById("countdown-days")) {
                document.getElementById("countdown-days").innerHTML = "0";
                document.getElementById("countdown-hours").innerHTML = "0";
                document.getElementById("countdown-mins").innerHTML = "0";
                document.getElementById("countdown-secs").innerHTML = "0";
            }
        }
    }, 1000);
}

// Global variable to track selected tickets
var selectedTickets = {};

// ... rest of your existing JavaScript ...
</script>
@endsection

<!-- Premium Animated Loader -->
<div class="premium-loader-overlay" id="premiumLoader">
    <div class="premium-loader-content">
        <div class="premium-spinner"></div>
        <div class="premium-loader-text">LOADING</div>
        <div class="premium-progress">
            <div class="premium-progress-bar" id="progressBar"></div>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="content-wrapper">
        @if(session()->Has("message"))
        <div class="alert-message alert-error" x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
            <div class="alert-content">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p>Please select at least one ticket quantity before proceeding</p>
            </div>
        </div>
    @endif

        <!-- Hero Section - Pixel Perfect Replica -->
        <section class="event-hero-section" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('{{ str_starts_with($listonee->image, 'http') ? $listonee->image : asset('storage/' . $listonee->image) }}') center/cover no-repeat;">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">INTERNATIONAL FILM FESTIVAL</h1>

                    <div class="hero-actions">
                        <div class="ticket-btn-container">
                            <button class="book-tickets-btn" onclick="document.querySelector('.pricing-section').scrollIntoView({behavior: 'smooth'})">
                                <i class="fas fa-ticket-alt" aria-hidden="true"></i>
                                Book Tickets
                            </button>
                        </div>

                        <div class="hero-action-buttons">
                            <button class="share-btn" onclick="shareEvent()">
                                <i class="fas fa-share-alt" aria-hidden="true"></i>
                            </button>
                            <button class="bookmark-btn" onclick="bookmarkEvent()">
                                <i class="far fa-bookmark" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="event-details">
                        <div class="event-detail">
                            <div class="detail-icon">
                                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                            </div>
                            <div class="detail-text">Saturday, August 10, 2024</div>
                        </div>
                        <div class="event-detail">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            </div>
                            <div class="detail-text">Cinema Plaza, Los Angeles</div>
                        </div>
                        <div class="event-detail">
                            <div class="detail-icon">
                                <i class="far fa-clock" aria-hidden="true"></i>
                            </div>
                            <div class="detail-text">3 hours</div>
                        </div>
                    </div>

                    <div class="event-tags">
                        <span class="event-tag">Live performance</span>
                        <span class="event-tag">Food & drinks</span>
                        <span class="event-tag">Indoor event</span>
                    </div>
                </div>
            </div>
        </section>

        <input id="text" type="hidden" value="{{ URL::current() }}" />

        <!-- Pricing Section -->
        <section class="pricing-section">
            <div class="pricing-header">
                <h2 class="section-title">Available Tickets</h2>
                <p class="pricing-subtitle">Choose from our selection of tickets for this event</p>
            </div>

            <form id="addToCartForm" method="POST" action="{{ route('addtocart') }}">
                @csrf
                <input type="hidden" id="checkout_direct" name="checkout_direct" value="0">
                @if($listonee->ticketTypes->count() > 0)
                    <div class="ticket-grid">
                        @foreach($listonee->ticketTypes as $ticket)
                            <div class="spotify-ticket-card {{ !$ticket->isOnSale() || $ticket->isSoldOut() ? 'inactive' : '' }}" data-ticket-id="{{ $ticket->id }}" data-ticket-name="{{ $ticket->name }}" data-ticket-price="{{ $ticket->price }}" data-ticket-description="{{ $ticket->description }}" data-ticket-max="{{ $ticket->capacity ? min(10, $ticket->getAvailableQuantity()) : 10 }}">
                                @if($ticket->isOnSale() && !$ticket->isSoldOut())
                                @elseif($ticket->isSoldOut())
                                @elseif(!$ticket->isOnSale() && $ticket->sales_start && $ticket->sales_start->isFuture())
                                @elseif(!$ticket->isOnSale() && $ticket->sales_end && $ticket->sales_end->isPast())
                                @else
                                @endif

                                <div class="ticket-header">
                                    <div class="ticket-name">{{ $ticket->name }}</div>
                                </div>

                                <div class="ticket-price">
                                    <span class="currency">₦</span>
                                    <span class="amount">{{ number_format($ticket->price) }}</span>
                                </div>

                                <div class="ticket-description">
                                    {{ $ticket->description }}
                                </div>

                                @if($ticket->capacity !== null)
                                    <!-- Removed ticket-capacity div -->
                                @endif

                                @if($ticket->isOnSale() && !$ticket->isSoldOut())
                                    <div class="direct-quantity-control">
                                        <input type="number"
                                               min="0"
                                               value="0"
                                               class="direct-quantity-input"
                                               data-ticket-id="{{ $ticket->id }}"
                                               data-ticket-price="{{ $ticket->price }}"
                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                               onchange="updateTicketSelection(this)"
                                               placeholder="Enter quantity">
                                    </div>
                                    <button type="button" class="buy-ticket-btn" onclick="updateTicketFromButton(this)" data-ticket-id="{{ $ticket->id }}">
                                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                                    </button>
                                    <input type="hidden" name="ticket_ids[]" value="{{ $ticket->id }}">
                                    <input type="hidden" name="ticket_quantities[]" value="0" class="hidden-quantity">
                                @else
                                    <div class="unavailable-overlay">
                                        <div class="unavailable-message">
                                            {{ $ticket->isSoldOut() ? 'SOLD OUT' : 'UNAVAILABLE' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ticket-grid">
                        @php
                            $tableNames = ['startingprice', 'earlybirds', 'tableone', 'tabletwo', 'tablethree', 'tablefour', 'tablefive', 'tablesix', 'tableseven', 'tableeight'];
                            $hasTickets = false;
                            $count = 0;
                        @endphp

                        @foreach ($tableNames as $tableName)
                            @if (!empty($listonee[$tableName]))
                                @php
                                    $hasTickets = true;
                                    $ticketInfo = explode('.', trim($listonee[$tableName]));
                                    $ticketNameAndPrice = explode(',', $ticketInfo[0]);
                                    $ticketName = count($ticketNameAndPrice) > 1 ? trim($ticketNameAndPrice[0]) : trim($ticketInfo[0]);
                                    $ticketPrice = count($ticketNameAndPrice) > 1 ? trim($ticketNameAndPrice[1]) : "Contact for price";
                                    $isSoldOut = strpos($listonee[$tableName], '.') !== false;
                                    $count++;
                                @endphp

                                <div class="spotify-ticket-card" data-ticket-table="{{ $tableName }}" data-ticket-name="{{ $ticketName }}" data-ticket-price="{{ $ticketPrice }}" data-ticket-description="Premium ticket for {{ $listonee['name'] }}">
                                    <input type="hidden" name="product_ids[]" value="{{ $listonee->id }}">
                                    <input type="hidden" name="table_names[]" value="{{ $ticketName }}, {{ $ticketPrice }}">
                                    <input type="hidden" name="quantities[]" value="0" class="hidden-quantity">

                                    @if($isSoldOut)
                                        <!-- Removed sold out ticket tag -->
                                    @else
                                        <!-- Removed available ticket tag -->
                                    @endif

                                    <div class="ticket-header">
                                        <div class="ticket-name">{{ $ticketName }}</div>
                                    </div>

                                    <div class="ticket-price">
                                        <span class="currency">₦</span>
                                        <span class="amount">{{ is_numeric($ticketPrice) ? number_format($ticketPrice) : $ticketPrice }}</span>
                                    </div>

                                    <div class="ticket-description">
                                        Premium ticket for {{ $listonee['name'] }}
                                    </div>

                                    @if($isSoldOut)
                                        <div class="unavailable-overlay">
                                            <div class="unavailable-message">SOLD OUT</div>
                                        </div>
                                    @else
                                        <div class="direct-quantity-control">
                                            <input type="number"
                                                   min="0"
                                                   value="0"
                                                   class="direct-quantity-input"
                                                   data-ticket-table="{{ $tableName }}"
                                                   data-ticket-name="{{ $ticketName }}"
                                                   data-ticket-price="{{ $ticketPrice }}"
                                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   onchange="updateTableTicketSelection(this)"
                                                   placeholder="Enter quantity">
                                        </div>
                                        <button type="button" class="buy-ticket-btn" onclick="updateTableTicketFromButton(this)" data-ticket-table="{{ $tableName }}">
                                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                                        </button>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasTickets)
                            <div class="no-tickets" style="grid-column: span 4; text-align: center; padding: 40px;">
                                <div class="no-tickets-icon">
                                    <i class="fa-solid fa-ticket-simple"></i>
                                </div>
                                <h3>No tickets available yet</h3>
                                <p>Ticket information will be updated soon. Check back later or contact the organizer for details.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </form>
        </section>

        <!-- Fixed Buy Now Footer -->
        <div class="fixed-buy-footer" id="fixedBuyFooter">
            <div class="fixed-buy-summary">
                <div class="fixed-buy-count" id="fixedBuyCount">0 tickets selected</div>
                <div class="fixed-buy-total">Total: <span class="amount" id="fixedBuyTotal">₦0</span></div>
            </div>
            <button class="fixed-buy-btn" onclick="proceedToCheckout()">
                <i class="fa-solid fa-shopping-bag"></i> Buy Now
            </button>
        </div>
    </div>
</div>

<script>
    // Share event function
    function shareEvent() {
        if (navigator.share) {
            // Use Web Share API if available
            navigator.share({
                title: 'International Film Festival',
                text: 'Join me at the International Film Festival on August 10, 2024',
                url: window.location.href
            })
            .then(() => console.log('Share successful'))
            .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback for browsers that don't support Web Share API
            // Create a temporary input to copy the URL
            const tempInput = document.createElement('input');
            tempInput.value = window.location.href;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            // Show a small notification
            const shareBtn = document.querySelector('.share-btn');
            const originalHTML = shareBtn.innerHTML;
            shareBtn.innerHTML = '<i class="fas fa-check"></i>';
            shareBtn.style.backgroundColor = '#4CAF50';

            setTimeout(() => {
                shareBtn.innerHTML = originalHTML;
                shareBtn.style.backgroundColor = '';
                alert('Link copied to clipboard!');
            }, 1000);
        }
    }

    // Bookmark event function
    function bookmarkEvent() {
        const bookmarkBtn = document.querySelector('.bookmark-btn');
        const isBookmarked = bookmarkBtn.classList.contains('bookmarked');

        if (isBookmarked) {
            // Remove bookmark
            bookmarkBtn.classList.remove('bookmarked');
            bookmarkBtn.querySelector('i').classList.remove('fas');
            bookmarkBtn.querySelector('i').classList.add('far');
        } else {
            // Add bookmark
            bookmarkBtn.classList.add('bookmarked');
            bookmarkBtn.querySelector('i').classList.remove('far');
            bookmarkBtn.querySelector('i').classList.add('fas');
        }
    }
</script>

<!-- Fix for cart functionality -->
<script>
    // Make sure DOM is fully loaded before executing scripts
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize selectedTickets if not already done
        if (typeof window.selectedTickets === 'undefined') {
            window.selectedTickets = {};
        }

        // Function to update ticket selection when input changes
        window.updateTicketSelection = function(input) {
            const card = input.closest('.spotify-ticket-card');

            // Force numeric input
            let quantity = input.value;

            // Remove any non-numeric characters
            quantity = quantity.replace(/[^0-9]/g, '');

            // Convert to integer
            quantity = parseInt(quantity || 0);

            // Update input value to ensure it's a clean number
            input.value = quantity;

            const ticketId = input.dataset.ticketId;
            const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));

            // Make sure quantity is not negative
            if (quantity < 0) {
                input.value = 0;
                quantity = 0;
            }

            // Update the hidden quantity input
            const hiddenQuantity = card.querySelector('.hidden-quantity');
            if (hiddenQuantity) {
                hiddenQuantity.value = quantity;
            }

            // Update the button text - Changed to "Add to Cart" regardless of selection
            const buyButton = card.querySelector('.buy-ticket-btn');
            if (buyButton) {
                if (quantity > 0) {
                    buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                    buyButton.classList.add('ticket-selected');
                } else {
                    buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                    buyButton.classList.remove('ticket-selected');
                }
            }

            // Store in the selected tickets object
            const ticketKey = ticketId;
            if (quantity > 0) {
                selectedTickets[ticketKey] = {
                    id: ticketId,
                    table: null,
                    name: card.dataset.ticketName,
                    price: card.dataset.ticketPrice,
                    quantity: quantity,
                    card: card
                };
            } else {
                // Remove if quantity is 0
                delete selectedTickets[ticketKey];
            }

            // Update the cart summary
            updateCartSummary();

            // Update fixed buy footer if it exists
            if (typeof updateFixedBuyFooter === 'function') {
                updateFixedBuyFooter();
            }
        };

        // Function to update table ticket selection when input changes
        window.updateTableTicketSelection = function(input) {
            const card = input.closest('.spotify-ticket-card');
            const quantity = parseInt(input.value) || 0;
            const ticketTable = input.dataset.ticketTable;
            const ticketName = input.dataset.ticketName;
            const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));

            // Make sure quantity is not negative
            if (quantity < 0) {
                input.value = 0;
                return;
            }

            // Update the hidden quantity input
            const hiddenQuantity = card.querySelector('.hidden-quantity');
            if (hiddenQuantity) {
                hiddenQuantity.value = quantity;
            }

            // Update the button text - Use "Add to Cart" regardless of selection
            const buyButton = card.querySelector('.buy-ticket-btn');
            if (buyButton) {
                if (quantity > 0) {
                    buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                    buyButton.classList.add('ticket-selected');
                } else {
                    buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                    buyButton.classList.remove('ticket-selected');
                }
            }

            // Store in the selected tickets object
            const ticketKey = 'table-' + ticketTable;
            if (quantity > 0) {
                selectedTickets[ticketKey] = {
                    id: null,
                    table: ticketTable,
                    name: ticketName,
                    price: ticketPrice,
                    quantity: quantity,
                    card: card
                };
            } else {
                // Remove if quantity is 0
                delete selectedTickets[ticketKey];
            }

            // Update the cart summary
            updateCartSummary();

            // Update fixed buy footer if it exists
            if (typeof updateFixedBuyFooter === 'function') {
                updateFixedBuyFooter();
            }
        };

        // Function to update cart summary totals
        window.updateCartSummary = function() {
            let totalTickets = 0;
            let totalAmount = 0;

            // Calculate totals based on selected tickets
            Object.values(selectedTickets).forEach(ticket => {
                if (ticket.quantity > 0) {
                    totalTickets += ticket.quantity;
                    const ticketPrice = parseFloat(ticket.price.toString().replace(/,/g, ''));
                    if (!isNaN(ticketPrice)) {
                        totalAmount += ticketPrice * ticket.quantity;
                    }
                }
            });

            // Skip updating count display as it was removed
            // Update fixed buy footer which is still in the document
            const fixedBuyCount = document.getElementById('fixedBuyCount');
            if (fixedBuyCount) {
                fixedBuyCount.textContent = `${totalTickets} ${totalTickets === 1 ? 'ticket' : 'tickets'} selected`;
            }

            const fixedBuyTotal = document.getElementById('fixedBuyTotal');
            if (fixedBuyTotal) {
                fixedBuyTotal.textContent = `₦${totalAmount.toLocaleString()}`;
            }

            // Show/hide fixed buy footer
            const fixedBuyFooter = document.getElementById('fixedBuyFooter');
            if (fixedBuyFooter) {
                if (totalTickets > 0) {
                    fixedBuyFooter.style.display = 'flex';
                    fixedBuyFooter.style.transform = 'translateY(0)';
                } else {
                    fixedBuyFooter.style.display = 'none';
                    fixedBuyFooter.style.transform = 'translateY(100%)';
                }
            }
        };

        // Define functions directly on window to ensure global availability
        window.updateTicketFromButton = function(button) {
            console.log("Adding to cart...");
            const card = button.closest('.spotify-ticket-card');
            const input = card.querySelector('.direct-quantity-input');

            // If user clicks buy with 0 quantity, set to 1
            if (parseInt(input.value) === 0 || !input.value) {
                input.value = 1;
            }

            // Update the selection
            if (input.dataset.ticketId) {
                window.updateTicketSelection(input);
            } else if (input.dataset.ticketTable) {
                window.updateTableTicketSelection(input);
            }

            // Add to cart with animation
            const quantity = parseInt(input.value) || 0;
            const ticketName = card.dataset.ticketName || "tickets";

            if (quantity <= 0) {
                // If quantity is 0 or invalid, update the button to show "Add to Cart"
                button.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                button.classList.remove('ticket-selected');
                button.style.background = '';
                button.style.color = '';
                return; // Don't proceed with cart update
            }

            // Only proceed with cart update if quantity > 0
            if (quantity > 0) {
                // Get the form element
                const form = document.getElementById('addToCartForm');

                // Set form action to addtocart
                form.action = "{{ route('addtocart') }}";
                form.method = "POST";

                // Clear any old dynamic inputs
                document.querySelectorAll('.dynamic-input').forEach(el => el.remove());

                // Create a FormData object from the form (to include CSRF token)
                const formData = new FormData(form);

                // Add the no_redirect flag to prevent automatic redirect
                formData.append('no_redirect', 'true');

                // Add the current ticket to cart
                if (input.dataset.ticketId) {
                    formData.append('ticket_ids[]', input.dataset.ticketId);
                    formData.append('ticket_quantities[]', quantity);
                } else if (input.dataset.ticketTable) {
                    formData.append('product_ids[]', card.querySelector('input[name="product_ids[]"]').value);
                    formData.append('table_names[]', `${input.dataset.ticketName}, ${input.dataset.ticketPrice}`);
                    formData.append('quantities[]', quantity);
                }

                // Update button to show processing state
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
                button.style.background = '#FECC01';
                button.style.color = '#000';

                // Submit using fetch API with animation
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Cart response:', data);

                    // Show success animation on button
                    button.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} ${ticketName} added`;

                    // Update cart count in header if possible
                    if (data.total_cart_items) {
                        // Use the global cart count updater function
                        if (window.updateGlobalCartCount) {
                            window.updateGlobalCartCount(data.total_cart_items);
                        } else {
                            // Fallback for older pages without the global updater
                            const cartCounter = document.querySelector('.cart-count');
                            if (cartCounter) {
                                cartCounter.textContent = data.total_cart_items;
                                cartCounter.classList.add('active');
                            }
                        }
                    }

                    // Show the notification with option to go to cart
                    showCartNotification(quantity, ticketName);

                    // Reset button after delay
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = '';
                        button.style.color = '';

                        // DO NOT reset the input value to 0 anymore
                        // input.value = 0;

                        // No need to update selection state here, since nothing changed
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    button.innerHTML = '<i class="fa-solid fa-times"></i> Error';

                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = '';
                        button.style.color = '';
                    }, 2000);
                });
            }
        };

        window.updateTableTicketFromButton = function(button) {
            window.updateTicketFromButton(button);
        };

        window.showCartNotification = function(itemCount) {
            // Deprecated - now redirecting to cart directly
        };

        // updateFixedBuyFooter is no longer needed as its functionality is in updateCartSummary
        window.updateFixedBuyFooter = function() {
            // Just call updateCartSummary to handle everything in one place
            window.updateCartSummary();
        };

        window.proceedToCheckout = function() {
            // Set checkout direct to 1
            document.getElementById('checkout_direct').value = "1";
            // Submit the form
            document.getElementById('addToCartForm').submit();
        };

        // Add click event listeners directly to all buttons
        document.querySelectorAll('.buy-ticket-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (button.dataset.ticketId) {
                    window.updateTicketFromButton(this);
                } else if (button.dataset.ticketTable) {
                    window.updateTableTicketFromButton(this);
                }
            });
        });

        // Add input listeners to all ticket quantity fields
        document.querySelectorAll('.direct-quantity-input').forEach(input => {
            // On input (as user types)
            input.addEventListener('input', function() {
                // Get parent card and buy button
                const card = this.closest('.spotify-ticket-card');
                const buyButton = card.querySelector('.buy-ticket-btn');

                // If user types anything, immediately highlight the button
                if (this.value && parseInt(this.value) > 0) {
                    buyButton.classList.add('ticket-selected');
                } else {
                    buyButton.classList.remove('ticket-selected');
                }

                // Then update selection state
                if (input.dataset.ticketId) {
                    window.updateTicketSelection(input);
                } else if (input.dataset.ticketTable) {
                    window.updateTableTicketSelection(input);
                }
            });

            // On change (when user completes entry)
            input.addEventListener('change', function() {
                if (input.dataset.ticketId) {
                    window.updateTicketSelection(input);
                } else if (input.dataset.ticketTable) {
                    window.updateTableTicketSelection(input);
                }
            });
        });
    });

    // Add a function to fix numeric input handling
    document.addEventListener('DOMContentLoaded', function() {
        // Fix for numeric inputs
        const quantityInputs = document.querySelectorAll('.direct-quantity-input');

        quantityInputs.forEach(input => {
            // On focus, select all text for easy replacement
            input.addEventListener('focus', function() {
                this.select();
            });

            // Ensure clean numeric input
            input.addEventListener('input', function() {
                // Remove non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');

                // If empty, allow it during typing
                if (this.value === '') return;

                // Parse as integer to remove leading zeros
                const parsed = parseInt(this.value, 10);

                // Update with parsed value
                if (!isNaN(parsed)) {
                    this.value = parsed;

                    // Check against max
                    const max = parseInt(this.getAttribute('max'), 10);
                    if (parsed > max) {
                        this.value = max;
                    }
                } else {
                    this.value = 0;
                }
            });

            // On blur, ensure valid value
            input.addEventListener('blur', function() {
                if (this.value === '' || isNaN(parseInt(this.value, 10))) {
                    this.value = 0;
                }

                // Trigger change event to update state
                const event = new Event('change', { bubbles: true });
                this.dispatchEvent(event);
            });
        });
    });
</script>

<script>
// Remove all loading code and make tickets display immediately
document.body.classList.remove('body-loading');

// Prevent any preview or promotional cards from displaying
document.addEventListener('DOMContentLoaded', function() {
    // Find and remove any standalone ticket cards outside the grid
    const standaloneCards = document.querySelectorAll('.spotify-ticket-card:not(.ticket-grid .spotify-ticket-card)');
    standaloneCards.forEach(card => card.remove());

    // Find and remove any elements that might be preview cards
    ['general-admission-card', 'ticket-card', 'admission-card', 'preview-card'].forEach(className => {
        document.querySelectorAll(`[class*="${className}"]`).forEach(el => {
            if (!el.closest('.ticket-grid')) {
                el.remove();
            }
        });
    });
});
</script>

<script>
// Premium loader animation and control
(function() {
    // Create loader at the start to ensure it appears immediately
    if (!document.getElementById('premiumLoader')) {
        const loaderHTML = `
            <div class="premium-loader-overlay" id="premiumLoader">
                <div class="premium-loader-content">
                    <div class="premium-spinner"></div>
                    <div class="premium-loader-text">LOADING</div>
                    <div class="premium-progress">
                        <div class="premium-progress-bar" id="progressBar"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('afterbegin', loaderHTML);
    }

    // Run immediately
    const loader = document.getElementById('premiumLoader');
    const progressBar = document.getElementById('progressBar');

    // Show loader immediately
    if (loader) loader.style.visibility = 'visible';
    if (loader) loader.style.opacity = '1';

    // Initial progress (simulate loading)
    let progress = 0;
    const loadingInterval = setInterval(function() {
        if (!progressBar) return;

        progress += 5;
        if (progress > 100) progress = 100;

        progressBar.style.width = progress + '%';

        if (progress === 100) {
            clearInterval(loadingInterval);

            // Add a slight delay before hiding loader to see the completed progress
            setTimeout(function() {
                if (loader) loader.style.opacity = '0';
                setTimeout(function() {
                    if (loader) loader.style.visibility = 'hidden';
                }, 700);
            }, 500);
        }
    }, 100);
})();

// Remove all loading code and make tickets display immediately
document.body.classList.remove('body-loading');

// Prevent any preview or promotional cards from displaying
// ... existing code ...
</script>
