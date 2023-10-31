<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
<div id="login"  class="container box" >
<a href="{{route('registerform')}}" >Register</a>

<h2>Login </h2>
    <form id="login-form">
        @csrf
        <div class="form-group">
                        <label for="email">Email:</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            <span id="error-email"></span> <!-- This is where the error message will be displayed -->

        </div>
        <div class="form-group">
                        <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <span id="error-password"></span> <!-- This is where the error message will be displayed -->

        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</div>
    <div id="dashboard" style="display: none;">
        <!-- Dashboard content goes here -->
        <button id="logout-button">Logout</button>
        <h1>Products</h1>
        <form id="upload-form"  enctype="multipart/form-data">
                @csrf
                <h2>CSV Import</h2>
                <div class="form-group">
                    <label for="csv_file">Upload CSV File</label>
        
                    <input type="file" class="form-control-file" name="csv_file" accept=".csv">
                </div>
                <button type="submit" class="btn btn-primary">Upload CSV</button>
            </form>
    <table id="product-table" border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th width="20%">Name</th>
                <th width="10%">Price</th>
                <th>SKU</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>

    </div>
    <script>
        document.getElementById('login-form').addEventListener('submit', function (e) {
            e.preventDefault();

            fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Handle the API response here
                if (data.success == true) {
                    // Show the dashboard content
                    document.getElementById('dashboard').style.display = 'block';
                    document.getElementById('login').style.display = 'none';
                    const token =data.data.token; // Replace 'yourBearerToken' with your actual token
localStorage.setItem('access_token', token);
                    fetch('http://localhost:8000/api/products', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
    },
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
.then(data => {
    const productTable = document.getElementById('product-table');

    const tbody = productTable.getElementsByTagName('tbody')[0];

// Clear the existing rows
tbody.innerHTML = '';

data.data.forEach(product => {
    const newRow = tbody.insertRow(-1);
    for (const key in product) {
        const cell = newRow.insertCell();
        cell.appendChild(document.createTextNode(product[key]));
    }
});

})
.catch(error => {
    console.error('Error fetching products:', error);
});

                } else {
                    // Display an error message or handle the case where login was not successful
                    alert('Login failed. Please try again.');
                    
                }
            })
            .catch(error => {
                // Handle errors, e.g., network issues
                console.error('Fetch error:', error);
            });
        });
        
   
    const uploadForm = document.getElementById('upload-form');
    const csrfToken = '{{ csrf_token() }}'; // Retrieve the CSRF token
    const accessToken = localStorage.getItem('access_token');
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(uploadForm);

        try {
            const response = await fetch('{{ url("http://localhost:8000/api/products/import") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the header
                    'Authorization': `Bearer ${accessToken}`, // Include the Bearer token
                },
                body: formData,
            });

            if (response.ok) {
                const data = await response.json();
                const accessToken = localStorage.getItem('access_token');

              //  alert(data.message);
                fetch('http://localhost:8000/api/products', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'application/json',
    },
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
.then(data => {
    const productTable = document.getElementById('product-table');

    const tbody = productTable.getElementsByTagName('tbody')[0];

// Clear the existing rows
tbody.innerHTML = '';

data.data.forEach(product => {
    const newRow = tbody.insertRow(-1);
    for (const key in product) {
        const cell = newRow.insertCell();
        cell.appendChild(document.createTextNode(product[key]));
    }
});

})
.catch(error => {
    console.error('Error fetching products:', error);
});
            } else {
                const errorData = await response.json();
                alert('Error: ' + errorData.error);
            }
        } catch (error) {
            console.error('Upload error:', error);
        }
    });
    document.getElementById('logout-button').addEventListener('click', function () {
        // Make a POST request to the logout API
        const accessToken = localStorage.getItem('access_token');
        document.getElementById('dashboard').style.display = 'none';
        document.getElementById('login').style.display = 'block';
        fetch('http://localhost:8000/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': accessToken, // Replace with the user's access token
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Successfully logged out') {
                   

                // Handle successful logout (e.g., redirect or display a message)
            } else {
                // Handle logout error (e.g., display an error message)
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
        });
    });
</script>



</body>
</html>

   