@extends('layouts.app')

@section('content')
<div class="search-not-found">
    <div class="search-container">
        <h1>No results for <mark>"{{$si}}"</mark></h1>

        <p>We searched our entire catalog but couldn't find anything matching your query.</p>

        <form action="/search" method="GET" class="search-form">
            <input type="text" name="name" placeholder="Try a different search" value="{{$si}}" autofocus>
            <button type="submit">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M19 19L14.65 14.65M17 9C17 13.4183 13.4183 17 9 17C4.58172 17 1 13.4183 1 9C1 4.58172 4.58172 1 9 1C13.4183 1 17 4.58172 17 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </form>

        <div class="back-home">
            <a href="/" class="home-link">Back to Home</a>
        </div>
    </div>
</div>

<style>
:root {
    --bg: #13121a;
    --text: #FFFFFE;
    --accent: #C04888;
    --accent-light: rgba(192, 72, 136, 0.1);
    --surface: #1c1b24;
    --subtle: rgba(255, 255, 254, 0.6);
    --border-color: rgba(255, 255, 255, 0.1);
}

.search-not-found {
    min-height: calc(100vh - 200px);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.search-container {
    max-width: 650px;
    width: 100%;
    padding: 3rem 2rem;
    text-align: center;
    animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    background-color: var(--surface);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

h1 {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    letter-spacing: -0.02em;
}

mark {
    color: var(--accent);
    background: none;
    position: relative;
    font-style: normal;
}

mark::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: var(--accent);
    transform-origin: right;
    transform: scaleX(0);
    animation: underline 0.6s 0.4s forwards cubic-bezier(0.16, 1, 0.3, 1);
}

p {
    font-size: 1.15rem;
    color: var(--subtle);
    margin-bottom: 2.5rem;
    font-weight: 400;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.search-form {
    position: relative;
    margin: 0 auto;
    max-width: 500px;
    height: 60px;
    display: flex;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

.search-form input {
    width: 100%;
    height: 100%;
    border: none;
    background: rgba(0, 0, 0, 0.2);
    color: var(--text);
    font-size: 1.125rem;
    padding: 0 60px 0 1.5rem;
    border-radius: 12px;
    outline: none;
    transition: box-shadow 0.3s ease, background 0.3s ease;
    border: 1px solid var(--border-color);
}

.search-form input:focus {
    box-shadow: 0 0 0 2px var(--accent);
    border-color: transparent;
}

.search-form input::placeholder {
    color: rgba(255, 255, 254, 0.4);
}

.search-form button {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 60px;
    background: none;
    border: none;
    color: var(--text);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s ease;
}

.search-form button:hover {
    color: var(--accent);
}

.back-home {
    margin-top: 2rem;
}

.home-link {
    display: inline-flex;
    align-items: center;
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 10px 24px;
    border-radius: 8px;
    background-color: rgba(192, 72, 136, 0.1);
}

.home-link:hover {
    background-color: rgba(192, 72, 136, 0.2);
    transform: translateY(-2px);
    color: var(--accent);
    text-decoration: none;
}

.home-link::before {
    content: "←";
    margin-right: 8px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes underline {
    from { transform: scaleX(0); }
    to { transform: scaleX(1); }
}

@media (max-width: 768px) {
    h1 {
        font-size: 1.8rem;
    }

    p {
        font-size: 1rem;
    }

    .search-form {
        height: 50px;
    }

    .search-container {
        padding: 2rem 1.5rem;
    }
}
</style>
@endsection
