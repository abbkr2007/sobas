

<!-- Enhanced Favicon Setup -->
<link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicon.png')}}">
<link rel="manifest" href="{{asset('manifest.json')}}">
<meta name="theme-color" content="#28a745">

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="{{asset('css/libs.min.css')}}">
<link rel="stylesheet" href="{{asset('css/hope-ui.css?v=1.1.0')}}">
<link rel="stylesheet" href="{{asset('css/custom.css?v=1.1.0')}}">
<link rel="stylesheet" href="{{asset('css/dark.css?v=1.1.0')}}">
<link rel="stylesheet" href="{{asset('css/rtl.css?v=1.1.0')}}">
<link rel="stylesheet" href="{{asset('css/customizer.css?v=1.1.0')}}">

<!-- Fullcalender CSS -->
<link rel='stylesheet' href="{{asset('vendor/fullcalendar/core/main.css')}}" />
<link rel='stylesheet' href="{{asset('vendor/fullcalendar/daygrid/main.css')}}" />
<link rel='stylesheet' href="{{asset('vendor/fullcalendar/timegrid/main.css')}}" />
<link rel='stylesheet' href="{{asset('vendor/fullcalendar/list/main.css')}}" />
<link rel="stylesheet" href="{{asset('vendor/Leaflet/leaflet.css')}}" />
<link rel="stylesheet" href="{{asset('vendor/vanillajs-datepicker/dist/css/datepicker.min.css')}}" />

<link rel="stylesheet" href="{{asset('vendor/aos/dist/aos.css')}}" />

<style>
    th.hide-search input{
       display: none;
    }

      /* Professional, consistent form styling across pages */
      form {
         width: 100%;
      }

      .card form,
      .login-content form,
      .auth-card form {
         max-width: 880px;
         margin-left: auto;
         margin-right: auto;
      }

      form .form-label {
         font-weight: 600;
         letter-spacing: 0.01em;
         color: #1f2937;
         margin-bottom: 0.45rem;
      }

      form .form-control,
      form .form-select,
      form textarea {
         min-height: 44px;
         border-radius: 10px;
         border: 1px solid #d7dee8;
         box-shadow: none;
         transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
      }

      form textarea.form-control {
         min-height: 110px;
      }

      form .form-control:focus,
      form .form-select:focus,
      form textarea:focus {
         border-color: #198754;
         box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.16);
         background-color: #fff;
      }

      form .form-group,
      form .mb-3,
      form .mb-4 {
         margin-bottom: 1rem;
      }

      /* Center Next and Previous actions in multi-step forms */
      #form-wizard1 fieldset {
         text-align: center;
      }

      #form-wizard1 .form-card {
         text-align: left;
      }

      #form-wizard1 .action-button,
      #form-wizard1 .action-button-previous,
      #form-wizard1 button[name="next"],
      #form-wizard1 button[name="previous"] {
         float: none !important;
         display: inline-flex;
         align-items: center;
         justify-content: center;
         min-width: 130px;
         border-radius: 999px;
         font-weight: 600;
         margin: 0.35rem;
         padding: 0.55rem 1.15rem;
      }

      @media (max-width: 575.98px) {
         .card form,
         .login-content form,
         .auth-card form {
            max-width: 100%;
         }

         #form-wizard1 .action-button,
         #form-wizard1 .action-button-previous,
         #form-wizard1 button[name="next"],
         #form-wizard1 button[name="previous"] {
            min-width: 110px;
         }
      }
 </style>
