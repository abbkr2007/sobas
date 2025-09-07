<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->paper_type }} Submission Acknowledgment</title>
</head>
<body>
<div class="header">
        <!-- Replace 'logo.png' with the actual path to your logo image -->
        <img src="https://member.asuu.org.ng/images/logo.png" alt="ASUU Logo" width="300">
    </div>
    <p>Dear {{ $document->creator->name }},</p>

    <p>This is to acknowledge the receipt of your {{ $document->paper_type }}:</p>
    <p>Title: <strong>{{ $document->document_title }}</strong></p>
    <p>Key: <strong>{{ $document->document_key }}</strong></p>

    <p>Please proofread the PDF file of your article(s), and if there is any revision to this {{ $document->paper_type }}, fill out the "Abstract Revision Checklist" by indicating the revised parts after it is registered.</p>

    <p>You will be notified accordingly about the progress of the {{ $document->paper_type }}. Meanwhile, you can follow the progress of your submission and other important information in your portal.</p>

    <p>Thank you very much for your kind attention.</p>

    <p>With our best regards,</p>
    <p>ASUU National Conference in Abuja</p>
    <p>Email: <a href="mailto:conference@asuu.org.ng">conference@asuu.org.ng</a></p>
</body>
</html>

