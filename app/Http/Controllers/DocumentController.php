<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Status_Update;
use app\Mail\DocumentSubmittedMail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Store a newly submitted document in the storage and database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the form data including the file
        $request->validate([
            'document_title' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Ensure file validation is clear
            'theme' => 'required|string',
            'paper_type' => 'required|string', // Validate paper type input
        ]);

        // Generate a unique document key
        $documentKey = $this->generateDocumentKey(); // Use a method to encapsulate key generation logic

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('document_file')) {
            $filePath = $request->file('document_file')->store('documents'); // Store the file in the 'documents' directory
        }


        // Save document data into the database
        $document = Document::create([
            'document_title' => $request->document_title,
            'document_key' => $documentKey, // Save the unique document key separately
            'document_path' => $filePath, // Save the file path in the document_path field
            'theme' => $request->theme,
            'paper_type' => $request->paper_type, // Store the selected paper type
            'created_by' => Auth::id(),
            'user_id' => Auth::id(),
        ]);

           // Correctly retrieve the newly created document
        // $document = Document::find($document->id);
        $document = Document::with('creator')->find($document->id);
        // dd($document->creator);
        echo $document->creator->name;
        // Send email notification
        Mail::to(Auth::user()->email)->send(new DocumentSubmittedMail($document));

        return redirect()->back()->with('success', 'Document submitted successfully.');
    }

    /**
     * Generate a unique document key.
     *
     * @return string
     */
    private function generateDocumentKey()
    {
        // Generates a random 10-digit unique key
        return (string) rand(1000000000, 9999999999);
    }

    /**
     * Show the document submission history for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Fetch documents submitted by the authenticated user
        $documents = Document::where('user_id', $userId)->get(); // Corrected to Document
        // $documents = App\Models\Document::where('user_id', Auth::id())->get();
        // Return the view with the documents data
        return view('documents.history', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::find($id);

        return view('document.show', compact('document'));
    }

    public function download($id)
    {
        // Find the document
        $document = Document::findOrFail($id);

        // Assuming the file path is stored in the 'file_path' field
        $filePath = storage_path("app/{$document->document_path}");

        // dd($filePath);
        // Check if the file exists
        if (file_exists($filePath)) {
            // Return the file for download
            return response()->download($filePath);
        }

        // If the file doesn't exist, return an error response
        return redirect()->back()->with('error', 'File not found.');
    }

    public function destroy($id)
    {
        // Find the document
        $document = Document::findOrFail($id);

        // Assuming the file path is stored in the 'document_path' field
        $filePath = storage_path("app/{$document->document_path}");

        // Check if the file exists
        if (file_exists($filePath)) {
            // Delete the file from the storage
            unlink($filePath);
        }

        // Delete the document record from the database
        $document->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }


   public function updateStatus($id, $status)
{
    // Find the document by its ID
    $document = Document::find($id);
    
    // Update the document status
    $document->status = $status;
    $document->save();
    
     // Get the first_name directly from the users table using the user_id in the document
   $fullName = DB::table('users')
                ->where('id', $document->user_id)
                ->select(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))
                ->value('full_name');
// Fetches the first_name

    // Get the authenticated user
    $user = Auth::user();
    
    // Send the email with both $user and $document
    Mail::to($user->email)->send(new Status_Update($user, $document, $fullName));
    
    // Redirect back with success message
    return redirect()->back()->with('success', 'Document status updated successfully.');
}



}
