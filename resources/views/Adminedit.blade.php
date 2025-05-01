<h1>Create New Event</h1>

<style>
    .fstyle {
        max-width: 700px;
        margin: 0 auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #C04888;
    }

    .forminner {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    input[type="text"],
    input[type="url"] {
        width: 100%;
        padding: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        border-radius: 5px;
        color: white;
        font-size: 16px;
    }

    input[type="url"] {
        color: #4fc3f7;
    }

    small {
        display: block;
        margin-top: 5px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
    }

    button {
        background: #C04888;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: block;
        width: 100%;
    }

    button:hover {
        background: #a73672;
        transform: translateY(-2px);
    }

    .image-preview {
        margin-top: 10px;
        border: 1px dashed rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        overflow: hidden;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .preview-placeholder {
        color: rgba(255, 255, 255, 0.4);
        font-size: 14px;
    }

    .error-message {
        color: #ff6b6b;
        margin-top: 5px;
        font-size: 14px;
    }
</style>

<form method="post" class="fstyle" action="/creationsuccess">
    {{ csrf_field() }}

    <div class="forminner">
        <label for="name">Event Name</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
        />
        @error('name')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <label for="location">Location</label>
        <input
            type="text"
            name="location"
            value="{{ old('location') }}"
        />
        @error('location')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <label for="description">Description</label>
        <input
            type="text"
            name="description"
            value="{{ old('description') }}"
        />
        @error('description')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <label for="date">Date</label>
        <input
            type="text"
            name="date"
            value="{{ old('date') }}"
        />
        @error('date')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <label for="image">Event Image URL (Cloudinary link)</label>
        <input
            type="url"
            name="image"
            placeholder="https://res.cloudinary.com/your-account/image/upload/v1234567890/your-image.jpg"
            value="{{ old('image') }}"
            onchange="previewImage(this, 'event-image-preview')"
        />
        <small>Paste your Cloudinary image URL here</small>
        <div class="image-preview" id="event-image-preview">
            <div class="preview-placeholder">Image preview will appear here</div>
        </div>
        @error('image')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <label for="heroimage">Hero Image URL (Cloudinary link)</label>
        <input
            type="url"
            name="heroimage"
            placeholder="https://res.cloudinary.com/your-account/image/upload/v1234567890/your-hero-image.jpg"
            value="{{ old('heroimage') }}"
            onchange="previewImage(this, 'hero-image-preview')"
        />
        <small>Paste your Cloudinary hero image URL here</small>
        <div class="image-preview" id="hero-image-preview">
            <div class="preview-placeholder">Image preview will appear here</div>
        </div>
        @error('heroimage')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <label for="herolink">Hero Link</label>
        <input
            type="text"
            name="herolink"
            value="{{ old('herolink') }}"
        />
        @error('herolink')
        <p class="error-message"> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">
        <button type="submit">Create Event</button>
    </div>
</form>

<script>
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
            previewContainer.innerHTML = '<div class="preview-placeholder" style="color: #ff6b6b;">Invalid image URL</div>';
        };

        img.onload = function() {
            previewContainer.innerHTML = '';
            previewContainer.appendChild(img);
        };

        img.src = url;
    }

    // Initialize previews on page load (for previously entered URLs)
    document.addEventListener('DOMContentLoaded', function() {
        const eventImageInput = document.querySelector('input[name="image"]');
        const heroImageInput = document.querySelector('input[name="heroimage"]');

        if (eventImageInput.value) {
            previewImage(eventImageInput, 'event-image-preview');
        }

        if (heroImageInput.value) {
            previewImage(heroImageInput, 'hero-image-preview');
        }
    });
</script>
