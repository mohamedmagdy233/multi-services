<title>
    {{  'لوحة التحكم' }} | تسجيل الدخول
</title>
<link rel="shortcut icon" type="image/x-icon" href="{{isset($setting->where('key', 'logo')->first()->value) ? asset($setting->where('key', 'logo')->first()->value) :  asset('assets/uploads/logo.png')}}">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap');
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Almarai';
    }
    html {
        font-size: 62.5%;
    }

    body {
        overflow-y: hidden;
        direction: {{ lang() == 'ar' ? 'rtl' : 'ltr' }};
        font-family: "Almarai", sans-serif;
        line-height: 1.6;
        color: #1a1a1a;
        font-size: 1.6rem;
        overflow-x: hidden;
    }
    a {
        color: #0285CE;
        text-decoration: none;
    }

    .container {
        display: grid;
        grid-template-rows: minmax(min-content, 100vh);
        grid-template-columns: repeat(2, 50vw);
    }
    .heading-secondary {
        font-size: 3rem;
    }
    .heading-primary {
        font-size: 5rem;
    }
    .span-blue {
        color: #b02a37;
    }
    .signup-container,
    .signup-form {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .signup-container {
        width: 100vw;
        padding: 10rem 10rem;
        align-items: flex-start;
        justify-content: flex-start;

        grid-column: 1 / 2;
        grid-row: 1;
    }
    .signup-form {
        max-width: 45rem;
        width: 100%;
    }
    .text-mute {
        color: #aaa;
    }

    .input-text {
        font-family: 'Almarai';
        font-size: 1.8rem;
        padding: 3rem 5rem 1rem 2rem;
        border: none;
        border-radius: 2rem;
        background: #eee;
        width: 100%;
    }
    .input-text:focus {
        outline-color: #0077b6;
    }

    .btn {
        padding: 2rem 3rem;
        border: none;
        background: #0285CE;
        color: #fff;
        border-radius: 1rem;
        cursor: pointer;
        font-family: 'Almarai';
        font-weight: 500;
        font-size: inherit;
    }
    .btn-login {
        align-self: flex-end;
        width: 100%;
        margin-top: 2rem;
        box-shadow: 0 5px 5px #00000020;
    }
    .btn-login:active {
        box-shadow: none;
    }
    .btn-login:hover {
        background: #0570ac;
    }
    .inp {
        position: relative;
    }
    .label {
        pointer-events: none;

        position: absolute;
        top: 2rem;
        left: 2rem;
        color: #00000070;
        font-weight: 500;
        font-size: 1.8rem;

        transition: all 0.2s;
        transform-origin: left;
    }
    .input-text:not(:placeholder-shown) + .label,
    .input-text:focus + .label {
        top: 0.7rem;
        transform: scale(0.75);
    }
    .input-text:focus + .label {
        color: #0077b6;
    }

    .input-icon {
        position: absolute;
        top: 2rem;
        right: 2rem;
        font-size: 2rem;
        color: #00000070;
    }
    .input-icon-password {
        cursor: pointer;
    }

    .btn-google {
        color: #222;
        background: #fff;
        border: solid 1px #eee;
        padding: 1.5rem;

        display: flex;
        justify-content: center;
        align-items: center;

        box-shadow: 0 1px 2px #00000020;
    }

    .btn-google img {
        width: 3rem;
        margin-right: 1rem;
    }

    .login-wrapper {
        max-width: 45rem;
        width: 100%;
    }
    .line-breaker .line {
        width: 50%;
        height: 1px;
        background: #eee;
    }
    .line-breaker {
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ccc;

        margin: 3rem 0;
    }
    .line-breaker span:nth-child(2) {
        margin: 0 2rem;
    }

    .welcome-container {
        background: #eeeeee75;
        grid-column: 2 / 3;
        grid-row: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 10rem;
    }
    .lg {
        font-size: 6rem;
        color: #b02a37;
    }
    .welcome-container img {
        width: 100%;
    }

    @media only screen and (max-width: 700px) {
        html {
            font-size: 54.5%;
        }
    }

    @media only screen and (max-width: 600px) {
        .signup-container {
            padding: 5rem;
        }
    }
    @media only screen and (max-width: 400px) {
        html {
            font-size: 48.5%;
        }

        .input-text:not(:placeholder-shown) + .label,
        .input-text:focus + .label {
            top: 0.6rem;
            transform: scale(0.75);
        }
        .label {
            font-size: 1.9rem;
        }
    }

    @media only screen and (max-width: 1200px) {
        .signup-container {
            grid-column: 1 / 3;
            grid-row: 1/3;
        }
        .welcome-container {
            display: none;
        }
    }

    @if(lang() == 'ar')
    .language-switcher {
        position: absolute;
        top: 10px; /* Adjust this value based on your header's height */
        right: 10px; /* Adjust for padding from the right edge */
        z-index: 1000; /* Ensures it's on top of other elements */
    }
    @else
    .language-switcher {
        position: absolute;
        top: 10px; /* Adjust this value based on your header's height */
        left: 10px; /* Adjust for padding from the right edge */
        z-index: 1000; /* Ensures it's on top of other elements */
    }
    @endif

    .dark-switcher {
        position: absolute;
        top: 10px; /* Adjust this value based on your header's height */
        left: 90px; /* Adjust for padding from the right edge */
        z-index: 1000; /* Ensures it's on top of other elements */
    }

    .btn-language {
        padding: 5px 10px;
        margin-left: 5px;
        background-color: #b02a37;
        color: #ffffff;
        text-decoration: none; /* Removes underline from links */
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-language:hover {
        background-color: #dedede; /* Slight hover effect */
    }

</style>
@toastr_css
