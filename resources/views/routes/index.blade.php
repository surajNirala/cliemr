<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Routes</title>
    <!-- Add Bootstrap CSS and Font Awesome for the copy icon -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">All Routes</h1>

    <!-- Table for Routes -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Method</th>
                <th>URI</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($routes as $route)
                <tr>
                    <td>{{ implode(', ', $route->methods()) }}</td>
                    <td>{{ $route->uri() }}</td>
                    <td>
                        @if (in_array($route->getName(),$permissions))
                            <span class="badge badge-success" id="route-name-{{ $loop->index }}">{{ $route->getName() ?: 'N/A' }}</span>
                        @else
                            <span id="route-name-{{ $loop->index }}">{{ $route->getName() ?: 'N/A' }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-outline-primary copy-btn" data-target="route-name-{{ $loop->index }}" title="Copy to clipboard">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Toast Notification for copy confirmation -->
    <div class="toast" id="copyToast" style="position: absolute; bottom: 20px; right: 20px; display: none;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="mr-auto">Route Copied!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            The route name has been copied to the clipboard.
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- JavaScript to handle copy functionality and show toast -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const copyButtons = document.querySelectorAll('.copy-btn');

        copyButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetId = button.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);
                
                // Create a temporary input to select and copy the text
                const tempInput = document.createElement('input');
                tempInput.value = targetElement.textContent; // Get text from the span
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                // Show toast notification
                const toast = document.getElementById('copyToast');
                $(toast).toast('show');
            });
        });
    });
</script>

</body>
</html>
