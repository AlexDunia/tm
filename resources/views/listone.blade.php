@extends('layouts.app')

@section('content')
<style>
/* Spotify-inspired Ticket Grid Styling */
.ticket-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
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
    background: linear-gradient(145deg, rgba(40, 40, 55, 0.8), rgba(30, 30, 45, 0.95));
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
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
    transition: all 0.2s ease;
    margin-top: auto;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
    width: 100%;
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
    background: rgba(192, 72, 136, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
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

    .cart-summary {
        flex-direction: column;
        gap: 1.5rem;
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
    const quantity = parseInt(input.value) || 0;
    const ticketId = input.dataset.ticketId;
    const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));
    const maxQuantity = parseInt(input.max);

    // Make sure quantity is within limits
    if (quantity < 0) {
        input.value = 0;
        return;
    }

    if (quantity > maxQuantity) {
        input.value = maxQuantity;
        return;
    }

    // Update the hidden quantity input
    const hiddenQuantity = card.querySelector('.hidden-quantity');
    if (hiddenQuantity) {
        hiddenQuantity.value = quantity;
    }

    // Update the button text
    const buyButton = card.querySelector('.buy-ticket-btn');
    if (buyButton) {
        if (quantity > 0) {
            buyButton.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} Selected`;
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
    const maxQuantity = 10;

    // Make sure quantity is within limits
    if (quantity < 0) {
        input.value = 0;
        return;
    }

    if (quantity > maxQuantity) {
        input.value = maxQuantity;
        return;
    }

    // Update the hidden quantity input
    const hiddenQuantity = card.querySelector('.hidden-quantity');
    if (hiddenQuantity) {
        hiddenQuantity.value = quantity;
    }

    // Update the button text
    const buyButton = card.querySelector('.buy-ticket-btn');
    if (buyButton) {
        if (quantity > 0) {
            buyButton.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} Selected`;
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

    // Update the UI
    document.getElementById('selected-tickets-count').textContent = totalTickets;
    document.getElementById('total-amount').textContent = totalAmount.toLocaleString();

    // Enable/disable checkout button based on selection
    const checkoutButton = document.querySelector('.add-to-cart-button');
    if (checkoutButton) {
        if (totalTickets > 0) {
            checkoutButton.classList.remove('disabled');
            checkoutButton.disabled = false;
        } else {
            checkoutButton.classList.add('disabled');
            checkoutButton.disabled = true;
        }
    }
}

