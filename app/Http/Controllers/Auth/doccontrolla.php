<?php
namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data including the file
        $request->validate([
            'document_title' => 'required|string|max:255',
            'document_key' => 'required|file|mimes:pdf,doc,docx|max:2048', // Accepts only PDF and Word documents, max size 2MB
            'theme' => 'required|string',
            'paper_type' => 'required|string' // Validate paper type input
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('document_key')) {
            $filePath = $request->file('document_key')->store('documents'); // Store the file in the 'documents' directory
        }

        // Save document data into the database
        Document::create([
            'document_title' => $request->document_title,
            'document_key' => $filePath, // Save the file path in the document_key field
            'theme' => $request->theme,
            'paper_type' => $request->paper_type, // Store the selected paper type
            'created_by' => Auth::id(),
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Document submitted successfully.');
    }
}



