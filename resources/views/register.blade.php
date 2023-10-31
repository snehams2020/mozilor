<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script></head>
  <style type="text/css">
   .box{
    width:600px;
    margin:0 auto;
    border:1px solid #ccc;
    margin-top: 100px;;
   }
  </style>
 </head>

<div id="register"  class="container box">
<a href="{{route('login')}}" >Login</a>

<h2>Register </h2>
    <form id="register-form">
        @csrf
        <div class="form-group">
                        <label for="name">Name:</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;

            <input type="text" name="name" id="name" value="{{ old('name') }}">
            <span id="error-name"></span> <!-- This is where the error message will be displayed -->
        </div>
        <div class="form-group">
                        <label for="email">Email:</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;

            <input type="email" name="email" id="email" value="{{ old('email') }}">
            <span id="error-email"></span> <!-- This is where the error message will be displayed -->
        </div>
        <div class="form-group">
                        <label for="password">Password:</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;

            <input type="password" name="password" id="password">
            <span id="error-password"></span> <!-- This is where the error message will be displayed -->
        </div>
        <div class="form-group">
                        <label for="password">Confirm Password:</label>
            <input type="password" name="c_password" id="c_password">
            <span id="error-c_password"></span> <!-- This is where the error message will be displayed -->
        </div>
        <div>
            <button type="submit">register</button>
        </div>
    </form>
</div>
</body>
<script>
    document.getElementById('register-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this);

    fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json', // Set the content type to JSON
        },
        body: JSON.stringify({
            email: formData.get('email'),
            password: formData.get('password'),
            name: formData.get('name'),
            c_password: formData.get('c_password'),
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            // Handle a successful registration, e.g., show a success message
            alert('Registration successful');
        } else {
            // Handle registration errors, e.g., display validation errors
            console.error('Registration error:', data.message);

            // Loop through the validation errors and display them
            for (const field in data.data) {
                if (data.data.hasOwnProperty(field)) {
                    const errors = data.data[field];
                    const errorElement = document.getElementById(`error-${field}`);
                    if (errorElement) {
                        errorElement.textContent = errors[0]; // Display the first error message for each field
                    }
                }
            }
        }
    })
    .catch(error => {
        console.error('Registration error:', error);
    });
});

</script>

</html>