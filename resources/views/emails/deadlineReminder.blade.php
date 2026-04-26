<!DOCTYPE html>
<html>
<head>
    <title>Activity Due Date Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 16px;
        }
        .alert-danger {
            background-color: #fee;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📚 Activity Reminder</h2>

        @if($daysUntilDue === 0)
            <div class="alert alert-danger">
                <strong>⚠️ This activity is due TODAY!</strong>
            </div>
        @else
            <div class="alert alert-warning">
                <strong>📅 This activity is due TOMORROW!</strong>
            </div>
        @endif

        <div class="info-section">
            <div class="info-row">
                <strong>Activity Name:</strong> {{ $activity->name }}
            </div>

            <div class="info-row">
                <strong>Due Date:</strong> {{ $activity->due_date->format('F j, Y g:i A') }}
            </div>

            @if($activity->description)
                <div class="info-row">
                    <strong>Description:</strong><br>
                    {{ $activity->description }}
                </div>
            @endif

            @if($activity->grades)
                <div class="info-row">
                    <strong>Grades:</strong> {{ $activity->grades }}
                </div>
            @endif
        </div>

        <p style="margin-top: 20px; color: #666;">
            Please make sure to complete this activity on time.
        </p>
    </div>
</body>
</html>
