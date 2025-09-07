<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;; 
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Document; 

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Define the available themes for filtering
        $themes = [
            'Party politics and Development in Nigeria (An Open Forum)',
            'Capacity for development',
            'Distorted and dependent Model of Capitalism',
            'Leadership, Politics and (Mis)Governance',
            'Citizenship, National Vision and Nation Building',
            'National Security and Socio-Economic Development',
            'Class Conflicts, Egalitarianism and Social Justice',
            'Patriotism, National Interest and the Future of Nigeria'
        ];

        // Check if a theme filter is applied
        $themeFilter = $request->get('theme');

        if ($user->user_type === 'admin') {
            // Fetch all documents with pagination if the user is an admin
            $query = Document::query();

            if ($themeFilter && $themeFilter !== 'all') {
                // Apply theme filter if selected
                $query->where('theme', $themeFilter);
            }

            $documents = $query->paginate(10); // Adjust the number as needed
            // Get the count of all submissions (for admin)
            $submissionCount = Document::count();
        } else {
            // Fetch only the documents associated with the authenticated user with pagination
            $query = Document::where('created_by', $user->id);

            if ($themeFilter && $themeFilter !== 'all') {
                // Apply theme filter if selected
                $query->where('theme', $themeFilter);
            }

            $documents = $query->paginate(10);
            // Get the count of submissions for the authenticated user
            $submissionCount = Document::where('created_by', $user->id)->count();
        }

        // Generate QR code for the user (based on email or ID)
        $barcode = QrCode::size(120)->generate($user->email); // You can change to $user->id

        // Assets for the dashboard view
        $assets = ['chart', 'animation'];

        // Pass data to the view
        // return view('dashboards.dashboard', compact('assets', 'documents', 'submissionCount', 'themes', 'themeFilter'));

        return view('dashboards.dashboard', compact('assets', 'documents', 'submissionCount', 'themes', 'themeFilter', 'barcode'));

    }

    public function downloadQR()
    {
        // Get the authenticated user
        $user = Auth::user();

        
    // Generate the QR code as PNG
    $qrCode = QrCode::format('png')->size(200)->generate($user->email);

    // Convert the QR code PNG to a base64 string for embedding
    $qrCodeBase64 = base64_encode($qrCode);

    // Prepare the HTML content for the PDF
    $html = '
    <html>
        <body>
            <h1>User QR Code</h1>
            <p>Here is your QR code:</p>
            <div><img src="data:image/png;base64,' . $qrCodeBase64 . '" alt="QR Code"></div>
        </body>
    </html>';
        // Load the HTML into dompdf
        $pdf = FacadePdf::loadHTML($html);

        // Return the generated PDF as a download
        return $pdf->download('user-qr-code.pdf');
    }

    //  public function index(Request $request)
    //     {

    //         // Get the authenticated user
    //         $user = Auth::user();

    //         // Retrieve the documents associated with the authenticated user
    //         $documents = Document::where('user_id', $user->id)->get();

    //        // Get the count of submissions for the authenticated user
    //         $submissionCount = Document::where('user_id', $user->id)->count();

    //         // Assets for the dashboard view
    //         $assets = ['chart', 'animation'];

    //         // // Pass data to the view
    //         // return view('dashboards.dashboard', compact('assets', 'documents'));
    //          // Pass data to the view
    //     return view('dashboards.dashboard', compact('assets', 'documents', 'submissionCount'));
    //     }



    public function submit_paper(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('submit-paper.submit-paper', compact('assets'));
    }

    /*
     * Menu Style Routs
     */
    public function horizontal(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.horizontal', compact('assets'));
    }
    public function dualhorizontal(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.dual-horizontal', compact('assets'));
    }
    public function dualcompact(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.dual-compact', compact('assets'));
    }
    public function boxed(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.boxed', compact('assets'));
    }
    public function boxedfancy(Request $request)
    {
        $assets = ['chart', 'animation'];
        return view('menu-style.boxed-fancy', compact('assets'));
    }

    /*
     * Pages Routs
     */
    public function billing(Request $request)
    {
        return view('special-pages.billing');
    }

    public function calender(Request $request)
    {
        $assets = ['calender'];
        return view('special-pages.calender', compact('assets'));
    }

    public function kanban(Request $request)
    {
        return view('special-pages.kanban');
    }

    public function pricing(Request $request)
    {
        return view('special-pages.pricing');
    }

    public function rtlsupport(Request $request)
    {
        return view('special-pages.rtl-support');
    }

    public function timeline(Request $request)
    {
        return view('special-pages.timeline');
    }


    /*
     * Widget Routs
     */
    public function widgetbasic(Request $request)
    {
        return view('widget.widget-basic');
    }
    public function widgetchart(Request $request)
    {
        $assets = ['chart'];
        return view('widget.widget-chart', compact('assets'));
    }
    public function widgetcard(Request $request)
    {
        return view('widget.widget-card');
    }

    /*
     * Maps Routs
     */
    public function google(Request $request)
    {
        return view('maps.google');
    }
    public function vector(Request $request)
    {
        return view('maps.vector');
    }

    /*
     * Auth Routs
     */
    public function signin(Request $request)
    {
        return view('auth.login');
    }
    public function signup(Request $request)
    {

        // $request->input('id');
        // // Fetch zones and branches data
        // $data['zones'] = zones::select('id', 'name')->get();
        // DB::enableQueryLog();
        // $data['branches'] = branches::where("zone_id", $request->zone)->get(["id", "name"]);
        // (DB::getQueryLog());


        // Check if it's a POST request or expects JSON
        if ($request->isMethod('post') || $request->expectsJson()) {
            // Return JSON response
            return response()->json($data);
        }

        // Return view for GET request
        // return view('auth.register', $data);
    }




    public function confirmmail(Request $request)
    {
        return view('auth.confirm-mail');
    }
    public function lockscreen(Request $request)
    {
        return view('auth.lockscreen');
    }
    public function recoverpw(Request $request)
    {
        return view('auth.recoverpw');
    }
    public function userprivacysetting(Request $request)
    {
        return view('auth.user-privacy-setting');
    }

    /*
     * Error Page Routs
     */

    public function error404(Request $request)
    {
        return view('errors.error404');
    }

    public function error500(Request $request)
    {
        return view('errors.error500');
    }
    public function maintenance(Request $request)
    {
        return view('errors.maintenance');
    }

    /*
     * uisheet Page Routs
     */
    public function uisheet(Request $request)
    {
        return view('uisheet');
    }

    /*
     * Form Page Routs
     */
    public function element(Request $request)
    {
        return view('forms.element');
    }

    public function wizard(Request $request)
    {
        return view('forms.wizard');
    }

    public function validation(Request $request)
    {
        return view('forms.validation');
    }

    /*
     * Table Page Routs
     */
    public function bootstraptable(Request $request)
    {
        return view('table.bootstraptable');
    }

    public function datatable(Request $request)
    {
        return view('table.datatable');
    }

    /*
     * Icons Page Routs
     */

    public function solid(Request $request)
    {
        return view('icons.solid');
    }

    public function outline(Request $request)
    {
        return view('icons.outline');
    }

    public function dualtone(Request $request)
    {
        return view('icons.dualtone');
    }

    public function colored(Request $request)
    {
        return view('icons.colored');
    }

    /*
     * Extra Page Routs
     */
    public function privacypolicy(Request $request)
    {
        return view('privacy-policy');
    }
    public function termsofuse(Request $request)
    {
        return view('terms-of-use');
    }
}
