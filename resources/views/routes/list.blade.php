<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Routes</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome for the copy icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <style>
        /* Ensure table responsiveness */
        .table-container {
            overflow-x: auto;
        }
        .copy-btn {
            white-space: nowrap; /* Ensure the button text doesn't wrap */
        }
    </style>
</head>
<body>
@php
    $ignoredRoutes = [
        'ignition.healthCheck',
        'ignition.executeSolution',
        'ignition.shareReport',
        'ignition.scripts',
        'ignition.styles'
    ];
@endphp
<div class="container-fluid mt-5">
    @include("common.errors")
    @include("common.message")
    <div class="row">
        <!-- Routes Without Permissions -->
        <div class="col-lg-7 col-md-12 mb-4">
            <h3 class="text-center">Routes Without Permissions</h3>
            <div class="table-container">
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
                        @if($route->getName() && !in_array($route->getName(), $ignoredRoutes))
                            @if ($route->getName() && !in_array($route->getName(), $permissions))
                                <tr>
                                    <td>{{ implode(', ', $route->methods()) }}</td>
                                    <td>{{ $route->uri() }}</td>
                                    <td>
                                        <span id="route-name-{{ $loop->index }}">{{ $route->getName() ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ url('routes-permissions-linked') }}/{{$route->getName()}}" class="btn btn-outline-success btn-sm" title="Permissions linked">
                                            <i class="fas fa-link"></i>
                                        </a>
                                        <button class="btn btn-outline-primary btn-sm copy-btn" data-target="route-name-{{ $loop->index }}" title="Copy to clipboard">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Routes With Permissions -->
        <div class="col-lg-5 col-md-12">
            <h3 class="text-center">Routes with Permissions</h3>
            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Method</th>
                            <th>URI</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routes as $route)
                            @if ($route->getName() && in_array($route->getName(), $permissions))
                                <tr>
                                    <td>{{ implode(', ', $route->methods()) }}</td>
                                    <td>{{ $route->uri() }}</td>
                                    <td>
                                        <span class="badge badge-success" id="route-name-{{ $loop->index }}">{{ $route->getName() ?: 'N/A' }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Add Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Add JavaScript to handle copy functionality -->
<script>
    // Configure Toastr options
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        showDuration: 300,
        hideDuration: 1000,
        timeOut: 3000,
        extendedTimeOut: 1000,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };

    // Document ready function
    $(document).ready(function () {
        // Add click event for copy buttons
        $('.copy-btn').click(function () {
            const targetId = $(this).data('target');
            const targetText = $(`#${targetId}`).text();

            // Copy text to clipboard
            const tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(targetText).select();
            document.execCommand('copy');
            tempInput.remove();

            // Show success notification using Toastr
            toastr.success('Route name copied to clipboard!');
        });

        $('.permission-btn').click(function () {
            const targetId = $(this).data('target');
            const targetText = $(`#${targetId}`).text();

            // Copy text to clipboard
            const tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(targetText).select();
            document.execCommand('copy');
            tempInput.remove();

            // Show success notification using Toastr
            toastr.success('Route name copied to clipboard!');
        });
    });
</script>
</body>
</html>
