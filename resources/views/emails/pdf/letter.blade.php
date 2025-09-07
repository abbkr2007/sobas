<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abstract Acceptance Letter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        h3 {
            margin-bottom: 20px;
            text-align: center; /* Center the heading */
            font-weight: bold;  /* Make the heading bold */
        }
        p {
            margin: 10px 0;
        }
        .signature {
            margin-top: 40px;
        }


    </style>
</head>

<body>

   <div style="text-align: center;"> 
    <img src="https://conference.asuu.org.ng/wp-content/uploads/2024/08/log-1-2048x523.png" 
         alt="Logo" 
         class="logo" 
         style="width: 300px; height: auto; max-width: 100%;"/>
   </div>

    <h3>Abstract ({{ $document->document_key }}) Acceptance Letter</h3>

    <p><strong>From:</strong>LOC, ASUU National Conference 2024 in Abuja</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($document->updated_at)->format('F j, Y') }}</p>
    
    <p>Dear {{ $userName }},</p>
    
    <p>This is to convey the acceptance of your abstract:</p>
    
    <p><strong>Title:</strong> {{ $document->document_title }}<br>
    <strong>Key:</strong> {{ $document->document_key }}</p>

    <p>has been accepted for the Conference. Congratulations! </p>

    <p>Thank you very much for your kind attention and contribution to the ASUU National Conference.</p>

    <div class="signature">
        <p>With our best regards,</p>
        <img src="" alt="Signature" style="max-width: 200px; margin-top: 20px;">
        <p><strong>LOC, ASUU National Conference</strong><br>
        Email: <a href="mailto:conference@asuu.org.ng" style="color: #0066cc;">conference@asuu.org.ng</a>
        </p>
        <p>
        Website:<a href="conference.asuu.org.ng" style="color: #0066cc;">conference.asuu.org.ng</a>
        </p>
         <!-- Add your signature here -->
    </div>

</body>
</html>
