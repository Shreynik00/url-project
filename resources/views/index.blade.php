<x-app-layout>
  <div class="container my-5">
      <h1 class="mb-4 text-center display-4">
          <strong><u>Shreynik & Abbas Shorturl Project</u></strong>
          {{-- <strong><u>Short URL</u></strong> --}}

      </h1>

      {{-- the styling changes are done by kaustubh sharma --}}

      @if (session('success'))
          <div class="alert alert-success text-center">{{ session('success') }}</div>
      @endif


      @if ($errors->any())
          <div class="alert alert-danger">
              <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif


      @auth
          <form method="POST" action="{{ route('Url.store') }}" class="bg-light p-5 rounded shadow-sm">
              @csrf
              <div class="mb-4 text-center">
                  <label for="original_url" class="form-label">
                      <strong>Enter URL:</strong>
                  </label>
                  <input type="url" class="form-control mb-3" id="original_url" name="original_url" placeholder="https://example.com" required><br><br>
                  <button type="submit" class="btn btn-primary btn-lg px-5 custom-btn">Shorten</button>
              </div>
          </form>
      @endauth

      <h2 class="mt-5 "><strong><u>Shortened URLs</u></strong></h2>


      <div class="table-responsive mt-4">
          <table class="table table-bordered table-hover text-center shadow-sm rounded">
              <thead class="table-dark">
                  <tr>
                      <th scope="col">Original URL</th>
                      <th scope="col">Short URL</th>
                      <th scope="col">Copy</th>
                      <th scope="col">Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($urls as $url)
                      <tr>
                          <td class="text-truncate">{{ $url->original_url }}</td>
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


  <style>
    
    .container{
      background-color: aqua
    }

    .table-responsive{
      background-color: aqua
    }
      .custom-btn {
          background: linear-gradient(to right, #10a437, #7a00b3);
          border: none;
          border-radius: 30px;
          color: white;
          font-weight: bold;
          transition: 0.3s ease;
          padding: 12px 40px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }



      .custom-btn:focus {
          outline: none;
          box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
      }

      .table {
          border: 2px solid #dee2e6;
          border-radius: 10px;
          overflow: hidden;
      }

      .table th,
      .table td {
          vertical-align: middle;
          padding: 14px;
          border: 1px solid #dee2e6;
      }

      .table-dark {
          background-color: #f50b32;
          color: #fff;
      }

      .text-truncate {
          max-width: 300px;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }
  </style>
</x-app-layout>
