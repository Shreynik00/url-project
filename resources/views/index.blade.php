<x-app-layout>
    <div class="container my-5">
      <h1 class="mb-4 text-center display-4"><strong><u>URL Shortener</u></strong></h1>
      
      {{-- the styling changes are done by kaustubh sharma --}}

      <!-- Success Message -->
      @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
      @endif
  
      <!-- Validation Errors -->
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

  
      <!-- URL Form -->
      @auth
        <form method="POST" action="{{ route('Url.store') }}" class="bg-light p-4 rounded shadow-sm">
            @csrf
            <div class="mt-5 text-center" >
                <label for="original_url" class="form-label" ><strong>Enter URL:</strong></label>
                <input type="url" class="form-control" id="original_url" name="original_url" placeholder="https://example.com" required>
                <button type="submit" class="btn btn-primary btn-lg px-5 custom-btn">Shorten</button> 
            </div>

            <br>

            <div class="text-center">

            </div>
      </form>
      @endauth

      <br>
  
      <!-- List of Shortened URLs -->
      <h2 class="mt-5 text-center"><strong><u>Shortened URLs</u></strong></h2>
  
      <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover text-center shadow-sm rounded" align="center">
          <thead class="table-dark">
            <tr>
              <th scope="col">Original URL</th>
              <th scope="col">Short URL</th>
              <th scope="col">Copy Count</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($urls as $url)
              <tr>
                <td class="text-truncate" style="max-width: 300px;">{{ $url->original_url }}</td>
                <td>
                  <a href="{{ $url->original_url }}" target="_blank" class="text-decoration-none">
                    {{ url($url->short_url) }}
                  </a>
                </td>
                <td id="copy-count-{{ $url->id }}">{{ $url->copy_count }}</td>
                <td>
                  <button class="btn btn-success btn-sm" onclick="copyToClipboard('{{ url($url->short_url) }}', {{ $url->id }})">
                    Copy
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted">No URLs have been shortened yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  
    <script>
      function copyToClipboard(shortUrl, id) {
        const tempInput = document.createElement('input');
        tempInput.value = shortUrl;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Short URL copied to clipboard!');
  
        fetch(`/urls/${id}/increment-copy-count`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.querySelector(`#copy-count-${id}`).textContent = data.copy_count;
          }
        })
        .catch(error => console.error('Error:', error));
      }
    </script>
  
    <!-- Custom CSS for Table Styling -->
    <style>

        .custom-btn {
            background: linear-gradient(to right, #007bff, #0056b3); /* Gradient background */
            border: none; /* Remove default border */
            border-radius: 30px; /* Rounded edges */
            color: white; /* Text color */
            font-weight: bold; /* Bold text */
            transition: all 0.3s ease; /* Smooth transition */
            padding: 10px 30px; /* Custom padding */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .custom-btn:hover {
            background: linear-gradient(to right, #0056b3, #004085); /* Darker gradient on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Deeper shadow on hover */
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .custom-btn:focus {
            outline: none; /* Remove focus outline */
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); /* Glow effect on focus */
        }
      .table {
        border: 2px solid #dee2e6; /* Light border around table */
        border-radius: 10px; /* Rounded corners */
        overflow: hidden; /* Keeps rounded borders intact */
      }
  
      .table th, .table td {
        vertical-align: middle; /* Align content vertically in the center */
        padding: 12px; /* Add padding inside cells */
        border: 1px solid #dee2e6; /* Add borders to each cell */
      }
  
      .table-dark {
        background-color: #343a40; /* Dark background for header */
        color: #ffffff; /* White text in header */
      }
  
      .table-hover tbody tr:hover {
        background-color: #f1f1f1; /* Light gray background on hover */
      }
  
      .text-truncate {
        max-width: 250px; /* Limit cell width */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis; /* Truncate long URLs */
      }
    </style>
  </x-app-layout>
  