// Function to show a notification when items are added to cart
function showCartNotification(itemCount) {
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
            <i class="fa-solid fa-check-circle" style="color: #FECC01; font-size: 20px;"></i>
            <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Added to Cart</div>
                <div style="font-size: 14px; color: rgba(255, 255, 255, 0.7);">${itemCount} ${itemCount === 1 ? 'ticket' : 'tickets'} added</div>
            </div>
        </div>
        <a href="{{ route('cart') }}" style="background: #FECC01; color: black; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; white-space: nowrap;">
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
var countDownDate;

// Global variable to track selected tickets
var selectedTickets = {};

// Try to parse the date, handling different formats
try {
    // First attempt: Try the default format
    countDownDate = new Date(eventDateString).getTime();

    // Check if the date is invalid
    if (isNaN(countDownDate)) {
        // Try to handle special formats like "December 15 @6:30pm"
        if (eventDateString.includes('@')) {
            // Replace @ with space
            var cleanDateStr = eventDateString.replace('@', ' ');
            countDownDate = new Date(cleanDateStr).getTime();

            // If still invalid, try more parsing
            if (isNaN(countDownDate)) {
                // Extract parts
                var parts = eventDateString.split('@');
                var datePart = parts[0].trim();
                var timePart = parts[1].trim();

                // Convert timePart to 24-hour format if needed
                if (timePart.toLowerCase().includes('pm')) {
                    timePart = timePart.toLowerCase().replace('pm', '');
                    var timeComponents = timePart.split(':');
                    var hours = parseInt(timeComponents[0]);
                    if (hours < 12) hours += 12;
                    timePart = hours + ':' + timeComponents[1];
                } else if (timePart.toLowerCase().includes('am')) {
                    timePart = timePart.toLowerCase().replace('am', '');
                }

                countDownDate = new Date(datePart + ' ' + timePart).getTime();
            }
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
    const card = button.closest('.spotify-ticket-card');
    const input = card.querySelector('.direct-quantity-input');

    // If user clicks buy with 0 quantity, set to 1
    if (parseInt(input.value) === 0 || !input.value) {
        input.value = 1;
    }

    // Update the selection
    if (input.dataset.ticketId) {
        updateTicketSelection(input);
    } else if (input.dataset.ticketTable) {
        updateTableTicketSelection(input);
    }

    // Add to cart with notification
    const quantity = parseInt(input.value) || 0;

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

        // Add the ticket data to the form
        if (input.dataset.ticketId) {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ticket_ids[]';
            idInput.value = input.dataset.ticketId;
            idInput.className = 'dynamic-input';
            form.appendChild(idInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'ticket_quantities[]';
            qtyInput.value = quantity;
            qtyInput.className = 'dynamic-input';
            form.appendChild(qtyInput);
        } else if (input.dataset.ticketTable) {
            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_ids[]';
            productInput.value = card.querySelector('input[name="product_ids[]"]').value;
            productInput.className = 'dynamic-input';
            form.appendChild(productInput);

            const tableInput = document.createElement('input');
            tableInput.type = 'hidden';
            tableInput.name = 'table_names[]';
            tableInput.value = `${input.dataset.ticketName}, ${input.dataset.ticketPrice}`;
            tableInput.className = 'dynamic-input';
            form.appendChild(tableInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'quantities[]';
            qtyInput.value = quantity;
            qtyInput.className = 'dynamic-input';
            form.appendChild(qtyInput);
        }

        // Set no_redirect flag for AJAX submission
        const noRedirectInput = document.createElement('input');
        noRedirectInput.type = 'hidden';
        noRedirectInput.name = 'no_redirect';
        noRedirectInput.value = 'true';
        noRedirectInput.className = 'dynamic-input';
        form.appendChild(noRedirectInput);

        // Update button to show processing state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
        button.style.background = '#FECC01';
        button.style.color = '#000';

        // Submit the form using AJAX
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Show success state
            button.innerHTML = '<i class="fa-solid fa-check"></i> Added!';

            // Show notification
            showCartNotification(quantity);

            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.style.color = '';
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
    const footer = document.getElementById('fixedBuyFooter');
    if (!footer) return;

    let totalTickets = 0;
    let totalAmount = 0;

    // Calculate totals
    Object.values(selectedTickets).forEach(ticket => {
        totalTickets += ticket.quantity;
        const price = parseFloat(ticket.price.toString().replace(/,/g, ''));
        totalAmount += price * ticket.quantity;
    });

    // Update the UI
    document.getElementById('fixedBuyCount').textContent = `${totalTickets} ${totalTickets === 1 ? 'ticket' : 'tickets'} selected`;
    document.getElementById('fixedBuyTotal').textContent = `₦${totalAmount.toLocaleString()}`;

    // Show or hide the footer
    if (totalTickets > 0) {
        footer.classList.add('visible');
    } else {
        footer.classList.remove('visible');
    }
}
</script>
@endsection

<!-- Ticket Modal -->
<div class="ticket-modal-overlay" id="ticketModal">
    <div class="ticket-modal">
        <div class="modal-header">
            <h3>Select Tickets</h3>
            <button type="button" class="close-modal" onclick="closeTicketModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="modal-ticket-details">
                <div class="modal-ticket-name" id="modalTicketName">VIP Access</div>
                <div class="modal-ticket-price" id="modalTicketPrice">₦15,000</div>
                <div class="modal-ticket-description" id="modalTicketDescription">
                    Premium seating with complimentary drinks
                </div>
            </div>

            <div class="modal-quantity-selector">
                <div class="quantity-control-row">
                    <div class="quantity-label">Quantity</div>
                    <div class="modal-quantity-control">
                        <button type="button" class="modal-quantity-btn" onclick="decrementModalQuantity()">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="number" id="modalQuantityInput" class="modal-quantity-input" value="1" min="0" max="10" onchange="updateModalTotal()">
                        <button type="button" class="modal-quantity-btn" onclick="incrementModalQuantity()">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-ticket-list" id="otherTicketsSection">
                <div class="modal-ticket-list-title">Other Available Tickets</div>
                <div id="otherTicketsList">
                    <!-- Other tickets will be dynamically added here -->
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <div class="modal-total">
                Total: <span class="modal-total-amount" id="modalTotalAmount">₦15,000</span>
            </div>
            <button type="button" class="add-to-cart-modal-btn" id="addToCartModalBtn" onclick="addToCartFromModal()">
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
            </button>
            <button type="button" class="checkout-modal-btn" id="checkoutModalBtn" onclick="checkoutFromModal()">
                <i class="fa-solid fa-arrow-right"></i> Checkout
            </button>
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
        <section class="event-hero-section">
            <div class="event-hero-wrapper">
                <div class="event-date-badge">
                    <div class="event-date-icon">
                        <i class="fas fa-calendar-alt"></i>
                        {{ \Carbon\Carbon::parse($listonee['date'])->format('F d, Y') }}
                    </div>
                    <div class="event-time-icon">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($listonee['time'])->format('h:i A') }}
                    </div>
                </div>

                <h1 class="event-title">{{ $listonee['name'] }}</h1>
                <p class="event-description">{{ $listonee['description'] }}</p>

                <div class="event-location">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $listonee['location'] }}
                </div>

                <div class="event-info-container">
                    <div class="info-box ticket-types">
                        <div class="info-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="info-content">
                            <h3>Ticket Types</h3>
                            <p>{{ count($listonee->ticketTypes) }} ticket types available</p>
                        </div>
                    </div>

                    <div class="info-box attendees">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-content">
                            <h3>Attendees</h3>
                            <p>{{ $listonee->attendees_count ?? 0 }} people attending</p>
                        </div>
                    </div>

                    <div class="info-box share">
                        <div class="info-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="info-content">
                            <h3>Share</h3>
                            <div class="social-icons">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="event-qr-container">
                    <div id="qrcode"></div>
                </div>
            </div>

            <div class="event-image-container">
                <img src="{{ str_starts_with($listonee->image, 'http') ? $listonee->image : asset('storage/' . $listonee->image) }}" alt="{{ $listonee['name'] }}">
                <div class="countdown-overlay">
                    <div class="countdown-heading">Event starts in</div>
                    <div class="countdown-timer" id="event-countdown">
                        <div class="countdown-item">
                            <div class="countdown-value" id="countdown-days">00</div>
                            <div class="countdown-label">Days</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="countdown-hours">00</div>
                            <div class="countdown-label">Hours</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="countdown-mins">00</div>
                            <div class="countdown-label">Minutes</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="countdown-secs">00</div>
                            <div class="countdown-label">Seconds</div>
                        </div>
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
                                    <div class="ticket-tag available">Available</div>
                                @elseif($ticket->isSoldOut())
                                    <div class="ticket-tag sold-out">Sold Out</div>
                                @elseif(!$ticket->isOnSale() && $ticket->sales_start && $ticket->sales_start->isFuture())
                                    <div class="ticket-tag upcoming">Coming Soon</div>
                                @elseif(!$ticket->isOnSale() && $ticket->sales_end && $ticket->sales_end->isPast())
                                    <div class="ticket-tag sold-out">Ended</div>
                                @else
                                    <div class="ticket-tag">Unavailable</div>
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
                                    <div class="ticket-capacity">
                                        <div class="capacity-label">
                                            <i class="fa-solid fa-users"></i> Availability
                                        </div>
                                        <div class="capacity-bar-container">
                                            <div class="capacity-bar" style="width: {{ min(100, ($ticket->sold / $ticket->capacity) * 100) }}%"></div>
                                        </div>
                                        <div class="capacity-text">
                                            {{ $ticket->getAvailableQuantity() }} remaining
                                        </div>
                                    </div>
                                @endif

                                @if($ticket->isOnSale() && !$ticket->isSoldOut())
                                    <div class="direct-quantity-control">
                                        <input type="number" min="0" max="{{ $ticket->capacity ? min(10, $ticket->getAvailableQuantity()) : 10 }}" value="0" class="direct-quantity-input" data-ticket-id="{{ $ticket->id }}" data-ticket-price="{{ $ticket->price }}" onchange="updateTicketSelection(this)" placeholder="How many?">
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
                                        <div class="ticket-tag sold-out">Sold Out</div>
                                    @else
                                        <div class="ticket-tag available">Available</div>
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
                                            <input type="number" min="0" max="10" value="0" class="direct-quantity-input" data-ticket-table="{{ $tableName }}" data-ticket-name="{{ $ticketName }}" data-ticket-price="{{ $ticketPrice }}" onchange="updateTableTicketSelection(this)" placeholder="How many?">
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

                <div class="cart-summary">
                    <div class="summary-details">
                        <div class="summary-item">
                            <span class="summary-label">Selected Tickets:</span>
                            <span class="summary-value" id="selected-tickets-count">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total:</span>
                            <div class="summary-total">
                                <span class="currency">₦</span>
                                <span class="amount" id="total-amount">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="cart-actions">
                        <button type="button" onclick="proceedToCheckout()" class="add-to-cart-button disabled" disabled>
                            <i class="fa-solid fa-shopping-bag"></i>
                            Buy Now
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Event Details Section -->
        <section class="event-details-section">
            <div class="event-tabs">
                <div class="tab-header">
                    <button class="tab-btn active" data-tab="details">Event Details</button>
                    <button class="tab-btn" data-tab="venue">Venue Info</button>
                    <button class="tab-btn" data-tab="organizer">Organizer</button>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="details-tab">
                        <div class="event-full-description">
                            <h3>About This Event</h3>
                            <p>{{$listonee['description']}}</p>
                            <p>Join us for an unforgettable experience at {{$listonee['name']}}. This event promises to be a landmark occasion featuring amazing performances, great music, and an electric atmosphere.</p>

                            <h3>What to Expect</h3>
                            <ul class="event-features">
                                <li><i class="fa-solid fa-check"></i> Live performances from top artists</li>
                                <li><i class="fa-solid fa-check"></i> Premium sound and lighting</li>
                                <li><i class="fa-solid fa-check"></i> VIP experience available</li>
                                <li><i class="fa-solid fa-check"></i> Food and beverages</li>
                                <li><i class="fa-solid fa-check"></i> Security and safety measures</li>
                            </ul>

                            <div class="event-timeline">
                                <h3>Event Schedule</h3>
                                <div class="timeline-item">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-time">5:00 PM</div>
                                        <div class="timeline-title">Doors Open</div>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-time">6:30 PM</div>
                                        <div class="timeline-title">Opening Act</div>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-time">8:00 PM</div>
                                        <div class="timeline-title">Main Performance</div>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-time">11:00 PM</div>
                                        <div class="timeline-title">Event Ends</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="venue-tab">
                        <div class="venue-info">
                            <h3>Venue Information</h3>
                            <div class="venue-address">
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <h4>{{$listonee['location']}}</h4>
                                    <p>The venue features state-of-the-art facilities to ensure a comfortable and enjoyable experience for all attendees.</p>
                                </div>
                            </div>

                            <div class="venue-map">
                                <h4>Location Map</h4>
                                <div class="map-placeholder">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                    <p>Interactive map will be displayed here</p>
                                </div>
                            </div>

                            <div class="venue-facilities">
                                <h4>Facilities</h4>
                                <ul class="facilities-list">
                                    <li><i class="fa-solid fa-square-parking"></i> Parking Available</li>
                                    <li><i class="fa-solid fa-wheelchair-move"></i> Wheelchair Accessible</li>
                                    <li><i class="fa-solid fa-restroom"></i> Restrooms</li>
                                    <li><i class="fa-solid fa-utensils"></i> Food & Beverages</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="organizer-tab">
                        <div class="organizer-info">
                            <h3>Event Organizer</h3>
                            <div class="organizer-details">
                                <div class="organizer-logo">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div class="organizer-content">
                                    <h4>TixDemand Events</h4>
                                    <p>TixDemand is a premier event management company specializing in concerts, theatrical performances, and cultural events across Nigeria.</p>
                                    <div class="organizer-contact">
                                        <div><i class="fa-solid fa-envelope"></i> contact@tixdemand.com</div>
                                        <div><i class="fa-solid fa-phone"></i> +234 800 123 4567</div>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-organizer">
                                <h4>Have Questions?</h4>
                                <button class="contact-btn">
                                    <i class="fa-solid fa-message"></i> Contact Organizer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Events Section -->
        <section class="related-events-section">
            <h2 class="section-title">You Might Also Like</h2>
            <div class="related-events-slider">
                <div class="related-events-wrapper">
                    <!-- Related events would dynamically be added here -->
                    <div class="related-event-card">
                        <div class="event-card-image">
                            <img src="https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg" alt="Related Event">
                            <div class="event-card-date">
                                <span class="month">Dec</span>
                                <span class="day">15</span>
                            </div>
                        </div>
                        <div class="event-card-content">
                            <div class="event-card-category">Music</div>
                            <h3 class="event-card-title">Davido Live in Concert</h3>
                            <div class="event-card-location">
                                <i class="fa-solid fa-location-dot"></i> Eko Convention Center
                            </div>
                        </div>
                    </div>

                    <div class="related-event-card">
                        <div class="event-card-image">
                            <img src="https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/6th-Service-with-mudiaga_1_hjvlab.jpg" alt="Related Event">
                            <div class="event-card-date">
                                <span class="month">Jan</span>
                                <span class="day">20</span>
                            </div>
                        </div>
                        <div class="event-card-content">
                            <div class="event-card-category">Theatre</div>
                            <h3 class="event-card-title">The Lion King Musical</h3>
                            <div class="event-card-location">
                                <i class="fa-solid fa-location-dot"></i> Terra Kulture
                            </div>
                        </div>
                    </div>

                    <div class="related-event-card">
                        <div class="event-card-image">
                            <img src="https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339804/IMG-20250219-WA0008_entmwi.jpg" alt="Related Event">
                            <div class="event-card-date">
                                <span class="month">Feb</span>
                                <span class="day">05</span>
                            </div>
                        </div>
                        <div class="event-card-content">
                            <div class="event-card-category">Sports</div>
                            <h3 class="event-card-title">Nigeria vs Ghana</h3>
                            <div class="event-card-location">
                                <i class="fa-solid fa-location-dot"></i> National Stadium
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slider-controls">
                    <button class="slider-arrow prev">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <button class="slider-arrow next">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
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
            const quantity = parseInt(input.value) || 0;
            const ticketId = input.dataset.ticketId;
            const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));
            const maxQuantity = parseInt(input.max);

            // Make sure quantity is within limits
            if (quantity < 0) {
                input.value = 0;
                return;
            }

            if (quantity > maxQuantity) {
                input.value = maxQuantity;
                return;
            }

            // Update the hidden quantity input
            const hiddenQuantity = card.querySelector('.hidden-quantity');
            if (hiddenQuantity) {
                hiddenQuantity.value = quantity;
            }

            // Update the button text
            const buyButton = card.querySelector('.buy-ticket-btn');
            if (buyButton) {
                if (quantity > 0) {
                    buyButton.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} Selected`;
                    buyButton.classList.add('ticket-selected');
                } else {
                    buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                    buyButton.classList.remove('ticket-selected');
                }
            }

            // Store in the selected tickets object
            const ticketKey = ticketId;
            if (quantity > 0) {
                window.selectedTickets[ticketKey] = {
                    id: ticketId,
                    table: null,
                    name: card.dataset.ticketName,
                    price: card.dataset.ticketPrice,
                    quantity: quantity,
                    card: card
                };
            } else {
                // Remove if quantity is 0
                delete window.selectedTickets[ticketKey];
            }

            // Update the cart summary
            window.updateCartSummary();

            // Update fixed buy footer if it exists
            if (typeof window.updateFixedBuyFooter === 'function') {
                window.updateFixedBuyFooter();
            }
        };

        // Function to update table ticket selection when input changes
        window.updateTableTicketSelection = function(input) {
            const card = input.closest('.spotify-ticket-card');
            const quantity = parseInt(input.value) || 0;
            const ticketTable = input.dataset.ticketTable;
            const ticketName = input.dataset.ticketName;
            const ticketPrice = parseFloat(input.dataset.ticketPrice.toString().replace(/,/g, ''));
            const maxQuantity = 10;

            // Make sure quantity is within limits
            if (quantity < 0) {
                input.value = 0;
                return;
            }

            if (quantity > maxQuantity) {
                input.value = maxQuantity;
                return;
            }

            // Update the hidden quantity input
            const hiddenQuantity = card.querySelector('.hidden-quantity');
            if (hiddenQuantity) {
                hiddenQuantity.value = quantity;
            }

            // Update the button text
            const buyButton = card.querySelector('.buy-ticket-btn');
            if (buyButton) {
                if (quantity > 0) {
                    buyButton.innerHTML = `<i class="fa-solid fa-check"></i> ${quantity} Selected`;
                    buyButton.classList.add('ticket-selected');
                } else {
                    buyButton.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Add to Cart`;
                    buyButton.classList.remove('ticket-selected');
                }
            }

            // Store in the selected tickets object
            const ticketKey = 'table-' + ticketTable;
            if (quantity > 0) {
                window.selectedTickets[ticketKey] = {
                    id: null,
                    table: ticketTable,
                    name: ticketName,
                    price: ticketPrice,
                    quantity: quantity,
                    card: card
                };
            } else {
                // Remove if quantity is 0
                delete window.selectedTickets[ticketKey];
            }

            // Update the cart summary
            window.updateCartSummary();

            // Update fixed buy footer if it exists
            if (typeof window.updateFixedBuyFooter === 'function') {
                window.updateFixedBuyFooter();
            }
        };

        // Function to update cart summary totals
        window.updateCartSummary = function() {
            let totalTickets = 0;
            let totalAmount = 0;

            // Calculate totals based on selected tickets
            Object.values(window.selectedTickets).forEach(ticket => {
                if (ticket.quantity > 0) {
                    totalTickets += ticket.quantity;
                    const ticketPrice = parseFloat(ticket.price.toString().replace(/,/g, ''));
                    if (!isNaN(ticketPrice)) {
                        totalAmount += ticketPrice * ticket.quantity;
                    }
                }
            });

            // Update the UI
            document.getElementById('selected-tickets-count').textContent = totalTickets;
            document.getElementById('total-amount').textContent = totalAmount.toLocaleString();

            // Enable/disable checkout button based on selection
            const checkoutButton = document.querySelector('.add-to-cart-button');
            if (checkoutButton) {
                if (totalTickets > 0) {
                    checkoutButton.classList.remove('disabled');
                    checkoutButton.disabled = false;
                } else {
                    checkoutButton.classList.add('disabled');
                    checkoutButton.disabled = true;
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

            // Add to cart with notification
            const quantity = parseInt(input.value) || 0;

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

                // Add the ticket data to the form
                if (input.dataset.ticketId) {
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'ticket_ids[]';
                    idInput.value = input.dataset.ticketId;
                    idInput.className = 'dynamic-input';
                    form.appendChild(idInput);

                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = 'ticket_quantities[]';
                    qtyInput.value = quantity;
                    qtyInput.className = 'dynamic-input';
                    form.appendChild(qtyInput);
                } else if (input.dataset.ticketTable) {
                    const productInput = document.createElement('input');
                    productInput.type = 'hidden';
                    productInput.name = 'product_ids[]';
                    productInput.value = card.querySelector('input[name="product_ids[]"]').value;
                    productInput.className = 'dynamic-input';
                    form.appendChild(productInput);

                    const tableInput = document.createElement('input');
                    tableInput.type = 'hidden';
                    tableInput.name = 'table_names[]';
                    tableInput.value = `${input.dataset.ticketName}, ${input.dataset.ticketPrice}`;
                    tableInput.className = 'dynamic-input';
                    form.appendChild(tableInput);

                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = 'quantities[]';
                    qtyInput.value = quantity;
                    qtyInput.className = 'dynamic-input';
                    form.appendChild(qtyInput);
                }

                // Set no_redirect flag for AJAX submission
                const noRedirectInput = document.createElement('input');
                noRedirectInput.type = 'hidden';
                noRedirectInput.name = 'no_redirect';
                noRedirectInput.value = 'true';
                noRedirectInput.className = 'dynamic-input';
                form.appendChild(noRedirectInput);

                // Update button to show processing state
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
                button.style.background = '#FECC01';
                button.style.color = '#000';

                // Submit the form using AJAX
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Show success state
                    button.innerHTML = '<i class="fa-solid fa-check"></i> Added!';

                    // Show notification
                    window.showCartNotification(quantity);

                    // Reset button after delay
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = '';
                        button.style.color = '';
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
            console.log("Showing notification for", itemCount, "tickets");
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
                    <i class="fa-solid fa-check-circle" style="color: #FECC01; font-size: 20px;"></i>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 2px;">Added to Cart</div>
                        <div style="font-size: 14px; color: rgba(255, 255, 255, 0.7);">${itemCount} ${itemCount === 1 ? 'ticket' : 'tickets'} added</div>
                    </div>
                </div>
                <a href="{{ route('cart') }}" style="background: #FECC01; color: black; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; white-space: nowrap;">
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

        // Also define the updateFixedBuyFooter function
        window.updateFixedBuyFooter = function() {
            const footer = document.getElementById('fixedBuyFooter');
            if (!footer) return;

            let totalTickets = 0;
            let totalAmount = 0;

            // Calculate totals
            Object.values(window.selectedTickets).forEach(ticket => {
                totalTickets += ticket.quantity;
                const price = parseFloat(ticket.price.toString().replace(/,/g, ''));
                totalAmount += price * ticket.quantity;
            });

            // Update the UI
            document.getElementById('fixedBuyCount').textContent = `${totalTickets} ${totalTickets === 1 ? 'ticket' : 'tickets'} selected`;
            document.getElementById('fixedBuyTotal').textContent = `₦${totalAmount.toLocaleString()}`;

            // Show or hide the footer
            if (totalTickets > 0) {
                footer.classList.add('visible');
            } else {
                footer.classList.remove('visible');
            }
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
</script>


